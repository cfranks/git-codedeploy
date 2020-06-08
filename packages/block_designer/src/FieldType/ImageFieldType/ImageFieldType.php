<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType\ImageFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Core\Asset\AssetList;
use Concrete\Package\BlockDesigner\Src\FieldType\FieldType;

class ImageFieldType extends FieldType
{
    protected $ftHandle = 'image';
    protected $dbType = 'I';
    protected $uses = array('\File', 'Page');
    protected $btExportFileColumn = true;

    public function getFieldDescription()
    {
        return t("An image selector");
    }

    public function validate()
    {
        $errors = array();
        if (isset($this->data['thumbnail']) && $this->data['thumbnail'] == '1') {
            $values = array(
                'height' => t('height'),
                'width'  => t('width'),
            );
            foreach ($values as $key => $value) {
                if (isset($this->data[$key]) && trim($this->data[$key]) != '') {
                    $integer = (int)$this->data[$key];
                    if ($integer <= 0) {
                        $errors[] = t('The %s for the image on row #%s has to be higher than 0.', $value, $this->data['row_id']);
                    } else {
                        if (!ctype_digit($this->data[$key])) {
                            $errors[] = t('The %s for the image on row #%s has to be a numeric value (floating numbers disallowed).', $value, $this->data['row_id']);
                        }
                    }
                } else {
                    $errors[] = t('No %s for the image on row #%s has been entered.', $value, $this->data['row_id']);
                }
            }
        }
        return empty($errors) ? true : implode('<br/>', $errors);
    }

    private function generateImage($field_data = array())
    {
        $field_data = array_filter($field_data);
        $attributes = implode(' ', array_map(function ($v, $k) {
            return sprintf('%s="%s"', $k, $v);
        }, $field_data, array_keys($field_data)));

        return '<img ' . $attributes . '/>';
    }

    private function generateLink($inner = '')
    {
        $html = null;
        switch ($this->data['link']) {
            case '1':
                $html = '<?php 
        $' . $this->data['slug'] . '_page = false;
        if (!empty($' . $this->data['slug'] . '_link) && (($page = Page::getByID($' . $this->data['slug'] . '_link)) && $page->error === false)) {
            $' . $this->data['slug'] . '_page = $page;
        }
        if ($' . $this->data['slug'] . '_page) {
            $linkURL = $' . $this->data['slug'] . '_page->getCollectionLink();
            echo \'<a href="\' . $linkURL . \'"' . (isset($this->data['link_class']) && is_string($this->data['link_class']) && trim($this->data['link_class']) != '' ? ' class="' . $this->data['link_class'] . '"' : null) . '>\';
        } ?>
        ' . $inner . '<?php 
        if ($' . $this->data['slug'] . '_page) {
            echo \'</a>\';
        } ?>';
                break;
            case '2':
                $html = '<?php 
        if (trim($' . $this->data['slug'] . '_url) != "") {
            echo \'<a href="\' . $' . $this->data['slug'] . '_url . \'"' . (isset($this->data['link_class']) && is_string($this->data['link_class']) && trim($this->data['link_class']) != '' ? ' class="' . $this->data['link_class'] . '"' : null) . '>\';
        } ?>
        ' . $inner . '<?php 
        if (trim($' . $this->data['slug'] . '_url) != "") {
            echo \'</a>\';
        } ?>';
                break;
        }
        return $html;
    }

    public function getViewContents()
    {
        $field_data = array(
            'src'   => '<?php  echo $' . $this->data['slug'] . '->getURL(); ?>',
            'alt'   => '<?php  echo $' . $this->data['slug'] . '->getTitle(); ?>',
            'class' => isset($this->data['class']) && is_string($this->data['class']) && trim($this->data['class']) != '' ? htmlentities(preg_replace('!\s+!', ' ', $this->data['class'])) : null,
        );
        if (isset($this->data['thumbnail']) && $this->data['thumbnail'] == '1') {
            $width = (int)$this->data['width'];
            $height = (int)$this->data['height'];
            $crop = isset($this->data['crop']) && $this->data['crop'] == '1' ? true : false;
            $field_data['src'] = '<?php  echo $thumb->src; ?>';
            $img = $this->generateImage($field_data);
            if (isset($this->data['link']) && in_array($this->data['link'], array(1, 2))) {
                $img = $this->generateLink($img);
            }
            return '<?php  if ($' . $this->data['slug'] . ') { ?>' . $this->data['prefix'] . '<?php 
    $im = Core::make(\'helper/image\');
    if ($thumb = $im->getThumbnail($' . $this->data['slug'] . ', ' . $width . ', ' . $height . ', ' . var_export($crop, true) . ')) {
        ?>' . $img . '<?php 
    } ?>' . $this->data['suffix'] . '
<?php  } ?>';
        } else {
            $img = $this->generateImage($field_data);
            if (isset($this->data['link']) && in_array($this->data['link'], array(1, 2))) {
                $img = $this->generateLink($img);
            }
            return '<?php  if ($' . $this->data['slug'] . '){ ?>' . $this->data['prefix'] . $img . $this->data['suffix'] . '<?php  } ?>';
        }
    }

    public function getViewFunctionContents()
    {
        return '
        if ($this->' . $this->data['slug'] . ') {
            $f = \File::getByID($this->' . $this->data['slug'] . ');
            if (is_object($f)) {
                $this->set("' . $this->data['slug'] . '", $f);
            }
            else {
                $this->set("' . $this->data['slug'] . '", false);
            }
        }';
    }

    public function getValidateFunctionContents()
    {
        $validation = 'if (in_array("' . $this->data['slug'] . '", $this->btFieldsRequired) && (trim($args["' . $this->data['slug'] . '"]) == "" || !is_object(\File::getByID($args["' . $this->data['slug'] . '"])))){
            $e->add(t("The %s field is required.", "' . htmlspecialchars($this->data['label']) . '"));
        }';
        if (isset($this->data['link']) && in_array($this->data['link'], array(1, 2))) {
            switch ($this->data['link']) {
                case '1':
                    $validation .= ' elseif (is_object(\File::getByID($args["' . $this->data['slug'] . '"])) && (($page = Page::getByID($args["' . $this->data['slug'] . '_link"])) && $page->error !== false)){
              $e->add(t("The %s link field is required.", "' . htmlspecialchars($this->data['label']) . '"));
        }';
                    break;
                case '2':
                    $validation .= 'elseif (is_object(\File::getByID($args["' . $this->data['slug'] . '"])) && (trim($args["' . $this->data['slug'] . '_url"]) == "" || !filter_var($args["' . $this->data['slug'] . '_url"], FILTER_VALIDATE_URL))){
              $e->add(t("The %s URL field does not have a valid URL.", "' . htmlspecialchars($this->data['label']) . '"));
        }';
                    break;
            }
        }
        return $validation;
    }

    public function getFormContents()
    {
        $html = '';
        if ($this->data['ft_count'] <= 0) {
            $html .= '<?php  $al = Core::make("helper/concrete/asset_library"); ?>' . PHP_EOL;
        }
        $html .= '<div class="form-group">
    <?php 
    if ($' . $this->data['slug'] . ' > 0) {
        $' . $this->data['slug'] . '_o = File::getByID($' . $this->data['slug'] . ');
        if ($' . $this->data['slug'] . '_o->isError()) {
            unset($' . $this->data['slug'] . '_o);
        }
    } ?>
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'label' => $this->data['label'])) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'array' => '$btFieldsRequired')) . '
    ' . parent::generateFormContent('file', array('slug' => 'ccm-b-file-' . $this->data['slug'], 'postName' => $this->data['slug'], 'bf' => '$' . $this->data['slug'] . '_o')) . '
</div>';
        if (isset($this->data['link']) && in_array($this->data['link'], array(1, 2))) {
            switch ($this->data['link']) {
                case '1':
                    $html .= PHP_EOL . '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'] . '_link', 'label' => $this->data['label'], 'suffix' => ' . " " . t("link")')) . '
    ' . parent::generateFormContent('page_selector', array('slug' => $this->data['slug'] . '_link')) . '
</div>';
                    break;
                case '2':
                    $html .= PHP_EOL . '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'] . '_url', 'label' => $this->data['label'], 'suffix' => ' . " " . t("url")')) . '
    ' . parent::generateFormContent('required') . '
    ' . parent::generateFormContent('text', array('slug' => $this->data['slug'] . '_url', 'attributes' => array('maxlength' => 255))) . '
</div>';
                    break;
            }

        }
        return $html;
    }

    public function getFieldOptions()
    {
        return parent::view('field_options.php');
    }

    public function getDbFields()
    {
        $dbFields = array(
            array(
                'name'       => $this->data['slug'],
                'type'       => $this->getDbType(),
                'attributes' => array(
                    'default' => '0',
                    'notnull' => true,
                ),
            )
        );
        if (isset($this->data['link']) && in_array($this->data['link'], array(1, 2))) {
            switch($this->data['link']){
                case '1':
                    $dbFields[] = array(
                        'name' => $this->data['slug'] . '_link',
                        'type' => 'I',
                    );
                    break;
                case '2':
                    $dbFields[] = array(
                        'name' => $this->data['slug'] . '_url',
                        'type' => 'C',
                    );
                    break;
            }
        }
        return $dbFields;
    }
}