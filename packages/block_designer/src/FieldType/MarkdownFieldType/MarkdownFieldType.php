<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType\MarkdownFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\BlockDesigner\Src\FieldType\FieldType;

class MarkdownFieldType extends FieldType
{
    protected $ftHandle = 'markdown';
    protected $dbType = 'X';

    public function getFieldDescription()
    {
        return t("A simple text area where you can use all markdown functionalities");
    }

    public function getSearchableContent()
    {
        $lines = array();
        if ($this->data['ft_count'] <= 0) {
            $lines[] = 'if(!class_exists(\'Parsedown\')){
            include_once(\'' . $this->data['btDirectory'] . 'libraries' . DIRECTORY_SEPARATOR . 'parsedown' . DIRECTORY_SEPARATOR . 'Parsedown.php' . '\');
        }';
        }
        $lines[] = '$content[] = (new Parsedown())->text($this->' . $this->data['slug'] . ');';
        return implode(PHP_EOL . '        ', $lines);
    }

    public function getViewContents()
    {
        return '<?php  if (isset($' . $this->data['slug'] . ') && trim($' . $this->data['slug'] . ') != ""){ ?>' . $this->data['prefix'] . '<?php  echo (new Parsedown())->text($' . $this->data['slug'] . '); ?>' . $this->data['suffix'] . '<?php  } ?>';
    }

    public function getViewFunctionContents(){
        if ($this->data['ft_count'] <= 0) {
            return 'if (!class_exists(\'Parsedown\')){
            include_once(\'' . $this->data['btDirectory'] . 'libraries' . DIRECTORY_SEPARATOR . 'parsedown' . DIRECTORY_SEPARATOR . 'Parsedown.php' . '\');
        }';
        }
    }

    public function getValidateFunctionContents()
    {
        return 'if (in_array("' . $this->data['slug'] . '", $this->btFieldsRequired) && trim($args["' . $this->data['slug'] . '"]) == ""){
            $e->add(t("The %s field is required.", "' . htmlspecialchars($this->data['label']) . '"));
        }';
    }

    public function copyFiles()
    {
        $files = array();
        if ($this->data['ft_count'] <= 0) {
            $files[] = array(
                'source' => $this->ftDirectory . 'libraries' . DIRECTORY_SEPARATOR,
                'target' => $this->data['btDirectory'] . 'libraries' . DIRECTORY_SEPARATOR,
            );
        }
        return $files;
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