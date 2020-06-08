<?php  
namespace Concrete\Package\Problog\Controller\SinglePage\Dashboard\Problog;

use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Core\Page\PageList as PageList;
use \Concrete\Core\Page\Page as Page;
use \Concrete\Package\Problog\Src\Models\ProblogPost;
use \Concrete\Package\Problog\Src\Models\ProblogList;
use PageTemplate;
use \Concrete\Core\Page\Type\Type as CollectionType;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use \Concrete\Core\Permission\Key\AddSubpagePageKey;
use \Concrete\Core\Permission\Access\Entity\UserEntity as UserAccess;
use \Concrete\Core\Permission\Access\AddSubpagePageAccess;
use \Concrete\Core\Page\Type\Composer\FormLayoutSet;
use Loader;
use Events;
use User;
use UserInfo;

class AddBlog extends DashboardPageController
{

    public $helpers = array('html','form');

    public function on_start()
    {
        $html = Loader::helper('html');
        $this->error = Loader::helper('validation/error');
        $this->addHeaderItem($html->css('app.css'));
        $this->addHeaderItem($html->javascript('jquery.js'));
        $this->addFooterItem($html->javascript('jquery-ui.js'));
        $this->addHeaderItem($html->css('jquery-ui.css'));

        $this->requireAsset('redactor');
        $this->requireAsset('core/file-manager');
    }

    public function view()
    {
        $blogify = Loader::helper('blogify');
        $settings = $blogify->getBlogSettings();
        $this->set('settings',$settings);

        $this->setupForm();
        $blogList = new PageList();
        $blogList->sortBy('cDateAdded', 'desc');
        if (isset($_GET['cParentID']) && $_GET['cParentID'] > 0) {
            $blogList->filterByParentID($_GET['cParentID']);
        } else {
            $sections = $this->get('sections');
            $keys = array_keys($sections);
            $keys[] = -1;
            $blogList->filterByParentID($keys);
        }
    }

    protected function loadblogSections()
    {
        $blogSectionList = new ProBlogList();
        $blogSectionList->filterByBlogSection(1);
        $blogSectionList->sortBy('cvName', 'asc');
        $tmpSections = $blogSectionList->get();
        
        $sections = array();
        foreach ($tmpSections as $_c) {
	        $pt = CollectionType::getByID($_c->getCollectionTypeID());
            $cmp = new \Permissions($pt);
            $pp = $cmp->canAddPageType();
            if ($pp) {
                $this->pt = $pt;
                $sections[$_c->getCollectionID()] = $_c->getCollectionName();
            }
        }
        $this->sections = $sections;
        $this->set('sections', $this->sections);
    }

    protected function setupForm()
    {
        $this->loadblogSections();
        $ctArray = PageTemplate::getList('');
        $pageTemplates = array();
        foreach ($ctArray as $ct) {
            $pageTemplates[$ct->getPageTemplateID()] = $ct->getPageTemplateName();
        }
        $this->set('pageTemplates', $pageTemplates);
    }

    public function edit($cID)
    {
        $this->setupForm();
        $blog = ProblogPost::getByID($cID);
        $date = $blog->getCollectionDatePublic();
        $canonical_parent_id = Loader::helper('blogify')->getCanonicalParent($date,$blog);
        $this->set('cParentID',$canonical_parent_id);
        $this->set('ptID', $blog->getPageTemplateID());
        $newdate = Loader::helper('form/date_time')->translate('blogDate');
        if ($this->isPost()) {
            static::validate();
            if (!$this->error->has()) {
                $p = ProblogPost::getByID($this->post('blogID'));
                $parent = ProblogPost::getParentByID($this->post('cParentID'));
                $data = array('cDescription' => $this->post('blogDescription'),'cName' => $this->post('blogTitle'), 'cDatePublic' => $newdate,'pTemplateID'=>$this->post('ptID'));
                $p->update($data);
                if ($this->post('draft')==1 || $this->post('draft')==2) {
                    $p->deactivate();
                    if ($this->post('draft')==2) {
                        Loader::helper('blog_actions')->send_publish_request($p);
                    }
                } else {
                    $p->getVersionObject()->approve();
                    $p->activate();
                }
                if ($canonical_parent_id != $parent->getCollectionID() || $date != $newdate) {
                    $canonical = Loader::helper('blogify')->getOrCreateCanonical($newdate,$parent);
                    $p->move($canonical);
                }
                ProblogPost::saveData($p);
                if (!$this->post('save_post')) {
                    $this->redirect('/dashboard/problog/blog_list/', 'blog_updated');
                }
            }
        }

        $sections = $this->get('sections');

        if (in_array($canonical_parent_id, array_keys($sections))) {
            $this->set('blog', $blog);
            $this->set('cParentID',$canonical_parent_id);
        } else {
            $this->redirect('/dashboard/problog/add_blog/');
        }
        $this->view();
    }

    public function add()
    {
        $this->setupForm();
        if ($this->isPost()) {
            $this->validate();
            if (!$this->error->has()) {
                $date = Loader::helper('form/date_time')->translate('blogDate');
                $parent = ProblogPost::getParentByID($this->post('cParentID'));
                $canonical = Loader::helper('blogify')->getOrCreateCanonical($date,$parent);
                $ct = CollectionType::getByHandle('pb_post');
                $data = array('cName' => $this->post('blogTitle'), 'cDescription' => $this->post('blogDescription'), 'cDatePublic' => $date);
                $p = $canonical->add($ct, $data);
                $p = ProblogPost::getByID($p->getCollectionID());
                $p->update(array('pTemplateID'=>$this->post('ptID')));
                if ($this->post('draft')==1 || $this->post('draft')==2) {
                    $p->deactivate();
                    if ($this->post('draft')==2) {
                        Loader::helper('blog_actions')->send_publish_request($p);
                    }
                }
                ProblogPost::saveData($p);
                if (!$this->post('save_post')) {
                    $this->redirect('/dashboard/problog/blog_list/', 'blogadded');
                }else{
                    $this->redirect('/dashboard/problog/add_blog/edit/'.$p->getCollectionID());
                }
            }
        }
    }

    protected function validate()
    {
        $vt = Loader::helper('validation/strings');
        $vn = Loader::Helper('validation/numbers');
        $dt = Loader::helper("form/date_time");
        if (!$vn->integer($this->post('cParentID'))) {
            $this->error->add(t('You must choose a parent page for this blog entry.'));
        }

        if (!$vt->notempty($this->post('blogTitle'))) {
            $this->error->add(t('Title is required'));
        }

        if (!$this->get('sections')) {
            $this->error->add(t('You must have at least one page in your website designated as a "blog section".'));
        }

        Loader::model("attribute/categories/collection");

        $akct = CollectionAttributeKey::getByHandle('blog_category');
        $ctKey = $akct->getAttributeKeyID();
        foreach ($this->post(akID) as $key => $value) {
            if ($key==$ctKey) {
                foreach ($value as $type => $values) {
                    if ($type=='atSelectNewOption') {
                        foreach ($values as $cat => $valued) {
                            if ($valued=='') {
                                $this->error->add(t('Categories must have a value'));
                            }
                        }
                    }
                }
            }
        }

        if (!$this->error->has()) {
            $parent = Page::getByID($this->post('cParentID'));
            $cmp = new \Permissions($parent);
            $parentPermissions = $cmp->canAddSubpage();
            if (!$parentPermissions) {
                $this->error->add(t('You do not have permission to add a page of that type to that area of the site.'));
            }
        }
    }

}
