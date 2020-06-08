<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType\CodeFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\BlockDesigner\Src\FieldType\FieldType;

class CodeFieldType extends FieldType
{
    protected $ftHandle = 'code';
    protected $dbType = 'X';

    public function getFieldDescription()
    {
        return t("A simple text area to place in your PHP, HTML, CSS (or other) code");
    }

    public function getSearchableContent()
    {
        return '$content[] = $this->' . $this->data['slug'] . ';';
    }

    public function getViewContents()
    {
        return '<?php  if (isset($' . $this->data['slug'] . ') && trim($' . $this->data['slug'] . ') != ""){ ?>' . $this->data['prefix'] . '<pre><code><?php  echo $' . $this->data['slug'] . '; ?></code></pre>' . $this->data['suffix'] . '<?php  } ?>';
    }

    public function getValidateFunctionContents()
    {
        return 'if(in_array("' . $this->data['slug'] . '",$this->btFieldsRequired) && trim($args["' . $this->data['slug'] . '"]) == ""){
            $e->add(t("The %s field is required.", "' . htmlspecialchars($this->data['label']) . '"));
        }';
    }

    public function getSaveFunctionContents()
    {
        return '$args[\'' . $this->data['slug'] . '\'] = htmlentities($args[\'' . $this->data['slug'] . '\']);';
    }

    public function getFormContents()
    {
        return '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'label' => $this->data['label'])) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'array' => '$btFieldsRequired')) . '
    ' . parent::generateFormContent('textarea', array('slug' => $this->data['slug'], 'attributes' => array('rows' => 5))) . '
</div>';
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