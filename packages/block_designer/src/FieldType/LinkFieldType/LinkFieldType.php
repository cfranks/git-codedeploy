<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType\LinkFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\BlockDesigner\Src\FieldType\FieldType;

class LinkFieldType extends FieldType
{
    protected $ftHandle = 'link';
    protected $dbType = 'I';
    protected $uses = array('Page');

    public function getFieldDescription()
    {
        return t("A page selector");
    }

    public function getViewContents()
    {
        return '<?php  if (!empty($' . $this->data['slug'] . ')) {
    $linkToC = Page::getByID($' . $this->data['slug'] . ');
    $linkURL = empty($linkToC) || $linkToC->error ? "" : $linkToC->getCollectionLink();
    echo \'<a href="\' . $linkURL . \'">\' . (isset($' . $this->data['slug'] . '_text) && trim($' . $this->data['slug'] . '_text) != "" ? $' . $this->data['slug'] . '_text : $linkToC->getCollectionName()) . \'</a>\';
} ?>';
    }

    public function getValidateFunctionContents()
    {
        return 'if (in_array("' . $this->data['slug'] . '", $this->btFieldsRequired) && (trim($args["' . $this->data['slug'] . '"]) == "" || $args["' . $this->data['slug'] . '"] == "0" || (($page = Page::getByID($args["' . $this->data['slug'] . '"])) && $page->error !== false))){
            $e->add(t("The %s field is required.", "' . htmlspecialchars($this->data['label']) . '"));
        }';
    }

    public function getFormContents()
    {
        $html = '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'label' => $this->data['label'])) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'array' => '$btFieldsRequired')) . '
    ' . parent::generateFormContent('page_selector', array('slug' => $this->data['slug']));
        if (!isset($this->data['hide_title']) && $this->data['hide_title'] != '1') {
            $html .= '    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'] . '_text', 'label' => $this->data['label'], 'suffix' => ' . " " . t("Text")')) . '
    ' . parent::generateFormContent('text', array('slug' => $this->data['slug'] . '_text'));
        }
        $html .= '</div>';
        return $html;
    }

    public function getFieldOptions()
    {
        return parent::view('field_options.php');
    }

    public function getDbFields()
    {
        $fields = array(
            0 => array(
                'name' => $this->data['slug'],
                'type' => $this->getDbType(),
            ),
            1 => array(
                'name' => $this->data['slug'] . '_text',
                'type' => 'C',
            ),
        );
        if (isset($this->data['hide_title']) && $this->data['hide_title'] == '1') {
            unset($fields[1]);
        }
        return $fields;
    }
}