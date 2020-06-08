<?php  
namespace Concrete\Package\Problog\Controller\Tools;

use Loader;
use URL;
use Package;
use Page;
use \Concrete\Core\Controller\Controller as RouteController;
use TwitterOAuth;

class DoTweet extends RouteController
{
    public function send()
    {
        $valt = Loader::helper('validation/token');
        if (!$valt->validate('tweet_token', $_REQUEST['tweet_token'])) {
            throw new Exception($valt->getErrorMessage());
        }
        $ba = Loader::helper('blog_actions');
        $ba->doTweet(Page::getByID($_REQUEST['pID']),$_REQUEST['hashtags']);
        print 'success';
        exit;
    }
}