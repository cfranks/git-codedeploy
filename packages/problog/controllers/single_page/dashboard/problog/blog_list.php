<?php  
namespace Concrete\Package\Problog\Controller\SinglePage\Dashboard\Problog;

use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Core\Page\Page as Page;
use \Concrete\Package\Problog\Src\Models\ProblogList;
use Loader;

class BlogList extends DashboardPageController
{

    public $helpers = array('html', 'form', 'navigation');

    public function on_start()
    {
        Loader::model('page_list');
        $this->error = Loader::helper('validation/error');
    }

    public function view()
    {
        $this->loadblogSections();
        $blogList = new ProblogList();
        $blogList->displayUnapprovedPages();
        $blogList->includeSystemPages(true);
        $blogList->includeInactivePages(true);
        $blogList->sortBy('cDateAdded', 'desc');
        if (isset($_GET['cParentID']) && $_GET['cParentID'] > 0) {
            $path = Page::getByID($_GET['cParentID'])->getCollectionPath();
            $blogList->filterByPath($path);
        }

        $blogList->filter(false,"(CHAR_LENGTH(cv.cvName) > 4 OR cv.cvName NOT REGEXP '^[0-9]')");

        if (empty($_GET['cParentID'])) {
            $sections = $this->get('sections');
            $keys = array_keys($sections);
            if (is_array($keys)) {
                foreach ($keys as $id) {
                    $fs .= ' OR ';
                    $path = Page::getByID($id)->getCollectionPath().'/';
                    $fs .= "pp.cPath LIKE '$path%'";
                }
                $blogList->filter(false,"(pp.cPath LIKE '/!drafts/%'$fs)");
            }
        }

        $blogList->displayUnapprovedPages();

        if (!empty($_GET['like'])) {
            $blogList->filterByName($_GET['like']);
        }

        if ($_GET['only_unaproved'] > 0) {
            $blogList->filter(false,"p.cIsActive != 1");
        }

        $blogList->setItemsPerPage($this->num);

        if (!empty($_GET['cat'])) {
            $cat = $_GET['cat'];
            $blogList->filter(false,"ak_blog_category LIKE '%\n$cat\n%'");
        }

        if (!empty($_GET['tag'])) {
            $tag = $_GET['tag'];
            $blogList->filter(false,"ak_tags LIKE '%\n$tag\n%'");
        }
        //$blogList->debug();
        $blogResults=$blogList->get();

        $this->set('blogResults', $blogResults);
        $this->set('blogList', $blogList);
        $this->set('cat_values', $this->getblogCats());
        $this->set('tag_values', $this->getblogTags());
        $this->set('sortOrder',$this->request('ccm_order_by_direction'));
        if ($this->request('ccm_order_by_direction')=='asc') {$nextSort = 'desc';} else {$nextSort = 'asc';}
        $this->set('nextSort',$nextSort);
        $this->set('controller',$this);
    }

    protected function loadblogSections()
    {
        $blogSectionList = new ProblogList();
        $blogSectionList->setItemsPerPage($this->num);
        $blogSectionList->filterByBlogSection(1);
        $blogSectionList->sortBy('cvName', 'asc');
        $tmpSections = $blogSectionList->get();
        $sections = array();
        foreach ($tmpSections as $_c) {
            $sections[$_c->getCollectionID()] = $_c->getCollectionName();
        }
        $this->set('sections', $sections);
    }

    public function delete_check($cIDd,$name=null)
    {
        $this->set('remove_name',$name);
        $this->set('remove_cid',$cIDd);
        $this->view();
    }

    public function approvethis($cIDd,$name=null)
    {
        $p = Page::getByID($cIDd);
        $p->activate();
        $this->set('message', t('"%s" has been approved and is now public', $name));
        $this->view();
    }

    public function deletethis($cIDd,$name=null)
    {
        $c= Page::getByID($cIDd);
        $c->delete();
        $this->set('message', t('"%s" has been deleted', $name));
        $this->set('remove_name','');
        $this->set('remove_cid','');
        $this->view();
    }

    public function clear_warning()
    {
        $this->set('remove_name','');
        $this->set('remove_cid','');
        $this->view();
    }

    public function checkActiveSortHeader($col)
    {
        if ($this->request('ccm_order_by')==$col) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getblogCats()
    {
        $db = Loader::db();
        $akID = $db->query("SELECT akID FROM AttributeKeys WHERE akHandle = 'blog_category'");
        while ($row=$akID->fetchrow()) {
            $akIDc = $row['akID'];
        }
        $akv = $db->execute("SELECT value FROM atSelectOptions WHERE akID = $akIDc");
        while ($row=$akv->fetchrow()) {
            $values[]=$row;
        }
        if (empty($values)) {
            $values = array();
        }

        return $values;
    }

    public function getblogTags()
    {
        $db = Loader::db();
        $akID = $db->query("SELECT akID FROM AttributeKeys WHERE akHandle = 'tags'");
        while ($row=$akID->fetchrow()) {
            $akIDc = $row['akID'];
        }
        $akv = $db->execute("SELECT value FROM atSelectOptions WHERE akID = $akIDc");
        while ($row=$akv->fetchrow()) {
            $values[]=$row;
        }
        if (empty($values)) {
            $values = array();
        }

        return $values;
    }

    public function blogadded()
    {
        $this->set('message', t('Blog added.'));
        $this->view();
    }

    public function blog_updated()
    {
        $this->set('message', t('Blog updated.'));
        $this->view();
    }
}
