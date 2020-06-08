<?php  
namespace Concrete\Package\Problog\Src\Models;

use Loader;
use \Concrete\Core\Page\Page as Page;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use \Concrete\Core\Block\BlockType\BlockType as BlockType;
use \Concrete\Core\Attribute\Set as AttributeSet;
use \Concrete\Core\Page\Type\Composer\FormLayoutSet;
use \Concrete\Core\Page\Type\Composer\Control\Type\Type as PageTypeComposerControlType;
use \Concrete\Core\Page\Type\Composer\FormLayoutSetControl;
use Events;
/**
*
* An object that allows a filtered list of blogs to be returned.
* @package ProBlog
*
**/
class ProblogPost extends Page
{

    public function getParentByID($parentID)
    {
        return self::getByID($parentID);
    }

    public static function saveData($p)
    {
        $blocks = $p->getBlocks('Main');
        foreach ($blocks as $b) {
            if ($b->getBlockTypeHandle()=='content' || $b->getBlockTypeHandle()=='core_page_type_composer_control_output') {
                $b->deleteBlock();
            }
        }

        Loader::model("attribute/categories/collection");
        $cak = CollectionAttributeKey::getByHandle('tags');
        $cak->saveAttributeForm($p);

        $cck = CollectionAttributeKey::getByHandle('meta_title');
        $cck->saveAttributeForm($p);

        $cck = CollectionAttributeKey::getByHandle('meta_description');
        $cck->saveAttributeForm($p);

        $cck = CollectionAttributeKey::getByHandle('meta_keywords');
        $cck->saveAttributeForm($p);

        $cck = CollectionAttributeKey::getByHandle('blog_category');
        $cck->saveAttributeForm($p);

        //$cnv = CollectionAttributeKey::getByHandle('exclude_nav');
        //$cnv->saveAttributeForm($p);

        $ct = CollectionAttributeKey::getByHandle('thumbnail');
        $ct->saveAttributeForm($p);

        $ca = CollectionAttributeKey::getByHandle('blog_author');
        $ca->saveAttributeForm($p);

        $set = AttributeSet::getByHandle('problog_additional_attributes');
        $setAttribs = $set->getAttributeKeys();
        if ($setAttribs) {
            foreach ($setAttribs as $ak) {
                $aksv = CollectionAttributeKey::getByHandle($ak->akHandle);
                $aksv->saveAttributeForm($p);
            }
        }

        $bt = BlockType::getByHandle('content');
        if (empty($_POST['blogBody'])) {$content = ' ';} else {$content = $_POST['blogBody'];}
        $data = array('content' => $content);
        $b = $p->addBlock($bt, 'Main', $data);
        $b->setCustomTemplate('blog_post');
        $b->setAbsoluteBlockDisplayOrder('1');

        $db = Loader::db();
        $pTemplate = $db->getOne("SELECT ptComposerFormLayoutSetControlID FROM PageTypeComposerOutputControls WHERE pTemplateID = ? AND ptID = ?",array($p->getPageTemplateID(),$p->getPageTypeID()));
        if($pTemplate){
            $db->Replace('PageTypeComposerOutputBlocks', array(
                                        'cID'=>$p->getCollectionID(),
                                        'arHandle'=>'Main',
                                        'cbDisplayOrder'=>0,
                                        'ptComposerFormLayoutSetControlID'=>$pTemplate,
                                        'bID'=>$b->getBlockID()
                                    ), 'cID', true);
        }

        Events::fire('on_problog_submit', $p);
        
        $ba = Loader::helper('blog_actions');
		$ba->doSubscription($p);

        $p->reindex();

    }
}