<?php   namespace Concrete\Package\Problog\Controller\Helpers;

use Page;
use Package;
use UserInfo;
use \Concrete\Core\Block\BlockType\BlockType as BlockType;
use Loader;
use TwitterOAuth;

class BlogActions {
    public function __construct()
    {
    }
    
    public function doTweet($p,$hash)
    {
        $nh = Loader::helper('navigation');
        $pkg = Package::getByHandle('problog');
        $PB_AUTH_TOKEN = $pkg->getConfig()->get('api.twitter_auth_token', false);
        $PB_AUTH_SECRET = $pkg->getConfig()->get('api.twitter_auth_secret', false);
        $PB_APP_KEY = $pkg->getConfig()->get('api.twitter_app_key', false);
        $PB_APP_SECRET = $pkg->getConfig()->get('api.twitter_app_secret', false);

        if ($PB_AUTH_TOKEN) {
            $connection = new TwitterOAuth($PB_APP_KEY,$PB_APP_SECRET,$PB_AUTH_TOKEN,$PB_AUTH_SECRET);
            $msg = t('New Blog Post!').' - '.$p->getCollectionName().' : '.BASE_URL.$nh->getLinkToCollection($p).' '.$hash;
            $update_status = $connection->post('statuses/update',array('status' => $msg));
            $temp = $update_status->response;
        }
    }
    
  
    public function doSubscription($p)
    {
		$parentID = Loader::helper('blogify')->getCanonicalParent(null,$p);
		$parent = Page::getByID($parentID);
		$subscription = $parent->getAttribute('subscription');
		if($_REQUEST['send_to_subscribers'] == 1){
			if(is_array($subscription)){
				foreach($subscription as $uID){
					$ui = UserInfo::getByID($uID);
					
					$mh = Loader::helper('mail');
					$mh->from('subscriptions@'.str_replace('www', '',$_SERVER['SERVER_NAME']));
					$mh->to($ui->getUserEmail(),$ui->getUserFirstName().' '.$ui->getUserLastName());
					
					$mh->addParameter('url', BASE_URL.Loader::helper('navigation')->getLinkToCollection($p));
					$mh->addParameter('name', $p->getCollectionName());
					$mh->addParameter('description', $p->getCollectionDescription());
					$mh->addParameter('parent', BASE_URL.Loader::helper('navigation')->getLinkToCollection($parent));
					
					$mh->load('new_blog_post', 'problog');
					
					$mh->setSubject('New Blog Post @'.str_replace('www', '',$_SERVER['SERVER_NAME']).'!');
					$mh->sendMail();
				}
				$sent = true;
			}
		}
	}

}
