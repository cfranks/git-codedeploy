<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType\DateTimeFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\BlockDesigner\Src\FieldType\FieldType;

class DateTimeFieldType extends FieldType
{
    protected $ftHandle = 'date_time';
    protected $dbType = 'I';

    public function getFieldDescription()
    {
        return t("A date + time, date or time field");
    }

    public function getViewContents()
    {
        return '<?php  if (isset($' . $this->data['slug'] . ') && $' . $this->data['slug'] . ' > 0){ ?>' . $this->data['prefix'] . '<?php  echo strftime("' . $this->data['date_format'] . '",$' . $this->data['slug'] . '); ?>' . $this->data['suffix'] . '<?php  } ?>';
    }

    public function getValidateFunctionContents()
    {
        return 'if(in_array("' . $this->data['slug'] . '",$this->btFieldsRequired) && trim($args["' . $this->data['slug'] . '"]) == ""){
            $e->add(t("The %s field is required.", "' . htmlspecialchars($this->data['label']) . '"));
        }
        elseif(trim($args["' . $this->data['slug'] . '"]) != "" && strtotime($args["' . $this->data['slug'] . '"]) <= 0){
            $e->add(t("The %s field is not a valid date.", "' . htmlspecialchars($this->data['label']) . '"));
        }';
    }

    public function validate()
    {
        $return = true;
        if (trim($this->data['date_format']) == '') {
            $return = t('The date format field for "%s" (row #%s) is required', $this->data['label'], $this->data['row_id']);
        }
        return $return;
    }

    public function getSaveFunctionContents()
    {
        return '$args[\'' . $this->data['slug'] . '\'] = strtotime(substr($args[\'' . $this->data['slug'] . '\'], 0, 21));';
    }

    public function getAddFunctionContents()
    {
        return $this->editAddFunctionContents($this->data);
    }

    public function getEditFunctionContents()
    {
        return $this->editAddFunctionContents($this->data);
    }

    private function editAddFunctionContents()
    {
        if ($this->data['ft_count'] <= 0) {
            return '$this->requireAsset(\'css\', \'bootstrap_fonts\');
        $this->requireAsset(\'css\', \'datetimepicker\');
        $this->requireAsset(\'javascript\', \'moment\');
        $this->requireAsset(\'javascript\', \'bootstrap\');
        $this->requireAsset(\'javascript\', \'datetimepicker\');';
        }
        return;
    }

    public function getOnStartFunctionContents()
    {
        if ($this->data['ft_count'] <= 0) {
            return '$al->register(\'css\', \'datetimepicker\', \'blocks/' . $this->data['block_handle'] . '/css_form/bootstrap-datetimepicker.min.css\');
        $al->register(\'css\', \'bootstrap_fonts\', \'blocks/' . $this->data['block_handle'] . '/css_form/bootstrap.fonts.css\');
        $al->register(\'javascript\', \'moment\', \'blocks/' . $this->data['block_handle'] . '/js_form/moment.js\');
        $al->register(\'javascript\', \'bootstrap\', \'blocks/' . $this->data['block_handle'] . '/js_form/bootstrap.min.js\');
        $al->register(\'javascript\', \'datetimepicker\', \'blocks/' . $this->data['block_handle'] . '/js_form/bootstrap-datetimepicker.min.js\');';
        }
    }

    public function getFormContents()
    {
        $fieldAttributes = array('autocomplete' => 'off');
        $config = array(
            'pickDate'           => isset($this->data['pick_date']) && $this->data['pick_date'] == '0' ? false : true,
            'pickTime'           => isset($this->data['pick_time']) && $this->data['pick_time'] == '0' ? false : true,
            'useMinutes'         => isset($this->data['use_minutes']) && $this->data['use_minutes'] == '0' ? false : true,
            'useSeconds'         => isset($this->data['use_seconds']) && $this->data['use_seconds'] == '0' ? false : true,
            'useCurrent'         => isset($this->data['use_current']) && $this->data['use_current'] == '0' ? false : true,
            'showToday'          => isset($this->data['show_today']) && $this->data['show_today'] == '0' ? false : true,
            'useStrict'          => isset($this->data['use_strict']) && $this->data['use_strict'] == '1' ? true : false,
            'sideBySide'         => isset($this->data['side_by_side']) && $this->data['side_by_side'] == '1' ? true : false,

            'minuteStepping'     => isset($this->data['minute_stepping']) && $this->data['minute_stepping'] <= 30 && $this->data['minute_stepping'] > 1 ? (int)$this->data['minute_stepping'] : 1,
            'minDate'            => isset($this->data['min_date']) ? $this->data['min_date'] : '1/1/1900',
            'maxDate'            => isset($this->data['max_date']) ? $this->data['max_date'] : null,
            'defaultDate'        => isset($this->data['default_date']) ? $this->data['default_date'] : "",

            // Not (yet) implemented configurations
            'icons '             => array(
                'time' => 'glyphicon glyphicon-time',
                'date' => 'glyphicon glyphicon-calendar',
                'up'   => 'glyphicon glyphicon-chevron-up',
                'down' => 'glyphicon glyphicon-chevron-down',
            ),
            'language'           => 'en',
            'disabledDates'      => array(),
            'enabledDates'       => array(),
            'daysOfWeekDisabled' => array(),
        );

        if ($config['pickDate'] !== true && $config['pickTime'] !== true) {
            $config['pickDate'] = true;
        }

        if (trim($config['maxDate']) == '') {
            unset($config['maxDate']);
        }
        if ($config['pickTime'] !== true) {
            $config['useMinutes'] = false;
            $config['useSeconds'] = false;
            $config['sideBySide'] = false;
        }

        $assets = array();
        $assets[] = '<script type="text/javascript">
    $(function () {
        $("#' . $this->data['slug'] . '").datetimepicker(' . str_replace(array(',', '{'), array(',' . PHP_EOL, '{' . PHP_EOL), json_encode($config)) . ');
    });
</script>';

        $html = implode(PHP_EOL, $assets);
        $dateFormat = $config['pickTime'] ? 'm/d/Y g:i:s A' : 'm/d/Y';
        if ($config['pickTime'] && !$config['pickDate']) {
            $dateFormat = 'g:i:s A';
        }
        $html .= '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'label' => $this->data['label'])) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'array' => '$btFieldsRequired')) . '
    ' . parent::generateFormContent('text', array('slug' => $this->data['slug'], 'value' => '$' . $this->data['slug'] . ' > 0 ? date("' . $dateFormat . '", $' . $this->data['slug'] . ') : null', 'attributes' => $fieldAttributes)) . '
</div>';
        return $html;
    }

    public function copyFiles()
    {
        $files = array();
        if ($this->data['ft_count'] <= 0) {
            $files[] = array(
                'source' => $this->pkgDirectory . 'fonts',
                'target' => $this->data['btDirectory'] . 'fonts',
            );

            $files[] = array(
                'source' => $this->ftDirectory . 'css' . DIRECTORY_SEPARATOR,
                'target' => $this->data['btDirectory'] . 'css_form' . DIRECTORY_SEPARATOR,
            );

            $files[] = array(
                'source' => $this->pkgDirectory . 'css' . DIRECTORY_SEPARATOR . 'bootstrap.fonts.css',
                'target' => $this->data['btDirectory'] . 'css_form' . DIRECTORY_SEPARATOR . 'bootstrap.fonts.css',
            );

            $files[] = array(
                'source' => $this->ftDirectory . 'js' . DIRECTORY_SEPARATOR,
                'target' => $this->data['btDirectory'] . 'js_form' . DIRECTORY_SEPARATOR,
            );
        }
        return $files;
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
                'type' => $this->dbType,
            )
        );
    }
}