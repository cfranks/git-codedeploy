<?php  namespace Application\Block\HomepageSlider;

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
  0 => 'sliderImage',
  1 => 'TitleText',
  2 => 'HeaderText',
  3 => 'description_1',
  4 => 'LinkTo',
);
    protected $btExportFileColumns = array (
  0 => 'sliderImage',
);
    protected $btTable = 'btHomepageSlider';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btCacheBlockRecord = false;
    protected $btCacheBlockOutput = false;
    protected $btCacheBlockOutputOnPost = false;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btCacheBlockOutputLifetime = 0;
    
    public function getBlockTypeDescription()
    {
        return t("block for adding homepage slider");
    }

    public function getBlockTypeName()
    {
        return t("Homepage Slider");
    }

    public function getSearchableContent()
    {
        $content = array();
        $content[] = $this->TitleText;
        $content[] = $this->HeaderText;
        $content[] = $this->description_1;
        return implode(" ", $content);
    }

    public function view()
    {
        $db = \Database::get();
        
        if ($this->sliderImage) {
            $f = \File::getByID($this->sliderImage);
            if (is_object($f)) {
                $this->set("sliderImage", $f);
            }
            else {
                $this->set("sliderImage", false);
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
        if (in_array("sliderImage", $this->btFieldsRequired) && (trim($args["sliderImage"]) == "" || !is_object(\File::getByID($args["sliderImage"])))){
            $e->add(t("The %s field is required.", "Slider Image"));
        }
        if (in_array("TitleText", $this->btFieldsRequired) && trim($args["TitleText"]) == "") {
            $e->add(t("The %s field is required.", "H3 Title Text"));
        }
        if (in_array("HeaderText", $this->btFieldsRequired) && trim($args["HeaderText"]) == "") {
            $e->add(t("The %s field is required.", "H2 Header Text"));
        }
        if(in_array("description_1",$this->btFieldsRequired) && trim($args["description_1"]) == ""){
            $e->add(t("The %s field is required.", "Description"));
        }
        if (in_array("LinkTo", $this->btFieldsRequired) && (trim($args["LinkTo"]) == "" || $args["LinkTo"] == "0" || (($page = Page::getByID($args["LinkTo"])) && $page->error !== false))){
            $e->add(t("The %s field is required.", "LinkTo"));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }

    
}