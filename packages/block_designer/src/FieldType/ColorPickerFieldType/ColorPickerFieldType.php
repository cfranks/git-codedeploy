<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType\ColorPickerFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\BlockDesigner\Src\FieldType\FieldType;

class ColorPickerFieldType extends FieldType
{
    protected $ftHandle = 'color_picker';
    protected $dbType = 'C';

    public function getFieldDescription()
    {
        return t("A color picker input field");
    }

    public function getViewContents()
    {
        return '<?php  if (isset($' . $this->data['slug'] . ') && trim($' . $this->data['slug'] . ') != ""){ ?>' . $this->data['prefix'] . '<?php  echo h($' . $this->data['slug'] . '); ?>' . $this->data['suffix'] . '<?php  } ?>';
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
            return '$this->requireAsset(\'core/colorpicker\');';
        }
        return;
    }

    public function getValidateFunctionContents()
    {
        return 'if (in_array("' . $this->data['slug'] . '",$this->btFieldsRequired) && trim($args["' . $this->data['slug'] . '"]) == ""){
            $e->add(t("The %s field is required.", "' . htmlspecialchars($this->data['label']) . '"));
        }';
    }

    public function getFormContents()
    {
        $fieldAttributes = array(
            'placeholder' => isset($this->data['placeholder']) && trim($this->data['placeholder']) != '' ? h($this->data['placeholder']) : null,
        );
        $palette = array();
        $config = array(
            'color'                  => false,
            'appendTo'               => "body",
            'containerClassName'     => "",
            'replacerClassName'      => "",

            'flat'                   => isset($this->data['flat']) && $this->data['flat'] == '1' ? true : false,
            'showInput'              => isset($this->data['show_input']) && $this->data['show_input'] == '1' ? true : false,
            'allowEmpty'             => isset($this->data['allow_empty']) && $this->data['allow_empty'] == '1' ? true : false,
            'showButtons'            => isset($this->data['show_buttons']) && $this->data['show_buttons'] == '1' ? true : false,
            'clickoutFiresChange'    => isset($this->data['clickout_fires_change']) && $this->data['clickout_fires_change'] == '1' ? true : false,
            'showInitial'            => isset($this->data['show_initial']) && $this->data['show_initial'] == '1' ? true : false,
            'showPalette'            => isset($this->data['show_palette']) && $this->data['show_palette'] == '1' ? true : false,
            'showPaletteOnly'        => isset($this->data['show_palette_only']) && $this->data['show_palette_only'] == '1' ? true : false,
            'hideAfterPaletteSelect' => isset($this->data['hide_after_palette_select']) && $this->data['hide_after_palette_select'] == '1' ? true : false,
            'togglePaletteOnly'      => isset($this->data['toggle_palette_only']) && $this->data['toggle_palette_only'] == '1' ? true : false,
            'showSelectionPalette'   => isset($this->data['show_selection_palette']) && $this->data['show_selection_palette'] == '1' ? true : false,
            'localStorageKey'        => isset($this->data['local_storage']) && $this->data['local_storage'] == '1' ? $this->data['block_handle'] . '.' . $this->data['slug'] : false,
            'preferredFormat'        => isset($this->data['preferred_format']) && in_array($this->data['preferred_format'], array('hex', 'hex3', 'hsl', 'rgb', 'name')) ? $this->data['preferred_format'] : false,
            'showAlpha'              => isset($this->data['show_alpha']) && $this->data['show_alpha'] == '1' ? true : false,
            'disabled'               => isset($this->data['disabled']) && $this->data['disabled'] == '1' ? true : false,

            'maxSelectionSize'       => isset($this->data['max_selection_size']) && is_numeric($this->data['max_selection_size']) ? $this->data['max_selection_size'] : 7,
            'cancelText'             => isset($this->data['cancel_text']) && trim($this->data['cancel_text']) != '' ? $this->data['cancel_text'] : "cancel",
            'chooseText'             => isset($this->data['choose_text']) && trim($this->data['choose_text']) != '' ? $this->data['choose_text'] : "choose",
            'togglePaletteMoreText'  => isset($this->data['toggle_palette_more_text']) && trim($this->data['toggle_palette_more_text']) != '' ? $this->data['toggle_palette_more_text'] : "more",
            'togglePaletteLessText'  => isset($this->data['toggle_palette_less_text']) && trim($this->data['toggle_palette_less_text']) != '' ? $this->data['toggle_palette_less_text'] : "less",
            'clearText'              => isset($this->data['clear_text']) && trim($this->data['clear_text']) != '' ? $this->data['clear_text'] : "Clear Color Selection",
            'noColorSelectedText'    => isset($this->data['no_color_selected_text']) && trim($this->data['no_color_selected_text']) != '' ? $this->data['no_color_selected_text'] : "No Color Selected",
            'theme'                  => isset($this->data['theme']) && trim($this->data['theme']) != '' ? $this->data['theme'] : "sp-light",
            'selectionPalette'       => array(),
            'offset'                 => null,

            'blockDesignerFunctions' => null,
        );
        if (isset($this->data['palette']) && is_array($this->data['palette']) && !empty($this->data['palette'])) {
            foreach ($this->data['palette'] as $row => $values) {
                if (is_array($values)) {
                    $values = array_filter($values);
                    $values = array_unique($values);
                    if (!empty($values)) {
                        $values = array_values($values);
                        $palette[] = $values;
                    }
                }
            }
        }
        $palette = empty($palette) ? array(array("#ffffff", "#000000", "#ff0000", "#ff8000", "#ffff00", "#008000", "#0000ff", "#4b0082", "#9400d3")) : $palette;
        $config['palette'] = $palette;
        $search = array('"blockDesignerFunctions":null');
        $replace = array(" hide: function(color) {
                   $('.sp-container').hide();
                },
                beforeShow: function(tinycolor) {
                    $('.sp-container').show();
                }");
        $jsonArray = str_replace($search, $replace, json_encode($config));
        $assets = array();
        $assets[] = '<script type="text/javascript">
    $(function () {
        $("#' . $this->data['slug'] . '").spectrum(' . $jsonArray . ');
    });
</script>';
        $html = implode(PHP_EOL, $assets);
        $html .= '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'label' => $this->data['label'])) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'array' => '$btFieldsRequired')) . '
    ' . parent::generateFormContent('text', array('slug' => $this->data['slug'], 'attributes' => $fieldAttributes)) . '
</div>';
        return $html;
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
                'size' => 25,
            )
        );
    }
}