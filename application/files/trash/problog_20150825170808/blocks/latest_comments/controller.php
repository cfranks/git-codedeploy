<?php  
namespace Concrete\Package\Problog\Block\LatestComments;

use \Concrete\Core\Block\BlockController;
use Loader;
use Package;
use View;

class Controller extends BlockController
{

    protected $btTable = 'btLatestComments';
    protected $btInterfaceWidth = "300";
    protected $btInterfaceHeight = "150";

    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;

    public function getBlockTypeDescription()
    {
        return t("List of Latest Comments");
    }

    public function getBlockTypeName()
    {
        return t("Latest Comments");
    }

    public function view()
    {
        if ($this->maxItems==0) {
            $this->maxItems='none';
        }
        $entries=$this->getAllEntries($this->maxItems);
        $this->set('entries', $entries);
    }

    public function save($args)
    {
        $args['maxItems'] = ($args['maxItems'] === '') ? 0 : intval($args['maxItems']);
        parent::save($args);
    }

    public function getAllEntries($limit='none')
    {
        $db = Loader::db();
        if ($limit=='none') {
            $q="SELECT * FROM ConversationMessages cnvm LEFT JOIN Conversations cnv ON cnvm.cnvID = cnv.cnvID WHERE cnvm.cnvIsMessageApproved=1 ORDER BY cnvm.cnvMessageDateCreated DESC";
        } else {
            $limit = intval($limit);
            $q="SELECT * FROM ConversationMessages cnvm LEFT JOIN Conversations cnv ON cnvm.cnvID = cnv.cnvID WHERE cnvm.cnvIsMessageApproved=1 ORDER BY cnvm.cnvMessageDateCreated DESC LIMIT 0, $limit";
        }

        $rows = $db->getAll($q);

        $pkg = Package::getByHandle('problog');
        if ($pkg) {
            //grab problog settings
            //if the Disqus sitename is set
            //use disqus
            $blogify = Loader::helper('blogify');
            $settings = $blogify->getBlogSettings();
            if ($settings['disqus']) {
                return '<div id="recentcomments" class="dsq-widget"><h2 class="dsq-widget-title">Latest Comments</h2><script type="text/javascript" src="https://'.$settings['disqus'].'.disqus.com/recent_comments_widget.js?num_items='.$limit.'&hide_avatars=0&avatar_size=22&excerpt_length=100"></script></div><a href="https://disqus.com/">'.t('Powered by Disqus').'</a>';
            } else {
                return $rows;
            }
        } else {
            return $rows;
        }
    }
}
