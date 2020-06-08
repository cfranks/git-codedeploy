<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType\SelectFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\BlockDesigner\Src\FieldType\FieldType;

class SelectFieldType extends FieldType
{
    protected $ftHandle = 'select';
    protected $dbType = 'C';

    public function getFieldDescription()
    {
        return t("A field with multiple options (also known as dropdown), where you need to pick one");
    }

    private function _options()
    {
        $options = array();
        if (isset($this->data['select_options']) && trim($this->data['select_options']) != '') {
            $options_exploded = explode("\n", $this->data['select_options']);
            $max_key = 0;
            foreach ($options_exploded as $option_exploded) {
                list($before, $after) = explode(' :: ', $option_exploded, 2);
                if (trim($after) != '') {
                    $key = strip_tags($before);
                    $key_no = 0;
                    while (array_key_exists($key, $options)) {
                        $key_no++;
                        $key = $before . '_' . $key_no;
                    }
                    if (is_numeric($key) && $key > $max_key) {
                        $max_key = $key;
                    }
                    $options[$key] = $after;
                } else {
                    $max_key++;
                    $options[$max_key] = $before;
                }
            }
        }
        return $options;
    }

    public function validate()
    {
        $options = $this->_options($this->data);
        return !empty($options) ? true : t('No select choices were entered for row #%s.', $this->data['row_id']);
    }

    public function getViewContents()
    {
        $cases = '';
        $options = $this->_options($this->data);
        foreach ($options as $option_key => $option_value) {
            $options[$option_key] = trim($option_value);
            $cases .= '
case "' . $option_key . '":
    // ENTER MARKUP HERE FOR FIELD "' . htmlspecialchars($this->data['label']) . '" : CHOICE "' . htmlspecialchars($options[$option_key]) . '"
    break;';
        }
        return '<?php  if (trim($' . $this->data['slug'] . ') != ""){ ?>' . $this->data['prefix'] . '
<?php  switch($' . $this->data['slug'] . '){' . $cases . '
                                } ?>' . $this->data['suffix'] . '<?php  } ?>';
    }

    public function getValidateFunctionContents()
    {
        $options = $this->_options($this->data);
        $key_options = array();
        foreach ($options as $option_key => $option_value) {
            $key_options[] = '"' . $option_key . '"';
        }
        return 'if (in_array("' . $this->data['slug'] . '", $this->btFieldsRequired)){
            if (!in_array($args["' . $this->data['slug'] . '"], array(' . implode(', ', $key_options) . '))){
                $e->add(t("The %s field has an invalid value.", "' . htmlspecialchars($this->data['label']) . '"));
            }
        }';
    }

    public function getFormContents()
    {
        $options = $this->_options($this->data);
        $optionsArray = array();
        if (!$this->data['required']) {
            $options = array('' => '-- ' . t("None") . ' --') + $options;
        }
        foreach ($options as $key => $option) {
            $key = trim($key) == '' ? "''" : "'$key'";
            $optionsArray[] = '        ' . $key . " => '" . htmlspecialchars(trim($option)) . "'";
        }
        return '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'label' => $this->data['label'])) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'array' => '$btFieldsRequired')) . '
    <?php  $options = array(' . PHP_EOL . implode(',' . PHP_EOL, $optionsArray) . PHP_EOL . '    ); ?>
    ' . parent::generateFormContent('select', array('slug' => $this->data['slug'], 'options' => '$options')) . '
</div>';
    }

    public function getFieldOptions()
    {
        return parent::view('field_options.php');
    }

    public function getDbFields()
    {
        $dbFields = array(
            0 => array(
                'name' => $this->data['slug'],
                'type' => $this->getDbType(),
            )
        );
        if ($this->data['required']) {
            $dbFields[0]['default'] = '0';
        }
        return $dbFields;
    }
}