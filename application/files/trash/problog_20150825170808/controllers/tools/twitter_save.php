<?php  
namespace Concrete\Package\Problog\Controller\Tools;

use URL;
use Package;
use \Concrete\Core\Controller\Controller as RouteController;
use \Symfony\Component\HttpFoundation\Session\Session as SymfonySession;

use TwitterOAuth;

class TwitterSave extends RouteController
{
    public function save()
    {
        $pkg = Package::getByHandle('problog');
        $PB_APP_KEY = $pkg->getConfig()->get('api.twitter_app_key', false);
        $PB_APP_SECRET = $pkg->getConfig()->get('api.twitter_app_secret', false);

        $session = new SymfonySession();
        /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
        $connection = new TwitterOAuth($PB_APP_KEY, $PB_APP_SECRET, $session->get('problog.twitter.oauth_token'), $session->get('problog.twitter.oauth_token_secret'));

        /* Request access tokens from twitter */
        $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

        /* Save the access tokens. Normally these would be saved in a database for future use. */
        $session->set('problog.twitter.access_token',$access_token);

        /* Remove no longer needed request tokens */
        $session->remove('problog.twitter.oauth_token');
        $session->remove('problog.twitter.oauth_token_secret');

        $pkg = Package::getByHandle('problog');
        $pkg->getConfig()->save('api.twitter_auth_token', $access_token['oauth_token']);
        $pkg->getConfig()->save('api.twitter_auth_secret', $access_token['oauth_token_secret']);

        header("Location: ".URL::to('dashboard/problog/settings/'));
        die();
    }

}
