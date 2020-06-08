<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType\TextBoxFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\BlockDesigner\Src\FieldType\FieldType;

class TextBoxFieldType extends FieldType
{
    protected $ftHandle = 'text_box';
    protected $dbType = 'C';
    protected $canRepeat = true;

    public function getFieldDescription()
    {
        return t("A text input field");
    }

    public function getSearchableContent()
    {
        return '$content[] = $this->' . $this->data['slug'] . ';';
    }

    public function getViewContents()
    {
        return '<?php  if (isset($' . $this->data['slug'] . ') && trim($' . $this->data['slug'] . ') != "") { ?>' . $this->data['prefix'] . '<?php  echo h($' . $this->data['slug'] . '); ?>' . $this->data['suffix'] . '<?php  } ?>';
    }

    public function getViewFunctionContents()
    {
        if (isset($this->data['fallback_value']) && trim($this->data['fallback_value']) != '') {
            return 'if (trim($this->' . $this->data['slug'] . ') == "") {
            $this->set("' . $this->data['slug'] . '", \'' . $this->data['fallback_value'] . '\');
        }';
        }
        return;
    }

    public function getValidateFunctionContents()
    {
        return 'if (in_array("' . $this->data['slug'] . '", $this->btFieldsRequired) && trim($args["' . $this->data['slug'] . '"]) == "") {
            $e->add(t("The %s field is required.", "' . htmlspecialchars($this->data['label']) . '"));
        }';
    }

    public function getFormContents()
    {
        $fieldAttributes = array(
            'maxlength'   => $this->maxLength($this->data),
            'placeholder' => isset($this->data['placeholder']) && trim($this->data['placeholder']) != '' ? h($this->data['placeholder']) : null,
        );
        return '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'label' => $this->data['label'])) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'array' => '$btFieldsRequired')) . '
    ' . parent::generateFormContent('text', array('slug' => $this->data['slug'], 'attributes' => $fieldAttributes)) . '
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
                'size' => $this->maxLength($this->data),
            ),
        );
    }

    private function maxLength()
    {
        return isset($this->data['max_length']) && is_numeric($this->data['max_length']) && $this->data['max_length'] >= 1 && $this->data['max_length'] <= 255 ? (int)$this->data['max_length'] : 255;
    }
} 