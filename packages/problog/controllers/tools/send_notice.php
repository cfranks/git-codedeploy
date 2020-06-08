<?php  
namespace Concrete\Package\Problog\Controller\Tools;

use Page;
use Loader;
use \Concrete\Core\Controller\Controller as RouteController;

class SendNotice extends RouteController
{
    public function send()
    {
        $p = Page::getByID($_REQUEST['pID']);
        $ba = Loader::helper('blog_actions');
        $ba->doSubscription($p);

    }
}
