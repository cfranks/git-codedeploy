<?php  
namespace Concrete\Package\Problog\Controller\Tools;

use Loader;
use Page;
use Block;
use \Concrete\Package\Problog\Src\Models\ProblogPost;
use \Concrete\Core\Controller\Controller as RouteController;
use \Concrete\Core\Page\PageList as PageList;
use \Concrete\Core\Block\BlockType\BlockType as BlockType;
use \Concrete\Core\Page\Type\Type as CollectionType;
use \Concrete\Core\Page\Template as PageTemplate;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use \Concrete\Core\Attribute\Set as AttributeSet;
use \Concrete\Core\Permission\Key\AddSubpagePageKey;
use Events;

class AddBlog extends RouteController
{

    /**
     * render Add Blog dialog
     */
    public function render()
    {
	    $this->requireAsset('redactor');
        $this->requireAsset('core/file-manager');
        
        $blogify = Loader::helper('blogify');

        $settings = $blogify->getBlogSettings();

        $blogSectionList = new PageList();
        $blogSectionList->filter(false, "ak_blog_section = 1");
        $blogSectionList->sortBy('cvName', 'asc');
        $tmpSections = $blogSectionList->get();
        $sections = array();
        foreach ($tmpSections as $_c) {
            $pt = CollectionType::getByID($_c->getCollectionTypeID());
            $cmp = new \Permissions($pt);
            $pp = $cmp->canAddPageType();
            if ($pp) {
                $sections[$_c->getCollectionID()] = $_c->getCollectionName();
            }
        }
        $ctArray = PageTemplate::getList('');
        $pageTemplates = array();
        foreach ($ctArray as $ct) {
            $pms = new AddSubpagePageKey($ct);
            $pp = $pms->validate();
            if ($pp) {
                $pageTemplates[$ct->getPageTemplateID()] = $ct->getPageTemplateName();
            }
        }
        if ($_REQUEST['postID']) {
            $keys = array_keys($sections);
            $keys[] = -1;
            $current_page = Page::getByID($_REQUEST['postID']);
            $date = $current_page->getCollectionDatePublic();
            $canonical_parent_id = $blogify->getCanonicalParent($date,$current_page);

            $cParentID = $canonical_parent_id;

            if (in_array($canonical_parent_id, $keys)) {
                $this->blog = $current_page;
            }
        }

        if (is_object($this->blog)) {
            $blogTitle = $this->blog->getCollectionName();
            $blogDescription = $this->blog->getCollectionDescription();
            $blogDate = $this->blog->getCollectionDatePublic();
            $ptID = $this->blog->getPageTemplateID();
            $blogBody = '';
            $eb = $this->blog->getBlocks('Main');
            if (is_object($eb[0])) {
                $blogBody = $eb[0]->getInstance()->getContent();
            }
            echo "<div class=\"alert alert-success\"><span class=\"tooltip icon edit\"></span> ".t('You are now editing')." <b><u>$blogTitle</u></b></div>";
            $task = 'editthis';
            $buttonText = t('Update Blog Entry');
            $title = 'Update';
        } else {
            $task = 'addthis';
            $buttonText = t('Add Blog Entry');
            $title= 'Add';
        }

        Loader::PackageElement(
            'tools/add_blog',
            'problog',
            array(
                'blog' => $this->blog,
                'blogTitle'=>$blogTitle,
                'blogDescription'=>$blogDescription,
                'blogBody'=>$blogBody,
                'sections' => $sections,
                'pageTemplates' => $pageTemplates,
                'buttonText'=>$buttonText,
                'ptID'=>$ptID,
                'settings'=>$settings
            )
        );
    }

    /**
     * render Add Blog dialog
     */
    public function save()
    {
        $blogify = Loader::helper('blogify');
        $error = Loader::helper('validation/error');
        $error = $this->validate($error);

        if (!$error->has()) {
            $date = Loader::helper('form/date_time')->translate('blogDate');
            $parent = ProblogPost::getParentByID($_REQUEST['cParentID']);
            $canonical = $blogify->getOrCreateCanonical($date,$parent);

            $ct = CollectionType::GetByHandle('pb_post');

            $data = array('cName' => $_REQUEST['blogTitle'], 'cDescription' => $_REQUEST['blogDescription'], 'cDatePublic' => $date,'pTemplateID'=>$this->post('ptID'));

            if ($_REQUEST['blogID']) {
                $p = ProblogPost::getByID($_REQUEST['blogID']);
                $old_parent_id = $blogify->getCanonicalParent($date,$p);
                $olddate = $p->getCollectionDatePublic();

                $p->update($data);

                if ($old_parent_id != $canonical->getCollectionID() || $date != $olddate) {
                    $p->move($canonical);
                }
            } else {
                $p = $canonical->add($ct, $data);
            }

            if ($_REQUEST['draft']==1 || $_REQUEST['draft']==2) {
                $p->deactivate();
                if ($_REQUEST['draft']==2) {
                    Loader::helper('blog_actions')->send_publish_request($p);
                }
            }

            ProblogPost::saveData($p);

            print Loader::helper('json')->encode(array('success'));
        } else {
            $errors = $error->getList();
            print Loader::helper('json')->encode($errors);
        }
    }

    public function validate($error)
    {
        $vt = Loader::helper('validation/strings');
        $vn = Loader::Helper('validation/numbers');
        $dt = Loader::helper("form/date_time");
        //$er = Loader::helper('validation/error');

        if (!$vn->integer($_REQUEST['cParentID'])) {
            $error->add(t('You must choose a parent page for this blog entry.'));
        }

        if (!$vt->notempty($_REQUEST['blogTitle'])) {
            $error->add(t('Title is required'));
        }

        Loader::model("attribute/categories/collection");

        $akct = CollectionAttributeKey::getByHandle('blog_category');
        $ctKey = $akct->getAttributeKeyID();
        foreach ($_REQUEST['akID'] as $key => $value) {
            if ($key==$ctKey) {
                foreach ($value as $type => $values) {
                    if ($type=='atSelectNewOption') {
                        foreach ($values as $cat => $valued) {
                            if ($valued=='') {
                                $error->add(t('Categories must have a value'));
                            }
                        }
                    }
                }
            }
        }
        
        if (!$error->has()) {
            $parent = Page::getByID($_REQUEST['cParentID']);
            $cmp = new \Permissions($parent);
            $parentPermissions = $cmp->canAddSubpage();
            if (!$parentPermissions) {
                $error->add(t('You do not have permission to add a page of that type to that area of the site.'));
            }
        }

        return $error;
    }
}
