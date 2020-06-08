<?php namespace Application\Block\ContentFeature;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Core;
use File;
use Page;

class Controller extends BlockController
{
    public $btFieldsRequired = [];
    protected $btExportFileColumns = ['featureimg'];
    protected $btExportPageColumns = ['featurelink'];
    protected $btTable = 'btContentFeature';
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
        return t("Use this block to add an image, header, blurb, and link to page layouts.");
    }

    public function getBlockTypeName()
    {
        return t("Content Feature");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->featurehdr;
        $content[] = $this->featureblurb;
        return implode(" ", $content);
    }

    public function view()
    {
        
        if ($this->featureimg && ($f = File::getByID($this->featureimg)) && is_object($f)) {
            $this->set("featureimg", $f);
        } else {
            $this->set("featureimg", false);
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
        if (in_array("featureimg", $this->btFieldsRequired) && (trim($args["featureimg"]) == "" || !is_object(File::getByID($args["featureimg"])))) {
            $e->add(t("The %s field is required.", t("Image (600x300)")));
        }
        if (in_array("featurehdr", $this->btFieldsRequired) && (trim($args["featurehdr"]) == "")) {
            $e->add(t("The %s field is required.", t("Header")));
        }
        if (in_array("featureblurb", $this->btFieldsRequired) && trim($args["featureblurb"]) == "") {
            $e->add(t("The %s field is required.", t("Blurb")));
        }
        if (in_array("featurelink", $this->btFieldsRequired) && (trim($args["featurelink"]) == "" || $args["featurelink"] == "0" || (($page = Page::getByID($args["featurelink"])) && $page->error !== false))) {
            $e->add(t("The %s field is required.", t("Link")));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }
}