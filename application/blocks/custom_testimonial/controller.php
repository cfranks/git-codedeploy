<?php namespace Application\Block\CustomTestimonial;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Core;
use File;
use Page;

class Controller extends BlockController
{
    public $btFieldsRequired = ['sidebarimage', 'shorttestimonial', 'fulltestimonial'];
    protected $btExportFileColumns = ['sidebarimage', 'fullimage'];
    protected $btTable = 'btCustomTestimonial';
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
        return t("Custom Testimonial Block");
    }

    public function getBlockTypeName()
    {
        return t("Custom Testimonial Block");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->shorttestimonial;
        $content[] = $this->fulltestimonial;
        return implode(" ", $content);
    }

    public function view()
    {
        
        if ($this->sidebarimage && ($f = File::getByID($this->sidebarimage)) && is_object($f)) {
            $this->set("sidebarimage", $f);
        } else {
            $this->set("sidebarimage", false);
        }
        
        if ($this->fullimage && ($f = File::getByID($this->fullimage)) && is_object($f)) {
            $fullimage = $f;
        } else {
            $fullimage = false;
        }
        $fulltestimonial = LinkAbstractor::translateFrom($this->fulltestimonial);
        $this->addFooterItem('<div id="testimonial-'.$this->bID.'" class="modal fade testimonial-modal" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              ' .($fullimage ?  '<img src="' . $fullimage->getURL() . '" alt="' . $fullimage->getTitle() . '" class="img-responsive"/>' : '') . '
	 		  <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                
                ' .(!empty($fulltestimonial) ?  $fulltestimonial  : '') . '
              </div>
            </div>
          </div>
        </div>');
    }

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
        
        $this->set('fulltestimonial', LinkAbstractor::translateFromEditMode($this->fulltestimonial));
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
        $args['fulltestimonial'] = LinkAbstractor::translateTo($args['fulltestimonial']);
        parent::save($args);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if (in_array("sidebarimage", $this->btFieldsRequired) && (trim($args["sidebarimage"]) == "" || !is_object(File::getByID($args["sidebarimage"])))) {
            $e->add(t("The %s field is required.", t("Sidebar Image")));
        }
        if (in_array("shorttestimonial", $this->btFieldsRequired) && trim($args["shorttestimonial"]) == "") {
            $e->add(t("The %s field is required.", t("Short Testimonial")));
        }
        if (in_array("fullimage", $this->btFieldsRequired) && (trim($args["fullimage"]) == "" || !is_object(File::getByID($args["fullimage"])))) {
            $e->add(t("The %s field is required.", t("Full Image")));
        }
        if (in_array("fulltestimonial", $this->btFieldsRequired) && (trim($args["fulltestimonial"]) == "")) {
            $e->add(t("The %s field is required.", t("Full Testimonial")));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }
}