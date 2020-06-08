<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType\StaticFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\BlockDesigner\Src\FieldType\FieldType;

class StaticFieldType extends FieldType
{
    protected $ftHandle = 'static';
    protected $requiredSlug = false;
    protected $useBaseFields = false;

    public function getFieldName()
    {
        return t("Static HTML");
    }

    public function getFieldDescription()
    {
        return t("Enter your static HTML here (or any other programming languages you prefer, like CSS/JS/PHP)");
    }

    public function getFieldOptions()
    {
        return parent::view('field_options.php');
    }

    public function getViewContents()
    {
        if (isset($this->data['static_html']) && trim($this->data['static_html']) != '') {
            return $this->data['static_html'];
        }
    }
}