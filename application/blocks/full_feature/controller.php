<?php namespace Application\Block\FullFeature;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Core;
use File;
use Page;

class Controller extends BlockController
{
    public $btFieldsRequired = ['img', 'content'];
    protected $btExportFileColumns = ['img'];
    protected $btTable = 'btFullFeature';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btIgnorePageThemeGridFrameworkContainer = true;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $pkg = false;
    
    public function getBlockTypeName()
    {
        return t("Full Width Feature");
    }

    public function getSearchableContent()
    {
        $content = [];
        $content[] = $this->content;
        return implode(" ", $content);
    }

    public function view()
    {
        
        if ($this->img && ($f = File::getByID($this->img)) && is_object($f)) {
            $this->set("img", $f);
        } else {
            $this->set("img", false);
        }
        $this->set('content', LinkAbstractor::translateFrom($this->content));
    }

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
        
        $this->set('content', LinkAbstractor::translateFromEditMode($this->content));
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
        $args['content'] = LinkAbstractor::translateTo($args['content']);
        parent::save($args);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if (in_array("img", $this->btFieldsRequired) && (trim($args["img"]) == "" || !is_object(File::getByID($args["img"])))) {
            $e->add(t("The %s field is required.", t("Image (1600x600)")));
        }
        if (in_array("content", $this->btFieldsRequired) && (trim($args["content"]) == "")) {
            $e->add(t("The %s field is required.", t("Content")));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }
}