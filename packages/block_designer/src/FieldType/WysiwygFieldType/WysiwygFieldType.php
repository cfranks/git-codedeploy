<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType\WysiwygFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\BlockDesigner\Src\FieldType\FieldType;

class WysiwygFieldType extends FieldType
{
    protected $ftHandle = 'wysiwyg';
    protected $dbType = 'X2';
    protected $uses = array('\File', 'Page', 'Loader', 'URL', '\Concrete\Core\Editor\Snippet', 'Sunra\PhpSimple\HtmlDomParser', '\Concrete\Core\Editor\LinkAbstractor');

    public function getFieldName()
    {
        return t("WYSIWYG");
    }

    public function getFieldDescription()
    {
        return t("A 'What-You-See-Is-What-You-Get' text area");
    }

    public function getSearchableContent()
    {
        return '$content[] = $this->' . $this->data['slug'] . ';';
    }

    public function getViewFunctionContents()
    {
        return '$this->set(\'' . $this->data['slug'] . '\', LinkAbstractor::translateFrom($this->' . $this->data['slug'] . '));';
    }

    public function getSaveFunctionContents()
    {
        return '$args[\'' . $this->data['slug'] . '\'] = LinkAbstractor::translateTo($args[\'wysiwyg-ft-' . $this->data['slug'] . '\']);';
    }

    public function getValidateFunctionContents()
    {
        return 'if (in_array("' . $this->data['slug'] . '", $this->btFieldsRequired) && trim($args["wysiwyg-ft-' . $this->data['slug'] . '"]) == "") {
            $e->add(t("The %s field is required.", "' . htmlspecialchars($this->data['label']) . '"));
        }';
    }

    public function getAddFunctionContents()
    {
        if ($this->data['ft_count'] > 0) {
            return;
        }
        return '$this->requireAsset(\'redactor\');
        $this->requireAsset(\'core/file-manager\');';
    }

    public function getEditFunctionContents()
    {
        if ($this->data['ft_count'] > 0) {
            return;
        }
        return '$this->requireAsset(\'redactor\');
        $this->requireAsset(\'core/file-manager\');
        $this->set(\'' . $this->data['slug'] . '\', LinkAbstractor::translateFromEditMode($this->' . $this->data['slug'] . '));';
    }

    public function getViewContents()
    {
        return '<?php  if (isset($' . $this->data['slug'] . ') && trim($' . $this->data['slug'] . ') != "") { ?>' . $this->data['prefix'] . '<?php  echo $' . $this->data['slug'] . '; ?>' . $this->data['suffix'] . '<?php  } ?>';
    }

    public function getFormContents()
    {
        $html = '';
        if ($this->data['ft_count'] == 0) {
            $html .= '<?php 
$fp = FilePermissions::getGlobal();
$tp = new TaskPermission();
?>' . PHP_EOL . PHP_EOL;
        }
        $html .= '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'label' => $this->data['label'])) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'array' => '$btFieldsRequired')) . '

    <div id="wysiwyg-ft-' . $this->data['slug'] . '"><?php  echo $' . $this->data['slug'] . '; ?></div>

    <script type="text/javascript">
        var CCM_EDITOR_SECURITY_TOKEN = "<?php  echo Loader::helper(\'validation/token\')->generate(\'editor\')?>";
        $(function () {
            $("#wysiwyg-ft-' . $this->data['slug'] . '").redactor({
                minHeight: "300",
                "concrete5": {
                    filemanager: <?php  echo $fp->canAccessFileManager()?>,
                    sitemap: <?php  echo $tp->canAccessSitemap()?>,
                    lightbox: true
                },
                "plugins": [
                    "fontcolor", "concrete5"
                ]
            });
            $("#wysiwyg-ft-' . $this->data['slug'] . '").prev().css({opacity: "1"});
        });
    </script>
</div>';
        return $html;
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