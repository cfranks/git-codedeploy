<?php  namespace Application\Block\HomepageMeeting;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Core;
use Loader;
use \File;
use Page;

class Controller extends BlockController
{
    public $helpers = array (
  0 => 'form',
);
    public $btFieldsRequired = array (
  0 => 'Header',
  1 => 'Image',
  2 => 'Title',
  3 => 'Date',
  4 => 'Description_1',
  5 => 'URL',
);
    protected $btExportFileColumns = array (
  0 => 'Image',
);
    protected $btTable = 'btHomepageMeeting';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btCacheBlockRecord = false;
    protected $btCacheBlockOutput = false;
    protected $btCacheBlockOutputOnPost = false;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btCacheBlockOutputLifetime = 0;
    
    public function getBlockTypeDescription()
    {
        return t("Add meeting content");
    }

    public function getBlockTypeName()
    {
        return t("Homepage Meeting");
    }

    public function getSearchableContent()
    {
        $content = array();
        $content[] = $this->Header;
        $content[] = $this->Title;
        $content[] = $this->Date;
        $content[] = $this->Description_1;
        return implode(" ", $content);
    }

    public function view()
    {
        $db = \Database::get();
        
        if ($this->Image) {
            $f = \File::getByID($this->Image);
            if (is_object($f)) {
                $this->set("Image", $f);
            }
            else {
                $this->set("Image", false);
            }
        }
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
        if (in_array("Header", $this->btFieldsRequired) && trim($args["Header"]) == "") {
            $e->add(t("The %s field is required.", "Header"));
        }
        if (in_array("Image", $this->btFieldsRequired) && (trim($args["Image"]) == "" || !is_object(\File::getByID($args["Image"])))){
            $e->add(t("The %s field is required.", "Image"));
        }
        if(in_array("Title",$this->btFieldsRequired) && trim($args["Title"]) == ""){
            $e->add(t("The %s field is required.", "Title"));
        }
        if(in_array("Date",$this->btFieldsRequired) && trim($args["Date"]) == ""){
            $e->add(t("The %s field is required.", "Date"));
        }
        if(in_array("Description_1",$this->btFieldsRequired) && trim($args["Description_1"]) == ""){
            $e->add(t("The %s field is required.", "Description"));
        }
        if (in_array("URL", $this->btFieldsRequired) && (trim($args["URL"]) == "" || $args["URL"] == "0" || (($page = Page::getByID($args["URL"])) && $page->error !== false))){
            $e->add(t("The %s field is required.", "URL"));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }

    
}