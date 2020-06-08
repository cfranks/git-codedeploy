<?php  namespace Application\Block\HomeQuickLinks;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Core;
use Loader;
use Page;

class Controller extends BlockController
{
    public $helpers = array (
  0 => 'form',
);
    public $btFieldsRequired = array (
  0 => 'Title',
  1 => 'Description_1',
  2 => 'LinkURL',
);
    protected $btExportFileColumns = array (
);
    protected $btTable = 'btHomeQuickLinks';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btCacheBlockRecord = false;
    protected $btCacheBlockOutput = false;
    protected $btCacheBlockOutputOnPost = false;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btCacheBlockOutputLifetime = 0;
    
    public function getBlockTypeDescription()
    {
        return t("To add quick links on home page");
    }

    public function getBlockTypeName()
    {
        return t("Home Quick Links");
    }

    public function getSearchableContent()
    {
        $content = array();
        $content[] = $this->Title;
        $content[] = $this->Description_1;
        return implode(" ", $content);
    }

    public function add()
    {
        $this->set('btFieldsRequired', $this->btFieldsRequired);
    }

    public function edit()
    {
        $this->set('btFieldsRequired', $this->btFieldsRequired);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if (in_array("Title", $this->btFieldsRequired) && trim($args["Title"]) == "") {
            $e->add(t("The %s field is required.", "Title"));
        }
        if(in_array("Description_1",$this->btFieldsRequired) && trim($args["Description_1"]) == ""){
            $e->add(t("The %s field is required.", "Description"));
        }
        if (in_array("LinkURL", $this->btFieldsRequired) && (trim($args["LinkURL"]) == "" || $args["LinkURL"] == "0" || (($page = Page::getByID($args["LinkURL"])) && $page->error !== false))){
            $e->add(t("The %s field is required.", "LinkURL"));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }

    
}