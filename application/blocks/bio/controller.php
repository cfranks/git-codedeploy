<?php  namespace Application\Block\Bio;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Core;
use Loader;
use \File;
use Page;
use URL;
use \Concrete\Core\Editor\Snippet;
use Sunra\PhpSimple\HtmlDomParser;
use \Concrete\Core\Editor\LinkAbstractor;

class Controller extends BlockController
{
    public $helpers = array (
  0 => 'form',
);
    public $btFieldsRequired = array (
  0 => 'content',
);
    protected $btExportFileColumns = array (
  0 => 'img',
);
    protected $btTable = 'btBio';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    protected $btCacheBlockOutputLifetime = 0;
    
    public function getBlockTypeDescription()
    {
        return t("Use this block to add bios");
    }

    public function getBlockTypeName()
    {
        return t("Bio Block");
    }

    public function getSearchableContent()
    {
        $content = array();
        $content[] = $this->content;
        return implode(" ", $content);
    }

    public function view()
    {
        $db = \Database::get();
        
        if ($this->img) {
            $f = \File::getByID($this->img);
            if (is_object($f)) {
                $this->set("img", $f);
            }
            else {
                $this->set("img", false);
            }
        }
        $this->set('content', LinkAbstractor::translateFrom($this->content));
    }

    public function add()
    {
        $this->requireAsset('redactor');
        $this->requireAsset('core/file-manager');
        $this->set('btFieldsRequired', $this->btFieldsRequired);
    }

    public function edit()
    {
        $db = \Database::get();
        $this->requireAsset('redactor');
        $this->requireAsset('core/file-manager');
        $this->set('content', LinkAbstractor::translateFromEditMode($this->content));
        $this->set('btFieldsRequired', $this->btFieldsRequired);
    }

    public function save($args)
    {
        $db = \Database::get();
        $args['content'] = LinkAbstractor::translateTo($args['wysiwyg-ft-content']);
        parent::save($args);
    }

    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if (in_array("img", $this->btFieldsRequired) && (trim($args["img"]) == "" || !is_object(\File::getByID($args["img"])))){
            $e->add(t("The %s field is required.", "Image"));
        }
        if (in_array("content", $this->btFieldsRequired) && trim($args["wysiwyg-ft-content"]) == "") {
            $e->add(t("The %s field is required.", "Content"));
        }
        return $e;
    }

    public function composer()
    {
        $this->edit();
    }

    
}