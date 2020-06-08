<?php  
namespace Concrete\Package\Problog\Src\Models;

use \Concrete\Core\Page\PageList;
/**
*
* An object that allows a filtered list of blogs to be returned.
* @package ProBlog
*
**/
class ProblogList extends PageList
{
    public function sortByPublicDateTime()
    {
        $this->sortBy('cvDatePublic', 'desc');
    }

    public function filterByPath($path)
    {
        $this->filter(false,"(pp.cPath LIKE '$path%' AND pp.cPath <> '$path')");
    }
    
    public function filterByBlogSection($type) {
        $this->filter(false, "ak_blog_section = " . $type);
    }

}
