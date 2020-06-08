<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType\TextAreaFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\BlockDesigner\Src\FieldType\FieldType;

class TextAreaFieldType extends FieldType
{
    protected $ftHandle = 'text_area';
    protected $dbType = 'X';

    public function getFieldDescription()
    {
        return t("A simple text area with no editing options");
    }

    public function getSearchableContent()
    {
        return '$content[] = $this->' . $this->data['slug'] . ';';
    }

    public function getViewContents()
    {
        $inner = 'h($' . $this->data['slug'] . ')';
        if (isset($this->data['nl2br']) && $this->data['nl2br'] == '1') {
            $inner = 'nl2br(' . $inner . ')';
        }
        return '<?php  if (isset($' . $this->data['slug'] . ') && trim($' . $this->data['slug'] . ') != "") { ?>' . $this->data['prefix'] . '<?php  echo ' . $inner . '; ?>' . $this->data['suffix'] . '<?php  } ?>';
    }

    public function getValidateFunctionContents()
    {
        return 'if(in_array("' . $this->data['slug'] . '",$this->btFieldsRequired) && trim($args["' . $this->data['slug'] . '"]) == ""){
            $e->add(t("The %s field is required.", "' . htmlspecialchars($this->data['label']) . '"));
        }';
    }

    public function getFormContents()
    {
        $fieldAttributes = array('rows' => 5);
        return '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'label' => $this->data['label'])) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'array' => '$btFieldsRequired')) . '
    ' . parent::generateFormContent('textarea', array('slug' => $this->data['slug'], 'attributes' => $fieldAttributes)) . '
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
            )
        );
    }
}