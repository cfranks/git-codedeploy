<?php  
namespace Concrete\Package\Problog\Controller\SinglePage\Account;

use \Concrete\Core\Page\Controller\AccountPageController;
use Loader;
use Page;
use View;
use Block;
use \Concrete\Core\Page\PageList as PageList;
use \Concrete\Core\Page\Type\Type as CollectionType;
use \Concrete\Core\Permission\Key\AddSubpagePageKey;
use Events;

class Publish extends AccountPageController
{

    public function view()
    {
        $html = Loader::helper('html');
        // $this->addHeaderItem($html->css('css/font-awesome.css','problog'));
        // $this->addHeaderItem($html->css('css/seo_tools.css','problog'));
        // $this->addHeaderItem($html->css('app.css'));
        // $this->addHeaderItem($html->css('redactor.css'));
        // $this->addHeaderItem($html->css('jquery-ui.css'));
        // $this->addHeaderItem($html->javascript('jquery.js'));
        // $this->addHeaderItem('<script type="text/javascript"> var $ = jQuery.noConflict(); </script>');
        // $this->addHeaderItem($html->javascript('legacy.js'));
        // $this->addHeaderItem($html->javascript('jquery-ui.js'));
        // $this->addHeaderItem($html->javascript('events.js'));
         //$this->addHeaderItem($html->javascript('app.js'));
        //$this->addHeaderItem($html->javascript('redactor.js'));
         // $this->addFooterItem($html->javascript('file-manager.js'));
        // $this->addFooterItem($html->javascript('seo_tools.js','problog'));

        $blogify = Loader::helper('blogify');

        $settings = $blogify->getBlogSettings();

        $blogSectionList = new PageList();
        $blogSectionList->filter(false, "ak_blog_section = 1");
        $blogSectionList->sortBy('cvName', 'asc');
        $tmpSections = $blogSectionList->get();
        $sections = array();
        foreach ($tmpSections as $_c) {
            $spk = new AddSubpagePageKey();
            $pp = $spk->validate($_c);
            if ($pp) {
                $pt = $p;
                $sections[$_c->getCollectionID()] = $_c->getCollectionName();
            }
        }
        $ctArray = CollectionType::getList('');
        $pageTypes = array();
        foreach ($ctArray as $ct) {
            $spk = new AddSubpagePageKey();
            $pp = $spk->validate($ct);
            if ($pp) {
                $pageTypes[$ct->getPageTypeID()] = $ct->getPageTypeName();
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
            $ctID = $this->blog->getCollectionTypeID();
            $blogBody = '';
            $eb = $this->blog->getBlocks('Main');
            if (is_object($eb[0])) {
                $blogBody = $eb[0]->getInstance()->getContent();
            }
            echo "<div class=\"event_warning\"><span class=\"tooltip icon edit\"></span> You are now editing <b><u>$blogTitle</u></b></div>";
            $task = 'editthis';
            $buttonText = t('Update Blog Entry');
            $title = 'Update';
        } else {
            $task = 'addthis';
            $buttonText = t('Add Blog Entry');
            $title= 'Add';
        }

        $this->set('blog', $this->blog);
        $this->set('blogTitle', $blogTitle);
        $this->set('blogDescription', $blogDescription);
        $this->set('blogBody', $blogBody);
        $this->set('sections', $sections);
        $this->set('pageTypes', $pageTypes);
        $this->set('buttonText', $buttonText);
    }

}
