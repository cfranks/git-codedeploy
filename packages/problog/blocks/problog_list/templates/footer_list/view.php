<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
extract($blog_settings);
$c = Page::getCurrentPage();

if (count($cArray) > 0) {
?>
	<ul class="list-unstyled">
	<?php  
    for ($i = 0; $i < count($cArray); $i++ ) {

        $cobj = $cArray[$i];
        $title = $cobj->getCollectionName();

        $comment_count = $blogify->getNewCommentCount($cobj->getCollectionID());

        $date = $cobj->getCollectionDatePublic();

        $authorID = $c->getAttribute('blog_author');
        if (!$authorID) {
            $authorID = $c->getCollectionUserID();
        }
        $ui = UserInfo::getByID($authorID);

        if ($ui->uID != '') {
            $username = $ui->getUserName();
        }

        $imgHelper = Loader::helper('image');
        $imageF = $cobj->getAttribute('thumbnail');
        if (isset($imageF)) {
            $image = $imgHelper->getThumbnail($imageF, $blog_settings['thumb_width'],$blog_settings['thumb_height'])->src;
        }

        $content = false;
        if ($use_content > 0) {
            $block = $cobj->getBlocks('Main');
            foreach ($block as $bi) {
                if ($bi->getBlockTypeHandle()=='content' || $bi->getBlockTypeHandle()=='sb_blog_post') {
                    $content = $bi->getInstance()->getContent();
                }
            }
        } else {
            $content = $cobj->getCollectionDescription();
        }
        ?>
          <li>
          	<?php  
            if ($imageF) {
            ?>
          	<img src="<?php       echo $image?>" width="70" height="70" alt="image">
          	<?php  
              }
              ?>
            <h3><a href="<?php   echo BASE_URL.$nh->getLinkToCollection($cobj)?>"><?php   echo $title?></a></h3>
            <span><?php    echo Core::make('helper/date')->formatCustom(t('M d, Y'),strtotime($date)); ?>| <?php   echo t('by')?> <?php   echo $username?></span>
            <p>
    		<?php  
                if (!$controller->truncateSummaries) {
                    echo $content;
                } else {
                    echo $th->shorten($content,$controller->truncateChars);
                }
            ?>
            </p>
          </li>
    <?php  
    }
    if (!$previewMode && $controller->rss) {
            ?>
			<div class="rssIcon">
				<?php    echo t('Get this feed')?> &nbsp;<a href="<?php  echo URL::to('/problog/routes/rss')?>?bID=<?php   echo $blogBockID; ?>&problogRss=true" target="_blank"><img src="<?php       echo $uh->getBlockTypeAssetsURL($bt, 'images/rss.png')?>" alt="codestrat concrete5 addon development" title="CodeStrat Concrete5 Addon Development" width="14" height="14" /></a>
			</div>
			<link href="<?php  echo URL::to('/problog/routes/rss')?>?bID=<?php   echo $blogBockID; ?>&problogRss=true" rel="alternate" type="application/rss+xml" title="<?php   echo $controller->rssTitle?>" />
	<?php  
    }
    ?>
	</ul>
<?php   } ?>
