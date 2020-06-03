<?php namespace Application\Block\HeroHomepage;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Core;
use File;
use Page;

class Controller extends BlockController
{
    public $btFieldsRequired = ['heroimg', 'heroheader', 'blurb'];
    protected $btExportFileColumns = ['heroimg'];
    protected $btExportPageColumns = ['btnone', 'btntwo'];
    protected $btTable = 'btHeroHomepage';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btIgnorePageThemeGridFrameworkContainer = false;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $pkg = false;
    
    public function getBlockTypeName()
    {
        return t("Homepage Hero");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->heroheader;
        $content[] = $this->blurb;
        $content[] = $this->imgcaption;
        return implode(" ", $content);
    }

    public function view()
    {
        
        if ($this->heroimg && ($f = File::getByID($this->heroimg)) && is_object($f)) {
            $this->set("heroimg", $f);
        } else {
            $this->set("heroimg", false);
        }
        $this->set('imgcaption', LinkAbstractor::translateFrom($this->imgcaption));
    }

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
        
        $this->set('imgcaption', LinkAbstractor::translateFromEditMode($this->imgcaption));
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
        $args['imgcaption'] = LinkAbstractor::translateTo($args['imgcaption']);
        parent::save($args);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if (in_array("heroimg", $this->btFieldsRequired) && (trim($args["heroimg"]) == "" || !is_object(File::getByID($args["heroimg"])))) {
            $e->add(t("The %s field is required.", t("Hero Image")));
        }
        if (in_array("heroheader", $this->btFieldsRequired) && (trim($args["heroheader"]) == "")) {
            $e->add(t("The %s field is required.", t("Header")));
        }
        if (in_array("blurb", $this->btFieldsRequired) && trim($args["blurb"]) == "") {
            $e->add(t("The %s field is required.", t("Blurb")));
        }
        if (in_array("btnone", $this->btFieldsRequired) && (trim($args["btnone"]) == "" || $args["btnone"] == "0" || (($page = Page::getByID($args["btnone"])) && $page->error !== false))) {
            $e->add(t("The %s field is required.", t("Button One")));
        }
        if (in_array("btntwo", $this->btFieldsRequired) && (trim($args["btntwo"]) == "" || $args["btntwo"] == "0" || (($page = Page::getByID($args["btntwo"])) && $page->error !== false))) {
            $e->add(t("The %s field is required.", t("Button Two")));
        }
        if (in_array("imgcaption", $this->btFieldsRequired) && (trim($args["imgcaption"]) == "")) {
            $e->add(t("The %s field is required.", t("Image Caption")));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }
}