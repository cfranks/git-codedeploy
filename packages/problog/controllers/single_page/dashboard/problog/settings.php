<?php  
namespace Concrete\Package\Problog\Controller\SinglePage\Dashboard\Problog;

use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Core\Page\Page as Page;
use \Concrete\Core\Page\Template as PageTemplate;
use \Concrete\Core\Permission\Key\AddSubpagePageKey as AddSubpagePageKey;
use \Symfony\Component\HttpFoundation\Session\Session as SymfonySession;
use Request;
use Package;
use Loader;

class Settings extends DashboardPageController
{

    protected $stTable = 'btProBlogSettings';

    public function view()
    {
        $db = Loader::db();
        $r = $db->query("SELECT * FROM btProBlogSettings");
        while ($row=$r->fetchrow()) {
            $this->set('tweet' , $row['tweet']);
            $this->set('google' , $row['google']);
            $this->set('fb_like', $row['fb_like']);
            $this->set('addthis', $row['addthis']);
            $this->set('sharethis', $row['sharethis']);
            $this->set('embedly', $row['embedly']);
            $this->set('alchemy', $row['alchemy']);
            $this->set('author', $row['author']);
            $this->set('comments', $row['comments']);
            $this->set('trackback', $row['trackback']);
            $this->set('canonical', $row['canonical']);
            $this->set('search_path', $row['search_path']);
            $this->set('mobile_path', $row['mobile_path']);
            $this->set('disqus', $row['disqus']);
            $this->set('icon_color', $row['icon_color']);
            $this->set('thumb_width', $row['thumb_width']);
            $this->set('thumb_height', $row['thumb_height']);
            $this->set('ctID',$row['ctID']);
            $this->set('breakSyntax',$row['breakSyntax']);
            $this->set('pageTemplate',$row['pageTemplate']);
        }

        $this->set('pageTemplates', $this->getPageTemplates() );

//        $request = \Request::getInstance();
//        $session = $request->getSession();
//        if(!$session) {
//            $session = new SymfonySession();
//        }
        $this->set('session', \Core::make('session'));

    }

    public function save()
    {
        $pkg = Package::getByHandle('problog');

        $args= array(
            'tweet'=>$this->post('tweet'),
            'google'=>$this->post('google'),
            'fb_like'=>$this->post('fb_like'),
            'addthis'=>$this->post('addthis'),
            'sharethis'=> (strlen($this->post('sharethis'))>32) ? $this->post('sharethis') : '',
            'embedly'=>$this->post('embedly'),
            'alchemy'=>$this->post('alchemy'),
            'author'=>$this->post('author'),
            'comments'=>$this->post('comments'),
            'trackback'=>$this->post('trackback'),
            'canonical'=>$this->post('canonical'),
            'search_path'=>$this->post('search_path'),
            'mobile_path'=>$this->post('mobile_path'),
            'disqus'=>$this->post('disqus'),
            'icon_color'=>$this->post('icon_color'),
            'thumb_width'=>$this->post('thumb_width'),
            'thumb_height'=>$this->post('thumb_height'),
            'ctID'=>$this->post('ctID'),
            'breakSyntax'=>$this->post('breakSyntax'),
            'pageTemplate'=>$this->post('pageTemplate')
        );

        $db= Loader::db();

        $db->execute("DELETE from btProBlogSettings");

        $db->insert('btProBlogSettings',$args);

        $this->redirect('/dashboard/problog/settings/', 'view');
    }

    public function clear_twitter()
    {
        $pkg = Package::getByHandle('problog');
        $pkg->getConfig()->save('api.twitter_auth_token', '');
        $pkg->getConfig()->save('api.twitter_auth_secret', '');
        $this->view();
    }

    public function getPageTemplates()
    {
        $ctArray = PageTemplate::getList('');
        $pageTemplates = array();
        foreach ($ctArray as $ct) {
            $pms = new AddSubpagePageKey($ct);
            $pp = $pms->validate();
            if ($pp) {
                $pageTemplates[$ct->getPageTemplateID()] = $ct->getPageTemplateName();
            }
        }
        return $pageTemplates;
    }

}
