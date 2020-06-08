<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

class FieldType
{
    protected $ftHandle;
    protected $ftDirectory;

    protected $dbType = false;
    protected $canRepeat = false;
    protected $btExportFileColumn = false;
    protected $pkgVersionRequired = false;
    protected $appVersionRequired = false;
    protected $requiredSlug = true;
    protected $useBaseFields = true;

    protected $data = array();
    protected $uses = array();
    protected $helpers = array();

    public function __construct($fieldTypeDirectory, $pkgHandle, $pkgDirectory, $className)
    {
        $this->ftDirectory = $fieldTypeDirectory . DIRECTORY_SEPARATOR;
        $this->pkgHandle = $pkgHandle;
        $this->pkgDirectory = $pkgDirectory;
        $this->className = $className;
    }

    public function getFieldName()
    {
        return t(ucwords(implode(' ', explode('_', $this->ftHandle))));
    }

    public function getFieldDescription()
    {
        return '';
    }

    public function inc($file)
    {
        if (file_exists($file)) {
            ob_start();
            include($file);
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
        return;
    }

    public function view($view, $pkgHandle = 'block_designer')
    {
        return $this->inc('packages' . DIRECTORY_SEPARATOR . $pkgHandle . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'FieldType' . DIRECTORY_SEPARATOR . $this->className . DIRECTORY_SEPARATOR . $view);
    }

    public function getPkgVersionRequired()
    {
        return isset($this->pkgVersionRequired) && $this->pkgVersionRequired !== false ? $this->pkgVersionRequired : false;
    }

    public function getAppVersionRequired()
    {
        return isset($this->appVersionRequired) && $this->appVersionRequired !== false ? $this->appVersionRequired : false;
    }

    public function getDbType()
    {
        return $this->dbType;
    }

    public function getHandle()
    {
        return $this->ftHandle;
    }

    public function getRequiredSlug()
    {
        return $this->requiredSlug;
    }

    public function getBtExportFileColumn()
    {
        return $this->btExportFileColumn;
    }

    public function getHelpers()
    {
        return $this->helpers;
    }

    public function getUses()
    {
        return $this->uses;
    }

    public function getCanRepeat()
    {
        return $this->canRepeat;
    }

    public function getUseBaseFields()
    {
        return $this->useBaseFields;
    }

    public function setData($data = array())
    {
        $this->data = $data;
    }

    public function getFieldTypeJavascript()
    {
        return file_exists($this->ftDirectory . 'elements' . DIRECTORY_SEPARATOR . 'field_javascript.js') ? 'src/FieldType/' . $this->className . '/elements/field_javascript.js' : false;
    }

    public function getFieldTypeCss()
    {
        return file_exists($this->ftDirectory . 'elements' . DIRECTORY_SEPARATOR . 'field_css.css') ? 'src/FieldType/' . $this->className . '/elements/field_css.css' : false;
    }

    public function generateFormContent($type, $values = array())
    {
        switch ($type) {
            case 'label':
                return '<?php  echo $form->label(\'' . $values['slug'] . '\', t("' . htmlspecialchars($values['label']) . '")' . (isset($values['suffix']) ? $values['suffix'] : null) . '); ?>';
                break;
            case 'required':
                if (isset($values['array'])) {
                    return '<?php  echo isset(' . $values['array'] . ') && in_array(\'' . $values['slug'] . '\', ' . $values['array'] . ') ? \'<small class="required">\' . t(\'Required\') . \'</small>\' : null; ?>';
                } else {
                    return '<?php  echo \'<small class="required">\' . t(\'Required\') . \'</small>\'; ?>';
                }
                break;
            case 'file':
                return '<?php  echo $al->file($view->field(\'' . $values['slug'] . '\'), "' . $values['postName'] . '", ' . (isset($values['chooseText']) && trim($values['chooseText']) != '' ? $values['chooseText'] : 't("Choose File")') . ', ' . $values['bf'] . '); ?>';
                break;
            case 'page_selector':
                return '<?php  echo Core::make("helper/form/page_selector")->selectPage($view->field(\'' . $values['slug'] . '\'), $' . $values['slug'] . '); ?>';
                break;
            case 'textarea':
            case 'text':
                return '<?php  echo $form->' . $type . '($view->field(\'' . $values['slug'] . '\'), ' . (isset($values['value']) ? $values['value'] : '$' . $values['slug']) . ', ' . (isset($values['attributes']) && is_array($values['attributes']) && !empty($values['attributes']) ? var_export($values['attributes'], true) : 'array()') . '); ?>';
                break;
            case 'select':
                return '<?php  echo $form->' . $type . '($view->field(\'' . $values['slug'] . '\'), ' . $values['options'] . ', $' . $values['slug'] . '); ?>';
                break;
            case 'select_multiple':
                return '<?php  echo $form->selectMultiple($view->field(\'' . $values['slug'] . '\'), ' . $values['options'] . ', ' . (isset($values['defaultValues']) ? $values['defaultValues'] : false) . ', ' . (isset($values['attributes']) && is_array($values['attributes']) && !empty($values['attributes']) ? var_export($values['attributes'], true) : 'array()') . '); ?>';
                break;
        }
    }
} 