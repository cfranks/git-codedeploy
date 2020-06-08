<?php  
namespace Concrete\Package\Problog\Controller\Tools;

use \Concrete\Core\Controller\Controller as RouteController;
use Loader;


class Optimizer extends RouteController
{

    /**
     * render Add Blog dialog
     */
    public function run()
    {
        $blogify = Loader::helper('blogify');
        $settings = $blogify->getBlogSettings();
        Loader::packageElement('tools/optimizer','problog',array('settings'=>$settings));
    }
}