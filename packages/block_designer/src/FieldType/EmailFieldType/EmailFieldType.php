<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType\EmailFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\BlockDesigner\Src\FieldType\FieldType;
use Concrete\Core\File\Service\File;

class EmailFieldType extends FieldType
{
    protected $ftHandle = 'email';
    protected $dbType = 'C';

    public function getFieldDescription()
    {
        return t("An email field");
    }

    public function getSearchableContent()
    {
        return '$content[] = $this->' . $this->data['slug'] . ';';
    }

    public function getViewContents()
    {
        if (isset($this->data['anchor_field']) && $this->data['anchor_field'] == '1') {
            return '<?php  if (isset($' . $this->data['slug'] . ') && trim($' . $this->data['slug'] . ') != ""){ ?>' . $this->data['prefix'] . '<a href="mailto:<?php  echo $' . $this->data['slug'] . '; ?>' . (isset($this->data['subject']) && trim($this->data['subject']) != '' ? '?subject=' . htmlspecialchars($this->data['subject']) : null) . '"' . (isset($this->data['class']) && is_string($this->data['class']) && trim($this->data['class']) != '' ? ' class="' . h($this->data['class']) . '"' : null) . '><?php  echo $' . $this->data['slug'] . '; ?></a>' . $this->data['suffix'] . '<?php  } ?>';
        } else {
            return '<?php  if (isset($' . $this->data['slug'] . ') && trim($' . $this->data['slug'] . ') != ""){ ?>' . $this->data['prefix'] . '<?php  echo h($' . $this->data['slug'] . '); ?>' . $this->data['suffix'] . '<?php  } ?>';
        }
    }

    public function getExtraFunctionsContents()
    {
        if ($this->data['ft_count'] > 0) {
            return;
        }
        $fileService = new File();
        return $fileService->getContents($this->ftDirectory . 'elements' . DIRECTORY_SEPARATOR . 'extra_functions.txt');
    }

    public function getValidateFunctionContents()
    {
        return 'if(in_array("' . $this->data['slug'] . '",$this->btFieldsRequired) && trim($args["' . $this->data['slug'] . '"]) == ""){
            $e->add(t("The %s field is required.", "' . htmlspecialchars($this->data['label']) . '"));
        }
        elseif(trim($args["' . $this->data['slug'] . '"]) != \'\' && !$this->isValidEmail($args["' . $this->data['slug'] . '"])){
            $e->add(t("The %s field is an invalid email address.", "' . htmlspecialchars($this->data['label']) . '"));
        }';
    }

    public function getFieldOptions()
    {
        return parent::view('field_options.php');
    }

    public function getFormContents()
    {
        return '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'label' => $this->data['label'])) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'array' => '$btFieldsRequired')) . '
    ' . parent::generateFormContent('text', array('slug' => $this->data['slug'], 'attributes' => array('maxlength' => 255))) . '
</div>';
    }

    public function getDbFields()
    {
        return array(
            array(
                'name' => $this->data['slug'],
                'type' => $this->getDbType(),
                'size' => 255,
            )
        );
    }
}