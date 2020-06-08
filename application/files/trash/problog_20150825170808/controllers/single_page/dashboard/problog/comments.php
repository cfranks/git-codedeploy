<?php  
namespace Concrete\Package\Problog\Controller\SinglePage\Dashboard\Problog;

use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Core\Page\PageList as PageList;
use \Concrete\Core\Page\Page as Page;
use Loader;
use URL;

class Comments extends DashboardPageController
{

    public $helpers = array('html', 'form');

    public function on_start()
    {
        //$this->error = Loader::helper('validation/error');
    }

    public function view()
    {
        $this->loadblogSections();
        $blogList = new PageList();
        $sections = $this->get('sections');
        $keys = array_keys($sections);
        $comments = $this->getCommentsByParentIDs($keys);
        $this->set('comments', $comments);
    }

    protected function loadblogSections()
    {
        $blogSectionList = new PageList();
        $blogSectionList->filterByBlogSection(1);
        $blogSectionList->sortBy('cvName', 'asc');
        $tmpSections = $blogSectionList->get();
        $sections = array();
        foreach ($tmpSections as $_c) {
            $sections[$_c->getCollectionID()] = $_c->getCollectionName();
        }
        $this->set('sections', $sections);
    }

    public function getCommentsByParentIDs($IDs)
    {
        $db = Loader::db();
        $child_pages = array();

        $blogList = new PageList();
        $blogList->sortBy('cDateAdded', 'desc');
        $blogList->filter(false,"(CHAR_LENGTH(cv.cvName) > 4 OR cv.cvName NOT REGEXP '^[0-9]')");

        if (!$IDs) {
            $r = $db->EXECUTE("SELECT DISTINCT cnv.cID FROM ConversationMessages cnvm LEFT JOIN Conversations cnv ON cnvm.cnvID = cnv.cnvID ORDER BY cnvMessageDateCreated DESC");
            while ($row=$r->fetchrow()) {
                $IDs[] = $row['cID'];
            }
        }

        if (is_array($IDs)) {
            foreach ($IDs as $id) {
                if ($fs) {$fs .= ' OR ';}
                $path = Page::getByID($id)->getCollectionPath().'/';
                $fs .= "pp.cPath LIKE '$path%'";
            }
            $blogList->filter(false,"($fs)");
        }
        $blogList->filter(false,"(CHAR_LENGTH(cv.cvName) > 4 OR cv.cvName NOT REGEXP '^[0-9]')");
        $blogResults=$blogList->get();

        foreach ($blogResults as $result) {
            $child_pages[] = $result->getCollectionID();
        }

        $filter = '';
        if($this->request('comment_todo')){
            $this->set('comment_todo',$this->request('comment_todo'));
            switch($this->request('comment_todo')){
                case 'approves':
                    $filter = "WHERE cnvm.cnvIsMessageApproved = 1";
                    break;
                case 'unapproves':
                    $filter = "WHERE cnvm.cnvIsMessageDeleted = 1";
                    break;
            }
        }

        $r = $db->EXECUTE("SELECT * FROM ConversationMessages cnvm LEFT JOIN Conversations cnv ON cnvm.cnvID = cnv.cnvID $filter ORDER BY cnvMessageDateCreated DESC");
        $comments = array();

        while ($row=$r->fetchrow()) {
            $ccObj = Page::getByID($row['cID']);
            $pID = $ccObj->getCollectionID();
            //var_dump($pID.' - '.print_r($child_pages));
            if (in_array($pID, $child_pages)) {
                $comments[] = $row;
            }
        }

        return $comments;
    }

    public function delete($cnvMessageID)
    {
        $db = Loader::db();
        $db->execute("DELETE FROM ConversationMessages WHERE cnvMessageID = ?",array($cnvMessageID));
        $this->redirect('/dashboard/problog/comments/message_deleted/');
    }

    public function message_deleted()
    {
        $this->set('message',t('Message Has Been Deleted!'));
        $this->view();
    }

    public function remove($cnvMessageID)
    {
        $db = Loader::db();
        $db->update('ConversationMessages',array('cnvIsMessageApproved'=>0,'cnvIsMessageDeleted'=>1),array('cnvMessageID'=>$cnvMessageID));
        $this->redirect('/dashboard/problog/comments/message_removed/');
    }

    public function message_removed()
    {
        $this->set('message',t('Message unapproved!'));
        $this->view();
    }

    public function approve($cnvMessageID)
    {
        $db = Loader::db();
        $db->update('ConversationMessages',array('cnvIsMessageApproved'=>1,'cnvIsMessageDeleted'=>0),array('cnvMessageID'=>$cnvMessageID));
        $this->redirect('/dashboard/problog/comments/message_approved/');
    }

    public function message_approved()
    {
        $this->set('message',t('Message Approved!'));
        $this->view();
    }
}
