<?php namespace Application\Block\FeatureModal;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Core;
use File;
use Page;

class Controller extends BlockController
{
    public $btFieldsRequired = [];
    protected $btExportFileColumns = ['triggerimg', 'modalimg'];
    protected $btTable = 'btFeatureModal';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btIgnorePageThemeGridFrameworkContainer = false;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $pkg = false;
    
    public function getBlockTypeDescription()
    {
        return t("Use this block to add modal popovers with content to your layouts.");
    }

    public function getBlockTypeName()
    {
        return t("Feature Modal");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->modalid;
        $content[] = $this->triggercontent;
        $content[] = $this->modaltitle;
        $content[] = $this->modalcontent;
        $content[] = $this->modalcolleft;
        $content[] = $this->modalcolright;
        return implode(" ", $content);
    }

    public function view()
    {
        
        if ($this->triggerimg && ($f = File::getByID($this->triggerimg)) && is_object($f)) {
            $this->set("triggerimg", $f);
        } else {
            $this->set("triggerimg", false);
        }
        $this->set('triggercontent', LinkAbstractor::translateFrom($this->triggercontent));
        
        if ($this->modalimg && ($f = File::getByID($this->modalimg)) && is_object($f)) {
            $this->set("modalimg", $f);
        } else {
            $this->set("modalimg", false);
        }
        $this->set('modalcontent', LinkAbstractor::translateFrom($this->modalcontent));
        $this->set('modalcolleft', LinkAbstractor::translateFrom($this->modalcolleft));
        $this->set('modalcolright', LinkAbstractor::translateFrom($this->modalcolright));
    }

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
        
        $this->set('triggercontent', LinkAbstractor::translateFromEditMode($this->triggercontent));
        
        $this->set('modalcontent', LinkAbstractor::translateFromEditMode($this->modalcontent));
        
        $this->set('modalcolleft', LinkAbstractor::translateFromEditMode($this->modalcolleft));
        
        $this->set('modalcolright', LinkAbstractor::translateFromEditMode($this->modalcolright));
    }

    protected function addEdit()
    {
        $this->requireAsset('core/file-manager');
        $this->requireAsset('redactor');
        $this->set('btFieldsRequired', $this->btFieldsRequired);
        $this->set('identifier_getString', Core::make('helper/validation/identifier')->getString(18));
    }

    public function save($args)
    {
        $args['triggercontent'] = LinkAbstractor::translateTo($args['triggercontent']);
        $args['modalcontent'] = LinkAbstractor::translateTo($args['modalcontent']);
        $args['modalcolleft'] = LinkAbstractor::translateTo($args['modalcolleft']);
        $args['modalcolright'] = LinkAbstractor::translateTo($args['modalcolright']);
        parent::save($args);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if (in_array("modalid", $this->btFieldsRequired) && (trim($args["modalid"]) == "")) {
            $e->add(t("The %s field is required.", t("Modal ID")));
        }
        if (in_array("triggerimg", $this->btFieldsRequired) && (trim($args["triggerimg"]) == "" || !is_object(File::getByID($args["triggerimg"])))) {
            $e->add(t("The %s field is required.", t("Trigger Image")));
        }
        if (in_array("triggercontent", $this->btFieldsRequired) && (trim($args["triggercontent"]) == "")) {
            $e->add(t("The %s field is required.", t("Trigger Content")));
        }
        if (in_array("modaltitle", $this->btFieldsRequired) && (trim($args["modaltitle"]) == "")) {
            $e->add(t("The %s field is required.", t("Modal Title")));
        }
        if (in_array("modalimg", $this->btFieldsRequired) && (trim($args["modalimg"]) == "" || !is_object(File::getByID($args["modalimg"])))) {
            $e->add(t("The %s field is required.", t("Modal Image")));
        }
        if (in_array("modalcontent", $this->btFieldsRequired) && (trim($args["modalcontent"]) == "")) {
            $e->add(t("The %s field is required.", t("Modal Content")));
        }
        if (in_array("modalcolleft", $this->btFieldsRequired) && (trim($args["modalcolleft"]) == "")) {
            $e->add(t("The %s field is required.", t("Modal Column left")));
        }
        if (in_array("modalcolright", $this->btFieldsRequired) && (trim($args["modalcolright"]) == "")) {
            $e->add(t("The %s field is required.", t("Modal Column Right")));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }
}