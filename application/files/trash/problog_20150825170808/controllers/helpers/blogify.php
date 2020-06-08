<?php  namespace Concrete\Package\Problog\Controller\Helpers;

use Page;
use User;
use UserInfo;
use \Concrete\Core\Page\Type\Type as CollectionType;
use \Concrete\Core\Block\BlockType\BlockType as BlockType;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use \Concrete\Package\Problog\Src\Models\ProblogList;
use \Concrete\Core\Page\PageList;
use Loader;

class Blogify
{
    public function __construct()
    {
    }

    public function getCanonicalParent($date=null,$blog)
    {
        $canonical_path = $blog->getCollectionPath();
        $path_break = explode('/',$canonical_path);

        if (strlen($path_break[count($path_break)-2]) > 2) {
            $path = $canonical_path;
            $path= substr($path,0,strrpos($path, "/")).'/';
        } else {
            if ($path_break[count($path_break)-4]) {
                $par_pos = explode($path_break[count($path_break)-4],$canonical_path);
                $path = $par_pos[0].$path_break[count($path_break)-4].'/';
            }
        }

        if (!$path) {
            return 1;
        } else {
            return Page::getByPath($path)->getCollectionID();
        }

    }

    public function getOrCreateCanonical($date,$parent)
    {
        $db=Loader::db();
        $canonical = $db->getOne("SELECT canonical FROM btProBlogSettings");
        if ($canonical) {
            $year = date('Y',strtotime($date));
            $month = date('m',strtotime($date));
            $month_name = date('M',strtotime($date));

            $pageType = CollectionType::getByHandle('pb_post');
            if (!is_object($pageType) || $pageType==false) {
                 $pageType = CollectionType::getByHandle('left_sidebar');
             }
             if (!is_object($pageType) || $pageType==false) {
                 $pageType= CollectionType::getByHandle('right_sidebar');
             }
            if (!is_object($pageType) || $pageType==false) {
                 $pageType = CollectionType::getByHandle('default');
             }
            if (!is_object($pageType) || $pageType==false) {
                 $pageType = CollectionType::getByHandle('page');
             }

            $canonical_year = Page::getByPath($parent->getCollectionPath().'/'.$year.'/');

            if (!$canonical_year->cID) {

                $canonical_year = $parent->add($pageType, array('cName' => $year, 'cHandle' => $year,'cDescription'=>$year.t(' Blog Posts')));
                $canonical_year->setAttribute('exclude_page_list',1);
                $canonical_year->setAttribute('exclude_search_index',1);

                $bt = BlockType::getByHandle('problog_list');
                $cParentID = $canonical_year->getCollectionID();

                $data = array(
                'num' => '10',
                'cParentID'=>$cParentID,
                'cThis'=>'0',
                'paginate'=>'1',
                'displayAliases'=>'1',
                'ctID'=> $pageType->getPageTypeID(),
                'rss'=>'1',
                'rssTitle'=>t('Latest blog'),
                'orderBy'=>'chrono_desc',
                'rssDescription'=>t('Our latest blog feed'),
                'truncateSummaries'=>'0',
                'truncateChars'=>'128',
                'category'=>t('All Categories'),
                'title'=>$year.t(' Blog Posts')
                );

                $b = $canonical_year->addBlock($bt, 'Main', $data);
                $b->setCustomTemplate('templates/micro_blog');

                $block = $canonical_year->getBlocks('Sidebar');
                foreach ($block as $b) {
                    $b->delete();
                }

                $i = 0;
                for ($bb=1;$bb<=4;$bb+=1) {

                    if ($bb==1) {
                        $title=t('Category List');
                    } elseif ($bb==2) {
                        $title=t('Tag List');
                    } elseif ($bb==3) {
                        $title=t('Tag Cloud');
                    } elseif ($bb==4) {
                        $title=t('Archive');
                    }

                    $data = array('num' => '25',
                    'cParentID'=>$cParentID,
                    'cThis'=>'0',
                    'paginate'=>'0',
                    'displayAliases'=>'0',
                    'ctID'=>$pageType->getPageTypeID(),
                    'rss'=>'0',
                    'rssTitle'=>'',
                    'rssDescription'=>'',
                    'truncateSummaries'=>'0',
                    'truncateChars'=>'128',
                    'category'=>t('All Categories'),
                    'title'=>$title
                    );

                    $b = $canonical_year->addBlock($bt, 'Sidebar', $data);

                    $i++;
                    if ($i==1) {
                        $b->setCustomTemplate('categories');
                    } elseif ($i==2) {
                        $b->setCustomTemplate('tags');
                    } elseif ($i==3) {
                        $b->setCustomTemplate('tag_cloud');
                    } elseif ($i==4) {
                        $b->setCustomTemplate('archive');
                    }
                }
            }

            $canonical_month = Page::getByPath($canonical_year->getCollectionPath().'/'.$month.'/');

            if (!$canonical_month->cID) {

                $canonical_month = $canonical_year->add($pageType, array('cName' => $month, 'cHandle' => $month,'cDescription'=>$month_name.', '.$year.t(' Blog Posts')));

                $canonical_month->setAttribute('exclude_page_list',1);
                $canonical_month->setAttribute('exclude_search_index',1);

                $bt = BlockType::getByHandle('problog_list');
                $cParentID = $canonical_month->getCollectionID();

                $data = array(
                'num' => '10',
                'cParentID'=>$cParentID,
                'cThis'=>'0',
                'paginate'=>'1',
                'displayAliases'=>'1',
                'ctID'=> $pageType->getPageTypeID(),
                'rss'=>'1',
                'rssTitle'=>t('Latest blog'),
                'orderBy'=>'chrono_desc',
                'rssDescription'=>t('Our latest blog feed'),
                'truncateSummaries'=>'0',
                'truncateChars'=>'128',
                'category'=>t('All Categories'),
                'title'=>$month.t(' Blog Posts')
                );

                $b = $canonical_month->addBlock($bt, 'Main', $data);
                $b->setCustomTemplate('templates/micro_blog');

                $block = $canonical_month->getBlocks('Sidebar');
                foreach ($block as $b) {
                    $b->delete();
                }

                $i = 0;
                for ($bb=1;$bb<=4;$bb+=1) {

                    if ($bb==1) {
                        $title=t('Category List');
                    } elseif ($bb==2) {
                        $title=t('Tag List');
                    } elseif ($bb==3) {
                        $title=t('Tag Cloud');
                    } elseif ($bb==4) {
                        $title=t('Archive');
                    }

                    $data = array('num' => '25',
                    'cParentID'=>$cParentID,
                    'cThis'=>'0',
                    'paginate'=>'0',
                    'displayAliases'=>'0',
                    'ctID'=>$pageType->getPageTypeID(),
                    'rss'=>'0',
                    'rssTitle'=>'',
                    'rssDescription'=>'',
                    'truncateSummaries'=>'0',
                    'truncateChars'=>'128',
                    'category'=>t('All Categories'),
                    'title'=>$title
                    );

                    $b = $canonical_month->addBlock($bt, 'Sidebar', $data);

                    $i++;
                    if ($i==1) {
                        $b->setCustomTemplate('categories');
                    } elseif ($i==2) {
                        $b->setCustomTemplate('tags');
                    } elseif ($i==3) {
                        $b->setCustomTemplate('tag_cloud');
                    } elseif ($i==4) {
                        $b->setCustomTemplate('archive');
                    }
                }
            }

            return Page::getByPath($parent->getCollectionPath().'/'.$year.'/'.$month.'/');
        } else {
            return $parent;
        }
    }

    public function getBlogSettings()
    {
        $db=Loader::db();

        $pp=$db->QUERY("SELECT * FROM btProBlogSettings LIMIT 1");

        while ($row=$pp->FetchRow()) {
            $blogSettings=$row;
        }

        return $blogSettings;
    }

    public function getPosterAvatar($uID,$maxWidth=null)
    {
        $db=Loader::db();
        if ($uID) {
            $ui=UserInfo::getByID($uID);
            $ih=Loader::helper('image');
            $av=Loader::helper('concrete/avatar');

            if ($ui->hasAvatar()) {
                $avatarImgPath=$av->getImagePath($ui,false);
                $mw=($maxWidth) ? $maxWidth : '60';
                $mh=($maxHeight) ? $maxHeight : '80';
                $avatarHTML='<img src="'.$avatarImgPath.'"/>';
            }
        }

        return $avatarHTML;
    }

    public function getNewCommentCount($cID)
    {
        $settings = static::getBlogSettings();
        if (!$settings['disqus']) {
            $db=Loader::db();
            $count = $db->getOne("SELECT cnvMessagesTotal from Conversations WHERE cID=$cID ORDER BY cnvID DESC");

            if ($count!=1) {$s = 's';}

            return $count.' '.t('comment').$s;
        } else {
            //var_dump($settings['disqus']);
            loader::model('page');
            $cobj = Page::getByID($cParentID);
            $link = Loader::helper('navigation')->getLinkToCollection($cobj);
            $html = '<a href="'.BASE_URL.$link.'#disqus_thread">Comments</a>';
            $html .= '<script type="text/javascript">
/*<![CDATA[*/
	/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
	//var disqus_developer = 1; // developer mode is on
    var disqus_shortname = \''.$settings['disqus'].'\'; // required: replace example with your forum shortname

    /* * * DON\'T EDIT BELOW THIS LINE * * */
    (function () {
        var s = document.createElement(\'script\'); s.async = true;
        s.type = \'text/javascript\';
        s.src = \'http://\' + disqus_shortname + \'.disqus.com/count.js\';
        (document.getElementsByTagName(\'HEAD\')[0] || document.getElementsByTagName(\'BODY\')[0]).appendChild(s);
    }());
/*]]>*/
</script>';

            return $html;
        }
    }

    public function getBlogCats()
    {
            $db = Loader::db();
            $akID = $db->query("SELECT akID FROM AttributeKeys WHERE akHandle = 'blog_category'");
            while ($row=$akID->fetchrow()) {
                $akIDc = $row['akID'];
            }
            $akv = $db->execute("SELECT value FROM atSelectOptions WHERE akID = $akIDc");
            while ($row=$akv->fetchrow()) {
                $values[]=$row;
            }
            if (empty($values)) {
                $values = array();
            }

            return $values;
        }


    public function getBlogAuthorInfo($ui)
    {
        $authorInfo['aboutBio'] = $ui->getUserUserBio();
        $authorInfo['firstName'] = $ui->getUserFirstName();
        $authorInfo['lastName'] = $ui->getUserLastName();
        $authorInfo['uName'] = $ui->getUserUname();
        $authorInfo['uEmail'] = $ui->getUserUemail();
        $authorInfo['uLocation'] = $ui->getUserUlocation();

        return $authorInfo;
    }

    public function getBlogAuthor($c)
    {
        $authorID = $c->getAttribute('blog_author');
        if (!$authorID) {
            $authorID = $c->getCollectionUserID();
        }
        //if editing via page_type defaults, set bogus author
        if (!$authorID) {
            $authorID = '1';
        }

        return $authorID;
    }

    public function getBlogVars($c)
    {
        Loader::model("attribute/categories/collection");

        //load settings
        $blog_settings = static::getBlogSettings();
        //set search path
        $vars['search'] = Loader::helper('navigation')->getLinkToCollection(Page::getByPath('/blogsearch')).'/';

        //link url to page
        $vars['url'] = Loader::helper('navigation')->getLinkToCollection($c);

        //this pages collection ID
        $vars['cID'] = $c->getCollectionID();

        //set user
        $vars['u'] = new User();

        //get post title
        $vars['blogTitle'] = $c->getCollectionName();
        //get post public date
        $vars['blogDate'] = $c->getCollectionDatePublic();

        //get author
        $vars['authorID'] = $c->getAttribute('blog_author');
        if (!$vars['$authorID']) {
            $vars['authorID'] = $c->getCollectionUserID();
        }
        //if editing via page_type defaults, set bogus author
        if (!$vars['$authorID']) {
            $vars['authorID'] = '1';
        }
        //grab the user info object
        $vars['ui'] = UserInfo::getByID($vars['authorID']);

        //get tags
        $vars['tags'] = $c->getAttribute('tags');

        $ak_t = CollectionAttributeKey::getByHandle('tags');
        $vars['tag_list'] = $c->getCollectionAttributeValue($ak_t);

        //get category
        $vars['cat'] = $c->getAttribute('blog_category');

        //comment count
        $vars['comment_count'] = static::getNewCommentCount($c->getCollectionID());

        //thumbnail image
        $imgHelper = Loader::helper('image');
        $thumb = $c->getAttribute('thumbnail');
        $vars['thumb'] = $thumb;
        if ($thumb) {
            $vars['image'] = $imgHelper->getThumbnail($thumb, $blog_settings['thumb_width'],$blog_settings['thumb_height'])->src;
        }

        $vars['prev_link'] = $this->getPrevPost($c);
        $vars['next_link'] = $this->getNextPost($c);

        return $vars;
    }

    //clean up tags function
    public function closetags($html)
    {
        preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedtags = $result[1];
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        for ($i=0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= '</'.$openedtags[$i].'>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }
        //$mdsh = Loader::helper('magic_data_symbols', 'problog');
        //$html = $mdsh->fill($html);

        return $html;
    }


    public function getNextPost($c){

        $cID = $c->getCollectionID();
        
        $this->loadblogSections();
        
        $pl = new ProblogList();
        $pl->setItemsPerPage(1);
        $pl->filter(false,"cv.cID > $cID");
        $pl->filter(false,"(CHAR_LENGTH(cv.cvName) > 4 OR cv.cvName NOT REGEXP '^[0-9]')");
        $pl->filterByPublicDate(date('Y-m-d H:i:s'),'<=');
        
        $sections = $this->sections;
        $keys = array_keys($sections);
        if(is_array($keys)){
            foreach($keys as $id){
                if($fs){$fs .= ' OR ';}
                $path = Page::getByID($id)->getCollectionPath().'/';
                $fs .= "pp.cPath LIKE '$path%'";
            }
            $pl->filter(false,"($fs)");
        }
        //$pl->debug();
        $posts = $pl->get();
        
        $np = $posts[0];

        if($np){
            return Loader::helper('navigation')->getLinkToCollection($np);
        }
    }
    
    public function getPrevPost($c){

        $cID = $c->getCollectionID();
              
        $this->loadblogSections();
        
        $pl = new ProblogList();
        $pl->setItemsPerPage(1);
        $pl->filter(false,"cv.cID < $cID");
        $pl->filter(false,"(CHAR_LENGTH(cv.cvName) > 4 OR cv.cvName NOT REGEXP '^[0-9]')");
        $pl->filterByPublicDate(date('Y-m-d H:i:s'),'<=');
        
        $sections = $this->sections;
        $keys = array_keys($sections);
        if(is_array($keys)){
            foreach($keys as $id){
                if($fs){$fs .= ' OR ';}
                $path = Page::getByID($id)->getCollectionPath().'/';
                $fs .= "pp.cPath LIKE '$path%'";
            }
            $pl->filter(false,"($fs)");
        }
        
        $posts = $pl->get();
        
        $np = $posts[0];

        if($np){
            return Loader::helper('navigation')->getLinkToCollection($np);
        }

    }

    protected function loadblogSections() {
        $blogSectionList = new PageList();
        $blogSectionList->setItemsPerPage($this->num);
        $blogSectionList->filterByBlogSection(1);
        $blogSectionList->sortBy('cvName', 'asc');
        $tmpSections = $blogSectionList->get();
        $sections = array();
        foreach($tmpSections as $_c) {
            $sections[$_c->getCollectionID()] = $_c->getCollectionName();
        }
        $this->sections = $sections;
    }
}
