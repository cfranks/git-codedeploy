<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType\NumberFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\BlockDesigner\Src\FieldType\FieldType;

class NumberFieldType extends FieldType
{
    protected $ftHandle = 'number';
    public $dbType = 'N';

    public function getFieldDescription()
    {
        return t("A number field");
    }

    public function getViewContents()
    {
        $variable = '$' . $this->data['slug'];
        if (isset($this->data['number_format']) && $this->data['number_format'] == '1') {
            $thousands_sep = isset($this->data['number_format_thousand_sep']) && trim($this->data['number_format_thousand_sep']) != '' ? $this->data['number_format_thousand_sep'] : ',';
            $decimal_point = isset($this->data['number_format_decimal_point']) && trim($this->data['number_format_decimal_point']) != '' ? $this->data['number_format_decimal_point'] : '.';
            $decimals = (int)$this->data['number_format_decimals'] >= 0 ? (int)$this->data['number_format_decimals'] : 0;
            $variable = 'number_format($' . $this->data['slug'] . ', ' . $decimals . ', ' . var_export($decimal_point, true) . ', ' . var_export($thousands_sep, true) . ')';
        }
        return '<?php  if (isset($' . $this->data['slug'] . ') && trim($' . $this->data['slug'] . ') != ""){ ?>' . $this->data['prefix'] . '<?php  echo ' . $variable . '; ?>' . $this->data['suffix'] . '<?php  } ?>';
    }

    public function getValidateFunctionContents()
    {
        $return = '';
        $statements = array();
        if (isset($this->data['disallow_float']) && $this->data['disallow_float'] == '1') {
            $statements[] = array(
                'if'   => '!ctype_digit($args["' . $this->data['slug'] . '"])',
                'then' => '$e->add(t("The %s field has to be an integer (float number disallowed).", "' . htmlspecialchars($this->data['label']) . '"))'
            );
        }

        if (isset($this->data['min_number']) && trim($this->data['min_number']) != '') {
            $statements[] = array(
                'if'   => '$args["' . $this->data['slug'] . '"] < ' . $this->data['min_number'],
                'then' => '$e->add(t("The %s field needs a minimum of %s", "' . htmlspecialchars($this->data['label']) . '", ' . $this->data['min_number'] . '))'
            );
        }

        if (isset($this->data['max_number']) && trim($this->data['max_number']) != '') {
            $statements[] = array(
                'if'   => '$args["' . $this->data['slug'] . '"] > ' . $this->data['max_number'],
                'then' => '$e->add(t("The %s field needs a maximum of %s", "' . htmlspecialchars($this->data['label']) . '", ' . $this->data['max_number'] . '))'
            );
        }

        foreach ($statements as $k => $statement) {
            $type = $k == 0 ? 'if' : ' elseif';
            $return .= PHP_EOL . $type . ' (' . $statement['if'] . ') {
                ' . $statement['then'] . ';
            }';
        }
        return 'if (trim($args[\'' . $this->data['slug'] . '\']) != "") {
            $args[\'' . $this->data['slug'] . '\'] = str_replace(\',\', \'.\', $args[\'' . $this->data['slug'] . '\']);
            ' . $return . '
        } elseif (in_array("' . $this->data['slug'] . '", $this->btFieldsRequired)) {
            $e->add(t("The %s field is required.", "' . htmlspecialchars($this->data['label']) . '"));
        }';
    }

    public function getSaveFunctionContents()
    {
        return '$args[\'' . $this->data['slug'] . '\'] = str_replace(\',\', \'.\', $args[\'' . $this->data['slug'] . '\']);';
    }

    public function getFormContents()
    {
        return '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'label' => $this->data['label'])) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'array' => '$btFieldsRequired')) . '
    ' . parent::generateFormContent('text', array('slug' => $this->data['slug'])) . '
</div>';
    }

    public function getFieldOptions()
    {
        return parent::view('field_options.php');
    }

    public function getDbFields()
    {
        $length = isset($this->data['database_length']) && (int)$this->data['database_length'] >= -1 && (int)$this->data['database_length'] <= 10485760 ? (int)$this->data['database_length'] : 10;
        $decimals = isset($this->data['database_decimals']) && (int)$this->data['database_decimals'] >= -53 && (int)$this->data['database_decimals'] <= 53 ? (int)$this->data['database_decimals'] : 2;
        return array(
            array(
                'name' => $this->data['slug'],
                'type' => $this->getDbType(),
                'size' => ($length + $decimals) . '.' . $decimals
            )
        );
    }
}