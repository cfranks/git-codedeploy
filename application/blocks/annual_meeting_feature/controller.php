<?php namespace Application\Block\AnnualMeetingFeature;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Core;
use File;
use Page;

class Controller extends BlockController
{
    public $btFieldsRequired = ['cardcontent'];
    protected $btExportFileColumns = ['cardbkgimg'];
    protected $btTable = 'btAnnualMeetingFeature';
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
        return t("Use this block to add a card-like display that features the annual meeting and links to a detail page.");
    }

    public function getBlockTypeName()
    {
        return t("Annual Meeting Feature");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->cardcontent;
        return implode(" ", $content);
    }

    public function view()
    {
        
        if ($this->cardbkgimg && ($f = File::getByID($this->cardbkgimg)) && is_object($f)) {
            $this->set("cardbkgimg", $f);
        } else {
            $this->set("cardbkgimg", false);
        }
        $this->set('cardcontent', LinkAbstractor::translateFrom($this->cardcontent));
    }

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
        
        $this->set('cardcontent', LinkAbstractor::translateFromEditMode($this->cardcontent));
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
        $args['cardcontent'] = LinkAbstractor::translateTo($args['cardcontent']);
        parent::save($args);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if (in_array("cardbkgimg", $this->btFieldsRequired) && (trim($args["cardbkgimg"]) == "" || !is_object(File::getByID($args["cardbkgimg"])))) {
            $e->add(t("The %s field is required.", t("Background Image (600x800)")));
        }
        if (in_array("cardcontent", $this->btFieldsRequired) && (trim($args["cardcontent"]) == "")) {
            $e->add(t("The %s field is required.", t("Content")));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }
}