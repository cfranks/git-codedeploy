<?php  
namespace Concrete\Package\Problog\Src\Page\Type\PublishTarget\Configuration;

use Concrete\Core\Page\Type\Type;
use Page;
use \Concrete\Core\Page\Type\PublishTarget\Configuration\Configuration as Configuration;
use \Concrete\Package\Problog\Src\Models\ProblogList;

class BlogSectionConfiguration extends Configuration
{

    public function getDefaultParentPageID()
    {
        $pp = Page::getByPath('/blog');
        if ($pp) {
            return $pp->getCollectionID();
        }
    }

    public function canPublishPageTypeBeneathTarget(Type $pagetype, \Concrete\Core\Page\Page $page)
    {
        if ($page->getAttribute('blog_section') > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getBlogSectionPages()
    {
        $blogSectionList = new ProblogList();
        $blogSectionList->displayUnapprovedPages();
        $blogSectionList->setItemsPerPage(10);
        $blogSectionList->filter(false, "csi.ak_blog_section = 1");
        $blogSectionList->sortBy('cvName', 'asc');
        $tmpSections = $blogSectionList->getResults();
        $sections = array();
        foreach ($tmpSections as $_c) {
            $sections[$_c->getCollectionID()] = $_c->getCollectionName();
        }

        return $sections;
    }

}
