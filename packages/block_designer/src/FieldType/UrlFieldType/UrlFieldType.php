<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType\UrlFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\BlockDesigner\Src\FieldType\FieldType;

class UrlFieldType extends FieldType
{
    protected $ftHandle = 'url';
    protected $dbType = 'C';

    public function getFieldName()
    {
        return t("URL");
    }

    public function getFieldDescription()
    {
        return t("A text input field, where you would enter an http:// URL");
    }

    public function getViewContents()
    {
        $newWindow = isset($this->data['url_target']) && is_string($this->data['url_target']) && $this->data['url_target'] == '1' ? true : false;
        return '<?php  if (isset($' . $this->data['slug'] . ') && trim($' . $this->data['slug'] . ') != ""){ ?>' . $this->data['prefix'] . '<?php  echo "<a href=\"" . $' . $this->data['slug'] . ' . "\"' . ($newWindow ? ' target=\"_blank\"' : null) . (isset($this->data['class']) && is_string($this->data['class']) && trim($this->data['class']) != '' ? ' class=\"' . h($this->data['class']) . '\"' : null) . '>" . (trim($' . $this->data['slug'] . '_text) != "" ? $' . $this->data['slug'] . '_text : $' . $this->data['slug'] . ') . "</a>"; ?>' . $this->data['suffix'] . '<?php  } ?>';
    }

    public function getValidateFunctionContents()
    {
        return 'if(((!in_array("' . $this->data['slug'] . '",$this->btFieldsRequired) && trim($args["' . $this->data['slug'] . '"]) != "") || (in_array("' . $this->data['slug'] . '",$this->btFieldsRequired))) && !filter_var($args["' . $this->data['slug'] . '"], FILTER_VALIDATE_URL)){
            $e->add(t("The %s field does not have a valid URL.", "' . htmlspecialchars($this->data['label']) . '"));
        }';
    }

    public function getFormContents()
    {
        return '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'label' => $this->data['label'])) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'array' => '$btFieldsRequired')) . '
    ' . parent::generateFormContent('text', array('slug' => $this->data['slug'])) . '

    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'] . '_text', 'label' => $this->data['label'], 'suffix' => ' . " " . t(\'Text\')')) . '
    ' . parent::generateFormContent('text', array('slug' => $this->data['slug'] . '_text')) . '
</div>';
    }

    public function getFieldOptions()
    {
        return parent::view('field_options.php');
    }

    public function getDbFields()
    {
        return array(
            array(
                'name' => $this->data['slug'],
                'type' => $this->getDbType(),
            ),
            array(
                'name' => $this->data['slug'] . '_text',
                'type' => $this->getDbType(),
            ),
        );
    }
}