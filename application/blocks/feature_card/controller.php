<?php namespace Application\Block\FeatureCard;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Core;
use File;
use Page;

class Controller extends BlockController
{
    public $btFieldsRequired = [];
    protected $btExportFileColumns = ['cardimg'];
    protected $btExportPageColumns = ['cardlink'];
    protected $btTable = 'btFeatureCard';
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
        return t("Use this block to add a card with link to page layouts.");
    }

    public function getBlockTypeName()
    {
        return t("Feature Card");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->cardtitle;
        $content[] = $this->cardblurb;
        $content[] = $this->cardmoreinfo;
        return implode(" ", $content);
    }

    public function view()
    {
        
        if ($this->cardimg && ($f = File::getByID($this->cardimg)) && is_object($f)) {
            $this->set("cardimg", $f);
        } else {
            $this->set("cardimg", false);
        }
    }

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
    }

    protected function addEdit()
    {
        $this->requireAsset('core/file-manager');
        $this->set('btFieldsRequired', $this->btFieldsRequired);
        $this->set('identifier_getString', Core::make('helper/validation/identifier')->getString(18));
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if (in_array("cardimg", $this->btFieldsRequired) && (trim($args["cardimg"]) == "" || !is_object(File::getByID($args["cardimg"])))) {
            $e->add(t("The %s field is required.", t("Image")));
        }
        if (in_array("cardtitle", $this->btFieldsRequired) && (trim($args["cardtitle"]) == "")) {
            $e->add(t("The %s field is required.", t("Title")));
        }
        if (in_array("cardblurb", $this->btFieldsRequired) && trim($args["cardblurb"]) == "") {
            $e->add(t("The %s field is required.", t("Blurb")));
        }
        if (in_array("cardmoreinfo", $this->btFieldsRequired) && trim($args["cardmoreinfo"]) == "") {
            $e->add(t("The %s field is required.", t("More Info")));
        }
        if (in_array("cardlink", $this->btFieldsRequired) && (trim($args["cardlink"]) == "" || $args["cardlink"] == "0" || (($page = Page::getByID($args["cardlink"])) && $page->error !== false))) {
            $e->add(t("The %s field is required.", t("Link")));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }
}