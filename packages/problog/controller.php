<?php   namespace Concrete\Package\Problog;

use BlockType;
use Core;
use URL;
use Events;
use Loader;
use Package;
use Page;
use Route;
use Request;
use SinglePage;
use Log;
use User;

use Symfony\Component\ClassLoader\Psr4ClassLoader;
use \Concrete\Core\Foundation\ModifiedPSR4ClassLoader as SymfonyClassloader;
use \Symfony\Component\ClassLoader\MapClassLoader as SymfonyMapClassloader;

use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use \Concrete\Core\Attribute\Key\UserKey as UserAttributeKey;
use \Concrete\Core\Attribute\Set as AttributeSet;
use \Concrete\Core\Attribute\Type as AttributeType;
use \Concrete\Core\Page\Template;
use \Concrete\Core\Page\Type\Composer\Control\Type\Type as PageTypeComposerControlType;
use \Concrete\Core\Page\Type\Composer\OutputControl as OutputControl;
use \Concrete\Core\Page\Type\Composer\FormLayoutSet as FormLayoutSet;
use \Concrete\Core\Page\Type\Composer\FormLayoutSetControl as FormLayoutSetControl;
use \Concrete\Core\Page\Type\PublishTarget\Type\Type as PublishTarget;
use \Concrete\Core\Page\Type\Type as CollectionType;
use \Concrete\Package\Problog\Src\Page\Type\PublishTarget\Configuration\BlogSectionConfiguration;

class Controller extends Package
{

    protected $pkgHandle = 'problog';
    protected $appVersionRequired = '5.7.3';
    protected $pkgVersion = '2.4.0';

    public function getPackageDescription()
    {
        return t("A professional Blogging package");
    }

    public function getPackageName()
    {
        return t("ProBlog");
    }

    public function install()
    {

        $pkg = parent::install();

        //install blocks
        BlockType::installBlockTypeFromPackage('problog_list', $pkg);
        BlockType::installBlockTypeFromPackage('latest_comments', $pkg);
        BlockType::installBlockTypeFromPackage('problog_date_archive', $pkg);
        $bt = BlockType::getByHandle('related_pages');
        if (!$bt) {
            BlockType::installBlockTypeFromPackage('related_pages', $pkg);
        }

        // install pages
        $iak = CollectionAttributeKey::getByHandle('icon_dashboard');

        $pbp = SinglePage::add('/dashboard/problog', $pkg);
        $pbp->update(array('cName' => t('ProBlog'), 'cDescription' => t('Blog Management')));

        $pbl = SinglePage::add('/dashboard/problog/blog_list', $pkg);
        $pbl = Page::getByPath('/dashboard/problog/blog_list');
        $pbl->setAttribute($iak, 'icon-list-alt');

        $pba = SinglePage::add('/dashboard/problog/add_blog', $pkg);
        $pba->update(array('cName' => t('Add/Edit')));
        $pba = Page::getByPath('/dashboard/problog/add_blog');
        $pba->setAttribute($iak, 'icon-pencil');

        $pbc = SinglePage::add('/dashboard/problog/comments', $pkg);
        $pbc = Page::getByPath('/dashboard/problog/comments');
        $pbc->setAttribute($iak, 'icon-comment');

        $pbs = SinglePage::add('/dashboard/problog/settings', $pkg);
        $pbs = Page::getByPath('/dashboard/problog/settings');
        $pbs->setAttribute($iak, 'icon-wrench');

        $tags = Page::getByPath('/blogsearch');
        if (!is_object($tags) || $tags->cID == null) {
            $tags = SinglePage::add('/blogsearch', $pkg);
            $tags->setAttribute('exclude_nav', 1);
        }

        /**
         * To be Added back at some point when
         * image/file att form gets fixed.
         */
        // $draft = Page::getByPath('/account/publish');
        // if (!is_object($draft) || $draft->cID == null) {
        //     $draft = SinglePage::add('/account/publish', $pkg);
        //     $draft->update(array('cName' => t('Blog'), 'cDescription' => t('Blog Management')));
        // }

        $this->install_pb_attributes($pkg);
        $this->add_blog_page($pkg);
        $this->install_pb_pages($pkg);
        $this->install_pb_user_attributes($pkg);
        $this->install_pb_settings($pkg);

    }

    public function uninstall()
    {
        parent::uninstall();
    }

    public function upgrade()
    {

        $db = Loader::db();

        $pkg = Package::getByHandle('problog');
        
        $eaku = AttributeKeyCategory::getByHandle('collection');
        
        $multiuserpicker = AttributeType::getByHandle('multi_user_picker');
        if (!is_object($multiuserpicker) || !intval($multiuserpicker->getAttributeTypeID())) {
            $multiuserpicker = AttributeType::add('multi_user_picker', tc('AttributeTypeName', 'Multi User Picker'), $pkg);
            $eaku->associateAttributeKeyType($multiuserpicker);
            
            $users = CollectionAttributeKey::getByHandle('subscription'); 
			if( !is_object($users) ) {
			 	$users = array(
					'akHandle' => 'subscription',
					'akName' => 'Subscribed Members',
					'akIsSearchable' => 0,
					'akIsSearchableIndexed' => 0,				
					'akIsAutoCreated' => 1,
					'akIsEditable' => 1
				);
				$users = CollectionAttributeKey::add($multiuserpicker,$users,$pkg);
			}
        }
        
        $subscribe = AttributeType::getByHandle('subscribe');
        if (!is_object($subscribe) || !intval($subscribe->getAttributeTypeID())) {
            $subscribe = AttributeType::add('subscribe', tc('AttributeTypeName', 'Subscribe'), $pkg);
            $eaku->associateAttributeKeyType($subscribe);
            
            $evset = AttributeSet::getByHandle('problog');
            
            $send_subscribe = CollectionAttributeKey::getByHandle('send_subscription');
	        if (!is_object($send_subscribe)) {
	            CollectionAttributeKey::add($subscribe,
	                array('akHandle' => 'send_subscription',
	                    'akName' => t('Send To Subscribers'),
	                ), $pkg)->setAttributeSet($evset);
	        }
            
            $page_att_controls = PageTypeComposerControlType::getByHandle('collection_attribute');
            $type = CollectionType::getByHandle('pb_post');
			$lsl = FormLayoutSet::getList($type);
			foreach($lsl as $ls){
				if($ls->ptComposerFormLayoutSetName == 'Post Options'){
					$options = $ls;
				}
			}
            /* Send to subscribers */
	        $control_id = CollectionAttributeKey::getByHandle('send_subscription')->getAttributeKeyID();
	        $send_subscribe = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
	        $send_subscribe->addToPageTypeComposerFormLayoutSet($options);
        }

        parent::upgrade();

    }

    public function install_pb_attributes($pkg)
    {

        $eaku = AttributeKeyCategory::getByHandle('collection');
        $eaku->setAllowAttributeSets(AttributeKeyCategory::ASET_ALLOW_SINGLE);
        $evset = $eaku->addSet('problog', t('ProBlog'), $pkg);

        $bset = AttributeSet::getByHandle('problog_additional_attributes');
        if (!is_object($bset)) {
            $bset = $eaku->addSet('problog_additional_attributes', t('ProBlog Additional Attributes'), $pkg);
        }

        $userpicker = AttributeType::getByHandle('user_picker');
        if (!is_object($userpicker) || !intval($userpicker->getAttributeTypeID())) {
            $userpicker = AttributeType::add('user_picker', tc('AttributeTypeName', 'User Picker'), $pkg);
            $eaku->associateAttributeKeyType($userpicker);
        }
        
        $multiuserpicker = AttributeType::getByHandle('multi_user_picker');
        if (!is_object($multiuserpicker) || !intval($multiuserpicker->getAttributeTypeID())) {
            $multiuserpicker = AttributeType::add('multi_user_picker', tc('AttributeTypeName', 'Multi User Picker'), $pkg);
            $eaku->associateAttributeKeyType($multiuserpicker);
        }

        $posttotwitter = AttributeType::getByHandle('post_to_twitter');
        if (!is_object($posttotwitter) || !intval($posttotwitter->getAttributeTypeID())) {
            $posttotwitter = AttributeType::add('post_to_twitter', tc('AttributeTypeName', 'Post To Twitter'), $pkg);
            $eaku->associateAttributeKeyType($posttotwitter);
        }
        
        $subscribe = AttributeType::getByHandle('subscribe');
        if (!is_object($subscribe) || !intval($subscribe->getAttributeTypeID())) {
            $subscribe = AttributeType::add('subscribe', tc('AttributeTypeName', 'Subscribe'), $pkg);
            $eaku->associateAttributeKeyType($subscribe);
        }

        $composeroptmizer = AttributeType::getByHandle('composer_optimizer');
        if (!is_object($composeroptmizer) || !intval($composeroptmizer->getAttributeTypeID())) {
            $composeroptmizer = AttributeType::add('composer_optimizer', tc('AttributeTypeName', 'ProBlog Optimizer'), $pkg);
            $eaku->associateAttributeKeyType($composeroptmizer);
        }

        $blogauth = CollectionAttributeKey::getByHandle('blog_author');
        if (!is_object($blogauth)) {
            CollectionAttributeKey::add($userpicker,
                array('akHandle' => 'blog_author',
                    'akName' => t('Blog Author')
                ), $pkg)->setAttributeSet($evset);
        }

        $checkn = AttributeType::getByHandle('boolean');
        $blogsec = CollectionAttributeKey::getByHandle('blog_section');
        if (!is_object($blogsec)) {
            CollectionAttributeKey::add($checkn,
                array('akHandle' => 'blog_section',
                    'akName' => t('Blog Section'),
                    'akIsSearchable' => 1,
                    'akIsSearchableIndexed' => 1,
                ), $pkg)->setAttributeSet($evset);
        }

        $pulln = AttributeType::getByHandle('select');
        $blogcat = CollectionAttributeKey::getByHandle('blog_category');
        if (!is_object($blogcat)) {
            CollectionAttributeKey::add($pulln,
                array('akHandle' => 'blog_category',
                    'akName' => t('Blog Category'),
                    'akIsSearchable' => 1,
                    'akIsSearchableIndexed' => 1,
                    'akSelectAllowOtherValues' => true
                ), $pkg)->setAttributeSet($evset);
        }

        $blogtag = CollectionAttributeKey::getByHandle('tags');
        if (!is_object($blogtag)) {
            CollectionAttributeKey::add($pulln,
                array('akHandle' => 'tags',
                    'akName' => t('Tags'),
                    'akIsSearchable' => 1,
                    'akIsSearchableIndexed' => 1,
                    'akSelectAllowMultipleValues' => true,
                    'akSelectAllowOtherValues' => true
                ), $pkg)->setAttributeSet($evset);
        } else {
            $blogtag->update(array('akHandle' => 'tags',
                'akName' => t('Tags'),
                'akIsSearchable' => 1,
                'akIsSearchableIndexed' => 1,
                'akSelectAllowMultipleValues' => true,
                'akSelectAllowOtherValues' => true
            ));
        }

        $imagen = AttributeType::getByHandle('image_file');
        $blogthum = CollectionAttributeKey::getByHandle('thumbnail');
        if (!is_object($blogthum)) {
            CollectionAttributeKey::add($imagen,
                array('akHandle' => 'thumbnail',
                    'akName' => t('Thumbnail Image'),
                ), $pkg)->setAttributeSet($evset);
        }
        
        $send_subscribe = CollectionAttributeKey::getByHandle('send_subscription');
        if (!is_object($send_subscribe)) {
            CollectionAttributeKey::add($subscribe,
                array('akHandle' => 'send_subscription',
                    'akName' => t('Send To Subscribers'),
                ), $pkg)->setAttributeSet($evset);
        }

        $postBlogToTwitter = CollectionAttributeKey::getByHandle('post_to_twitter');
        if (!is_object($postBlogToTwitter)) {
            CollectionAttributeKey::add($posttotwitter,
                array('akHandle' => 'post_to_twitter',
                    'akName' => t('Post To Twitter'),
                ), $pkg)->setAttributeSet($evset);
        }

        $composerBlogOptimizer = CollectionAttributeKey::getByHandle('composer_optimizer');
        if (!is_object($composerBlogOptimizer)) {
            CollectionAttributeKey::add($composeroptmizer,
                array('akHandle' => 'composer_optimizer',
                    'akName' => t('ProBlog Optimizer'),
                ), $pkg)->setAttributeSet($evset);
        }
        
        $users = CollectionAttributeKey::getByHandle('subscription'); 
	  	if( !is_object($users) ) {
		 	$users = array(
				'akHandle' => 'subscription',
				'akName' => 'Subscribed Members',
				'akIsSearchable' => 0,
				'akIsSearchableIndexed' => 0,				
				'akIsAutoCreated' => 1,
				'akIsEditable' => 1
			);
			$users = CollectionAttributeKey::add($multiuserpicker,$users,$pkg);
		}

    }

    public function add_blog_page($pkg)
    {

        /*
         * Add new Post template
         */
        //$tmplt = Template::add('pb_post', 'ProBlog Post', 'right_sidebar.png', $pkg);
        $tmplt = Template::getByHandle('right_sidebar');

        /*
         * Add new Post Page Type using new Template
         */

        $setBlogAt = Page::getByPath('/blog');

        $type = CollectionType::add(
            array(
                'handle' => 'pb_post',
                'name' => 'ProBlog Post',
                'defaultTemplate' => $tmplt,
                'allowedTemplates' => 'C',
                'templates' => array($tmplt),
                'ptLaunchInComposer' => 1,
                'ptIsFrequentlyAdded' => 1
            ),
            $pkg
        );

        /*
         * Add our new blog_section PublishTarget Type
         */
        $pt_target = PublishTarget::add('blog_section', 'Choose From Blog Section Pages', $pkg);
        $configuration = new BlogSectionConfiguration($pt_target);

        /*
         * Set Post Page Type to use Parent Page Configuration
         */
        $type->setConfiguredPageTypePublishTargetObject($configuration);

        /*
         * Create Post Page Type Form Layout Sets
         */
        $info = $type->addPageTypeComposerFormLayoutSet('General Info', 'Basic Blog Post Information');
        $post = $type->addPageTypeComposerFormLayoutSet('Post Content', 'Your Post Content');
        $metas = $type->addPageTypeComposerFormLayoutSet('Post Metas', 'Your Post Meta Data');
        $options = $type->addPageTypeComposerFormLayoutSet('Post Options', 'Your Post Options');

        /*
         * Create Post Page Type Form Layout Controls
         */
        $core_att_controls = PageTypeComposerControlType::getByHandle('core_page_property');
        $page_att_controls = PageTypeComposerControlType::getByHandle('collection_attribute');
        $block_controls = PageTypeComposerControlType::getByHandle('block');

        /*
         * ++++++++++++++++++++++
         * Info Tab
         * ++++++++++++++++++++++
         */

        /* Post Title */
        $name = $core_att_controls->getPageTypeComposerControlByIdentifier('name');
        $name->setPageTypeComposerControlName(t('Post Title'));
        $name->addToPageTypeComposerFormLayoutSet($info);

        /* Post Slug */
        $slug = $core_att_controls->getPageTypeComposerControlByIdentifier('url_slug');
        $slug->setPageTypeComposerControlName(t('Post Slug'));
        $slug->addToPageTypeComposerFormLayoutSet($info);

        /* Post Author */
        $control_id = CollectionAttributeKey::getByHandle('blog_author')->getAttributeKeyID();
        $blog_author = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $blog_author->addToPageTypeComposerFormLayoutSet($info);

        /* Post Thumbnail */
        $control_id = CollectionAttributeKey::getByHandle('thumbnail')->getAttributeKeyID();
        $thumbnail = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $thumbnail->addToPageTypeComposerFormLayoutSet($info);

        /* Post Description */
        $description = $core_att_controls->getPageTypeComposerControlByIdentifier('description');
        $description->addToPageTypeComposerFormLayoutSet($info);

        /*
         * ++++++++++++++++++++++
         * Post Tab
         * ++++++++++++++++++++++
         */

        /* Event Content */
        $control_id = BlockType::getByHandle('content')->getBlockTypeID();
        $post_content = $block_controls->getPageTypeComposerControlByIdentifier($control_id);
        $post_content->addToPageTypeComposerFormLayoutSet($post);

        /*
         * ++++++++++++++++++++++
         * Metas Tab
         * ++++++++++++++++++++++
         */
        /* Meta Title */
       $control_id = CollectionAttributeKey::getByHandle('meta_title')->getAttributeKeyID();
       $meta_title = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
       $meta_title->addToPageTypeComposerFormLayoutSet($metas);

       /* Meta Description */
       $control_id = CollectionAttributeKey::getByHandle('meta_description')->getAttributeKeyID();
       $meta_description = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
       $meta_description->addToPageTypeComposerFormLayoutSet($metas);

       /* Meta Keywords */
       $control_id = CollectionAttributeKey::getByHandle('meta_keywords')->getAttributeKeyID();
       $meta_keywords = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
       $meta_keywords->addToPageTypeComposerFormLayoutSet($metas);

        /*
         * ++++++++++++++++++++++
         * Options Tab
         * ++++++++++++++++++++++
         */

        /* Post Location */
        $target = $core_att_controls->getPageTypeComposerControlByIdentifier('publish_target');
        $target->addToPageTypeComposerFormLayoutSet($options);

        /* Post Date */
        $public = $core_att_controls->getPageTypeComposerControlByIdentifier('date_time');
        $public->addToPageTypeComposerFormLayoutSet($options);

        /* Post Tags */
        $control_id = CollectionAttributeKey::getByHandle('tags')->getAttributeKeyID();
        $tags = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $tags->addToPageTypeComposerFormLayoutSet($options);

        /* Post Blog Category */
        $control_id = CollectionAttributeKey::getByHandle('blog_category')->getAttributeKeyID();
        $blog_category = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $blog_category->addToPageTypeComposerFormLayoutSet($options);
        
        /* Send to subscribers */
        $control_id = CollectionAttributeKey::getByHandle('send_subscription')->getAttributeKeyID();
        $send_subscribe = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $send_subscribe->addToPageTypeComposerFormLayoutSet($options);

        /* Post To Twitter */
        $control_id = CollectionAttributeKey::getByHandle('post_to_twitter')->getAttributeKeyID();
        $twitter = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $twitter->addToPageTypeComposerFormLayoutSet($options);

        /* Composer Optimize */
        $control_id = CollectionAttributeKey::getByHandle('composer_optimizer')->getAttributeKeyID();
        $composer_optimize = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $composer_optimize->addToPageTypeComposerFormLayoutSet($options);
        
        
        $db = Loader::db();
        $cocID = $db->getOne("SELECT ptComposerOutputControlID FROM PageTypeComposerOutputControls WHERE pTemplateID = ? AND ptID = ?",array($tmplt->getPageTemplateID(),$type->getPageTypeID()));
        
        $this->install_pb_page_defaults($pkg,$cocID);

    }

    public function install_pb_pages($pkg)
    {

        $db = Loader::db();

        $pageType = CollectionType::getByHandle('right_sidebar');
        if (!is_object($pageType) || $pageType == false) {
            $pageType = CollectionType::getByHandle('left_sidebar');
        }

        $setblogAt = Page::getByPath('/blog');

        if (!is_object($setblogAt) || !$setblogAt->cID) {
            $pageeventParent = Page::getByID(HOME_CID);
            $setblogAt = $pageeventParent->add($pageType, array('cName' => 'Blog', 'cHandle' => 'blog', 'pkgID' => $pkg->pkgID));
        }

        $tmplt = Template::getByHandle('right_sidebar');
        $setblogAt = Page::getByPath('/blog');
        $setblogAt->setAttribute('blog_section', true);
        $setblogAt->update(array('pTemplateID'=>$tmplt->getPageTemplateID()));

        $block = $setblogAt->getBlocks('Main');
        foreach ($block as $b) {
            $b->delete();
        }

        $block = $setblogAt->getBlocks('Sidebar');
        foreach ($block as $b) {
            $b->delete();
        }

        $pageType = CollectionType::getByHandle('pb_post');
        $ctID = $pageType->getPageTypeID();

        $bt = BlockType::getByHandle('problog_list');
        $cParentID = $setblogAt->getCollectionID();

        $data = array('num' => '10',
            'cParentID' => $cParentID,
            'cThis' => '0',
            'paginate' => '1',
            'displayAliases' => '1',
            'ctID' => $ctID,
            'rss' => '1',
            'rssTitle' => t('Latest blog'),
            'orderBy' => 'chrono_desc',
            'rssDescription' => t('Our latest blog feed'),
            'truncateSummaries' => '0',
            'truncateChars' => '128',
            'category' => t('All Categories'),
            'title' => t('Our Latest Blog Posts')
        );

        $b = $setblogAt->addBlock($bt, 'Main', $data);

        $i = 0;
        for ($bb = 1; $bb <= 4; $bb += 1) {

            if ($bb == 1) {
                $title = t('Category List');
            } elseif ($bb == 2) {
                $title = t('Tag List');
            } elseif ($bb == 3) {
                $title = t('Tag Cloud');
            } elseif ($bb == 4) {
                $title = t('Archive');
            }

            $data = array('num' => '25',
                'cParentID' => '0',
                'cThis' => '0',
                'paginate' => '0',
                'displayAliases' => '0',
                'ctID' => $ctID,
                'rss' => '0',
                'rssTitle' => '',
                'rssDescription' => '',
                'truncateSummaries' => '0',
                'truncateChars' => '128',
                'category' => t('All Categories'),
                'title' => $title,
            );

            $b = $setblogAt->addBlock($bt, 'Sidebar', $data);

            $i++;
            if ($i == 1) {
                $b->setCustomTemplate('categories');
            } elseif ($i == 2) {
                $b->setCustomTemplate('tags');
            } elseif ($i == 3) {
                $b->setCustomTemplate('tag_cloud');
            } elseif ($i == 4) {
                $b->setCustomTemplate('archive');
            }
        }

        $setblogAt->reindex();

        /////////////////////////
        //now we go add blocks to the /blogsearch singlepage
        ////////////////////////
        $setSearchAt = Page::getByPath('/blogsearch');

        $block = $setSearchAt->getBlocks('Main');
        foreach ($block as $b) {
            $b->delete();
        }

        $block = $setSearchAt->getBlocks('Sidebar');
        foreach ($block as $b) {
            $b->delete();
        }

        $bt = BlockType::getByHandle('search');

        $setblogAt = Page::getByPath('/blog');
        $searchPath = $setblogAt->cPath;

        $data = array('title' => t('Blog Search'),
            'buttonText' => t('go'),
            'baseSearchPath' => $searchPath,
            'resultsURL' => '',
        );

        $setSearchAt->addBlock($bt, 'Main', $data);

        $block = $setSearchAt->getBlocks('Main');
        foreach ($block as $b) {
            $b->setCustomTemplate('templates/blog_search');
        }

        $bt = BlockType::getByHandle('problog_list');
        $i = 0;
        for ($bb = 1; $bb <= 4; $bb += 1) {

            if ($bb == 1) {
                $title = t('Category List');
            } elseif ($bb == 2) {
                $title = t('Tag List');
            } elseif ($bb == 3) {
                $title = t('Tag Cloud');
            } elseif ($bb == 4) {
                $title = t('Archive');
            }

            $data = array('num' => '25',
                'cParentID' => '0',
                'cThis' => '0',
                'paginate' => '0',
                'displayAliases' => '0',
                'ctID' => $ctID,
                'rss' => '0',
                'rssTitle' => '',
                'rssDescription' => '',
                'truncateSummaries' => '0',
                'truncateChars' => '128',
                'category' => t('All Categories'),
                'title' => $title,
            );

            $b = $setSearchAt->addBlock($bt, 'Sidebar', $data);

            $i++;
            if ($i == 1) {
                $b->setCustomTemplate('categories');
            } elseif ($i == 2) {
                $b->setCustomTemplate('tags');
            } elseif ($i == 3) {
                $b->setCustomTemplate('tag_cloud');
            } elseif ($i == 4) {
                $b->setCustomTemplate('archive');
            }
        }

        $setSearchAt->reindex();

    }

    public function install_pb_page_defaults($pkg,$cocID)
    {
        $pageType = CollectionType::getByHandle('pb_post');
        $ctTemplate = $pageType->getPageTypeDefaultPageTemplateObject();
        $blogPostCollectionTypeMT = $pageType->getPageTypePageTemplateDefaultPageObject($ctTemplate);

        $ctID = $pageType->getPageTypeID();
        $bt = BlockType::getByHandle('problog_list');

        $cIDn = Page::getByPath('/blog')->getCollectionID();

        $blocks = $blogPostCollectionTypeMT->getBlocks('Sidebar');
        foreach ($blocks as $b) {
            $b->deleteBlock();
        }

        $i = 0;
        for ($bb = 1; $bb <= 4; $bb += 1) {

            if ($bb == 1) {
                $title = t('Category List');
            } elseif ($bb == 2) {
                $title = t('Tag List');
            } elseif ($bb == 3) {
                $title = t('Tag Cloud');
            } elseif ($bb == 4) {
                $title = t('Archive');
            }

            $data = array('num' => '25',
                'cParentID' => '0',
                'cThis' => '0',
                'paginate' => '0',
                'displayAliases' => '0',
                'ctID' => $ctID,
                'rss' => '0',
                'rssTitle' => '',
                'rssDescription' => '',
                'truncateSummaries' => '0',
                'truncateChars' => '128',
                'category' => t('All Categories'),
                'title' => $title,
            );

            $b = $blogPostCollectionTypeMT->addBlock($bt, 'Sidebar', $data);
            $i++;
            if ($i == 1) {
                $b->setCustomTemplate('categories');
            } elseif ($i == 2) {
                $b->setCustomTemplate('tags');
            } elseif ($i == 3) {
                $b->setCustomTemplate('tag_cloud');
            } elseif ($i == 4) {
                $b->setCustomTemplate('archive');
            }
        }
        
        
        //add composer controll output to pb_post defaults
        $bt = BlockType::getByHandle('core_page_type_composer_control_output');
        $data = array(
	      'ptComposerOutputControlID' => $cocID
        );
        $blogPostCollectionTypeMT->addBlock($bt, 'Main', $data);
        

        //install guestbook to page_type template
        $guestBookBT = BlockType::getByHandle('core_conversation');
        $guestbookArray = array();
        $guestbookArray['attachmentsEnabled'] = 0;
        $guestbookArray['title'] = t('Please add a comment');
        $guestbookArray['itemsPerPage'] = 14;
        $guestbookArray['enablePosting'] = 1;
        $guestbookArray['paginate'] = 1;
        $guestbookArray['displayMode'] = 'threaded';
        $blogPostCollectionTypeMT->addBlock($guestBookBT, 'Blog Post More', $guestbookArray);

    }

    public function install_pb_user_attributes($pkg)
    {

        $euku = AttributeKeyCategory::getByHandle('user');
        $euku->setAllowAttributeSets(AttributeKeyCategory::ASET_ALLOW_MULTIPLE);
        $uset = $euku->addSet('author_info', t('Author Info'), $pkg);

        $texta = AttributeType::getByHandle('textarea');
        $sbbio = UserAttributeKey::getByHandle('user_bio');
        if (!is_object($sbbio)) {
            UserAttributeKey::add($texta,
                array('akHandle' => 'user_bio',
                    'akName' => t('About the author'),
                    'akIsSearchable' => false,
                    'uakProfileEdit' => true,
                    'uakProfileEditRequired' => true,
                    'uakRegisterEdit' => true,
                    'uakProfileEditRequired' => true,
                    'akCheckedByDefault' => true,
                    'displayOrder' => '3',
                ), $pkg)->setAttributeSet($uset);
        }

        $textt = AttributeType::getByHandle('text');
        $sbname = UserAttributeKey::getByHandle('first_name');
        if (!is_object($sbname)) {
            UserAttributeKey::add($textt,
                array('akHandle' => 'first_name',
                    'akName' => t('First Name'),
                    'akIsSearchable' => false,
                    'uakProfileEdit' => true,
                    'uakProfileEditRequired' => true,
                    'uakRegisterEdit' => true,
                    'uakProfileEditRequired' => true,
                    'akCheckedByDefault' => true,
                    'displayOrder' => '1',
                ), $pkg)->setAttributeSet($uset);
        }

        $sblname = UserAttributeKey::getByHandle('last_name');
        if (!is_object($sblname)) {
            UserAttributeKey::add($textt,
                array('akHandle' => 'last_name',
                    'akName' => t('Last Name'),
                    'akIsSearchable' => false,
                    'uakProfileEdit' => true,
                    'uakProfileEditRequired' => true,
                    'uakRegisterEdit' => true,
                    'uakProfileEditRequired' => true,
                    'akCheckedByDefault' => true,
                    'displayOrder' => '2',
                ), $pkg)->setAttributeSet($uset);
        }

        /**
         *  To Do:  rework and add back at later date.
         *  Permissions can be manual for the time being.
         */
        //        $group = Group::getByName('ProBlog Editor');
        //        if (!$group || $group->getGroupID() < 1) {
        //            $group = Group::add('ProBlog Editor','Can create and edit Blog posts');
        //        }
        //
        //        $pk = PermissionKey::getByHandle('problog_post');
        //        if (!$pk || $pk->getPermissionKeyID() < 1) {
        //            $pk = AdminPermissionKey::add('admin','problog_post',t('Create Blog Posts'),t('User can use ProBlog frontend features.'),true,false,$pkg);
        //        }
        //
        //        $pe = GroupPermissionAccessEntity::getOrCreate($group);
        //
        //        $pa = AdminPermissionAccess::create($pk);
        //
        //        $pka = new PermissionAssignment();
        //        $pka->setPermissionKeyObject($pk);
        //        $pka->assignPermissionAccess($pa);
        //
        //        $pa->addListItem($pe, false, 10);
        //
        //        $agroup = Group::getByName('ProBlog Approver');
        //        if (!$agroup || $group->getGroupID() < 1) {
        //            $agroup = Group::add('ProBlog Approver','Can Approve Blog posts');
        //        }
        //
        //        $apk = PermissionKey::getByHandle('problog_approve');
        //        if (!$apk || $apk->getPermissionKeyID() < 1) {
        //            $apk = AdminPermissionKey::add('admin','problog_approve',t('Approve Blog Posts'),t('User can Approve ProBlog posts.'),true,false,$pkg);
        //        }
        //
        //        $ape = GroupPermissionAccessEntity::getOrCreate($agroup);
        //
        //        $apa = AdminPermissionAccess::create($apk);
        //
        //        $apka = new PermissionAssignment();
        //        $apka->setPermissionKeyObject($apk);
        //        $apka->assignPermissionAccess($apa);
        //
        //        $apa->addListItem($ape, false, 10); //add approver
        //        $pa->addListItem($ape, false, 10); //append approver to edit

    }

    public function install_pb_settings()
    {
        $tmplt = Template::getByHandle('right_sidebar');
        $serch = Page::getByPath('/blogsearch');
        $args = array(
            'tweet' => 1,
            'fb_like' => 1,
            'google' => 0,
            'addthis' => 1,
            'author' => 0,
            'comments' => 1,
            'trackback' => 1,
            'canonical' => 1,
            'breakSyntax' => '<hr id="horizontalrule">',
            'search_path' => $serch->getCollectionID(),
            'icon_color' => 'brown',
            'thumb_width' => '110',
            'thumb_height' => '120',
            'pageTemplate' => $tmplt->getPageTemplateID()
        );

        $db = Loader::db();
        $db->Execute("DELETE FROM btProBlogSettings");
        $db->insert('btProBlogSettings', $args);

    }

    public function registerHelpers()
    {
        Core::bind(
            'helper/blogify',
            'Concrete\Package\Problog\Controller\Helpers\Blogify'
        );
        Core::bind(
            'helper/blog_actions',
            'Concrete\Package\Problog\Controller\Helpers\BlogActions'
        );


		 $psr4_loader = new Psr4ClassLoader(); 
		 $psr4_loader->addPrefix('\\Concrete\\Package\\Problog\\src', __DIR__ . '/src'); 
		 $psr4_loader->register(); 

    }

    public function registerRoutes()
    {
        /**
         *  Registering Tools Calendar AJAX Views
         */
        Route::register('/problog/tools/twitter_save','\Concrete\Package\Problog\Controller\Tools\TwitterSave::save');
        Route::register('/problog/tools/dotweet','\Concrete\Package\Problog\Controller\Tools\DoTweet::send');
        Route::register('/problog/tools/add_blog', '\Concrete\Package\Problog\Controller\Tools\AddBlog::render');
        Route::register('/problog/tools/post_blog', '\Concrete\Package\Problog\Controller\Tools\AddBlog::save');
        Route::register('/problog/tools/optimizer', '\Concrete\Package\Problog\Controller\Tools\Optimizer::run');
        Route::register('/problog/tools/nab_attribute', '\Concrete\Package\Problog\Controller\Tools\NabAttribute::render');
        Route::register('/problog/tools/subscribe', '\Concrete\Package\Problog\Controller\Tools\Subscribe::doSubscription');

        /*
         *  Registering ProblogList XML Views
         */
        Route::register('/problog/routes/rss','\Concrete\Package\Problog\Controller\Tools\ProblogList\Rss::view');
    }

    public function registerEvents()
    {
        /**
         * Listen for new pb_post composer saves
         * & update content block view.
         */
        Events::addListener(
            'on_page_type_save_composer_form',
            function ($post) {
                $page = $post->getPageObject();
                $ctHandle = $page->getPageTypeHandle();
                if ($ctHandle == 'pb_post') {
                    $blocks = $page->getBlocks('Main');
                    foreach ($blocks as $b) {
                        if ($b->getBlockTypeHandle() == 'content') {
                            $b->setCustomTemplate('blog_post');
                        }
                    }
                }
            }
        );

        /**
         * Listen for new pb_post versions
         * if not under cannonical page, move it
         */
        Events::addListener(
            'on_page_version_add',
            function ($post) {
                $page = $post->getPageObject();
                $ctHandle = $page->getPageTypeHandle();
                if ($ctHandle == 'pb_post') {
                    $date = $page->getCollectionDatePublic();
                    $parentID = $page->getCollectionParentID();
                    $parent = Page::getByID($parentID);
                    if ($parent->getAttribute('blog_section') > 0) {
                        $canonical = Loader::helper('blogify')->getOrCreateCanonical($date,$parent);
                        $page->move($canonical);
                        $page->reindex();
                        Log::addEntry('ProBlog Post Relocated to Connonical Page.');
                    }
                }
            }
        );

        /**
         * Listen for new pb_post composer version approves
         * and activate
         */
        Events::addListener(
            'on_page_version_approve',
            function ($post) {
                $page = $post->getPageObject();
                $ctHandle = $page->getPageTypeHandle();
                if ($ctHandle == 'pb_post') {
                    $page->activate();
                    
                    $ba = Loader::helper('blog_actions');
					$ba->doSubscription($page);
                }
            }
        );

        /**
         * Listen for page render and add header nav
         */
        $obj = $this;
        Events::addListener(
            'on_before_render',
            function() use ($obj) {
                $obj->registerNav();
            });
    }

    public function on_start()
    {
        $this->registerHelpers();
        $this->registerRoutes();
        $this->registerEvents();
    }

    public function registerNav()
    {
        $u = new User();
        if($u->isLoggedIn()){
            $postID = null;
            $icon = 'file-text-o';
            $blog = Page::getCurrentPage();
            if($blog) {
                $canonical_parent_id = Loader::helper('blogify')->getCanonicalParent($blog->getCollectionDatePublic(),
                    $blog);
                $parent = Page::getByID($canonical_parent_id);
                if ($parent->getAttribute('blog_section') > 0) {
                    $title = t('Edit');
                    $icon = 'file-text';
                    $postID = $blog->getCollectionID();
                } else {
                    $title = t('Create');
                }
            }else{
                $title = t('Create');
            }

            $ihm = \Core::make('helper/concrete/ui/menu');
            $ihm->addPageHeaderMenuItem('problog', 'problog',
                array(
                    'icon' => $icon,
                    'label' => $title.t(' Event'),
                    'position' => 'right',
                    'href' => URL::to('/problog/tools/add_blog').'?postID='.$postID,
                    'linkAttributes' => array(
                        'id' => 'page-edit-nav-problog',
                        'dialog-title' => t('Create Blog'),
                        'dialog-on-open' => "",
                        'dialog-on-close' => "location.reload();",
                        'dialog-width' => '700',
                        'dialog-height' => "500",
                        'dialog-modal' => "false",
                        'class' => 'dialog-launch'
                    )
                )
            );
        }
    }
}
