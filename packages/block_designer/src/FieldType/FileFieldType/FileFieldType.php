<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType\FileFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\BlockDesigner\Src\FieldType\FieldType;

class FileFieldType extends FieldType
{
    protected $ftHandle = 'file';
    protected $dbType = 'I';
    protected $uses = array('\File', 'Page', 'View', 'Permissions');
    protected $btExportFileColumn = true;

    public function getFieldDescription()
    {
        return t("A file field");
    }

    public function getViewContents()
    {
        $href = '<?php  echo $' . $this->data['slug'] . '->urls["relative"]; ?>';
        if (isset($this->data['download']) && $this->data['download'] == '1') {
            $href = '<?php  echo isset($' . $this->data['slug'] . '->urls["download"]) ? $' . $this->data['slug'] . '->urls["download"] : $' . $this->data['slug'] . '->urls["relative"]; ?>';
        }
        $newWindow = isset($this->data['url_target']) && is_string($this->data['url_target']) && $this->data['url_target'] == '1' ? true : false;
        return '<?php  if(isset($' . $this->data['slug'] . ') && $' . $this->data['slug'] . ' !== false){ ?>' . $this->data['prefix'] . '<a href="' . $href . '"' . ($newWindow ? ' target="_blank"' : null) . (isset($this->data['link_class']) && is_string($this->data['link_class']) && trim($this->data['link_class']) != '' ? ' class="' . $this->data['link_class'] . '"' : null) . '>
            <?php  echo isset($' . $this->data['slug'] . '_title) && trim($' . $this->data['slug'] . '_title) != "" ? h($' . $this->data['slug'] . '_title) : $' . $this->data['slug'] . '->getTitle(); ?>
        </a>' . $this->data['suffix'] . '<?php  } ?>';
    }

    public function getViewFunctionContents()
    {
        $code = '$' . $this->data['slug'] . '_id = (int)$this->' . $this->data['slug'] . ';
        $this->' . $this->data['slug'] . ' = false;
        if ($' . $this->data['slug'] . '_id > 0){
            $' . $this->data['slug'] . '_file = File::getByID($' . $this->data['slug'] . '_id);
            $fp = new Permissions($' . $this->data['slug'] . '_file);
	        if ($fp->canViewFile()) {
	            $urls = array(
	                \'relative\' => $' . $this->data['slug'] . '_file->getRelativePath()
	            );
		        $c = Page::getCurrentPage();
		        if ($c instanceof Page) {
			        $cID = $c->getCollectionID();
			        $urls[\'download\'] = View::url(\'/download_file\', $' . $this->data['slug'] . '_id, $cID);
		        }
		        $' . $this->data['slug'] . '_file->urls = $urls;
		        $this->' . $this->data['slug'] . ' = $' . $this->data['slug'] . '_file;
            }
        }
        $this->set("' . $this->data['slug'] . '", $this->' . $this->data['slug'] . ');';
        if (isset($this->data['title_field']) && $this->data['title_field'] == '1' && (!isset($this->data['title_field_required']) || $this->data['title_field_required'] != '1') && isset($this->data['title_field_fallback_value']) && trim($this->data['title_field_fallback_value']) != '') {
            $code .= '
        if (!isset($this->' . $this->data['slug'] . '_title) || trim($this->' . $this->data['slug'] . '_title) == "") {
            $this->set("' . $this->data['slug'] . '_title", \'' . $this->data['title_field_fallback_value'] . '\');
        }';
        }
        return $code;
    }

    public function getValidateFunctionContents()
    {
        $validation = 'if(in_array("' . $this->data['slug'] . '",$this->btFieldsRequired) && (trim($args["' . $this->data['slug'] . '"]) == "" || !is_object(\File::getByID($args["' . $this->data['slug'] . '"])))){
            $e->add(t("The %s field is required.", "' . htmlspecialchars($this->data['label']) . '"));
        }';
        if (isset($this->data['title_field']) && $this->data['title_field'] == '1' && isset($this->data['title_field_required']) && $this->data['title_field_required'] == '1') {
            $validation .= '
            if(trim($args["' . $this->data['slug'] . '_title"]) == ""){
            $e->add(t("The %s title field is required.", "' . htmlspecialchars($this->data['label']) . '"));
        }';
        }

        return $validation;
    }

    public function getFormContents()
    {
        $html = '';
        if ($this->data['ft_count'] <= 0) {
            $html .= '<?php  $al = Core::make("helper/concrete/asset_library"); ?>' . PHP_EOL;
        }
        $html .= '<?php  $' . $this->data['slug'] . '_o = null;
if ($' . $this->data['slug'] . ' > 0) {
    $' . $this->data['slug'] . '_o = File::getByID($' . $this->data['slug'] . ');
} ?>
<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'label' => $this->data['label'])) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'array' => '$btFieldsRequired')) . '
    ' . parent::generateFormContent('file', array('slug' => 'ccm-b-file-' . $this->data['slug'], 'postName' => $this->data['slug'], 'bf' => '$' . $this->data['slug'] . '_o')) . '
</div>';
        if (isset($this->data['title_field']) && $this->data['title_field'] == '1') {
            $html .= '
<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'] . '_title', 'label' => $this->data['label'], 'suffix' => ' . " " . t("Title")')) . '
    ' . parent::generateFormContent('text', array('slug' => $this->data['slug'] . '_title', 'attributes' => array('maxlength' => 255, 'placeholder' => isset($this->data['title_field_placeholder']) && trim($this->data['title_field_placeholder']) != '' ? htmlspecialchars($this->data['title_field_placeholder']) : null))) . '
</div>';
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
        if (isset($this->data['title_field']) && $this->data['title_field'] == '1') {
            $dbFields[] = array(
                'name' => $this->data['slug'] . '_title',
                'type' => 'C',
            );
        }
        return $dbFields;
    }
}