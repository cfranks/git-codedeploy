<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
extract($blog_settings);
$c = Page::getCurrentPage();
if (count($cArray) > 0) { ?>
    <div class="ccm-page-list">
    <?php  
    for ($i = 0; $i < count($cArray); $i++ ) {
        $cobj = $cArray[$i];

        extract($blogify->getBlogVars($cobj));

        $content = $controller->getContent($cobj,$blog_settings);
        ?>
             <div class="content-sbBlog-wrap">
               
                <script type="text/javascript">var switchTo5x=true;</script>
                <script type="text/javascript" src="//ws.sharethis.com/button/buttons.js"></script>
                <?php   if ($sharethis) { ?>
                <script type="text/javascript">stLight.options({publisher:'<?php   echo $sharethis;?>'});</script>
                <?php   } ?>
                <div class="news-item">
                   <?php  
                        if ($thumb) {
                            echo '<img src="'.$image.'"/>';
                        }
                    ?>
                        <h3><a href="<?php   echo $url;?>"><?php   echo $blogTitle?></a></h3>
                        <p class="date">
                        <?php    echo Core::make('helper/date')->formatCustom(t('m/d/Y'),strtotime($blogDate)); ?>
                        </p>


                    <p>
                  
                    <?php  
                        echo $blogify->closetags($content);
                    ?>
                    <p>
                </div>

            </div>
            <br class="clearfloat" />
    <?php  
    }
    $u = new User();
    $subscribed = $c->getAttribute('subscription');
    if ($subscribe && $u->isLoggedIn()) {
        if ($subscribed && in_array($u->getUserID(),$subscribed)) {
            $subscribed_status = true;
        }
        ?>
        <div id="subscribe_to_blog" class="ccm-ui">
            <a href="<?php   echo $subscribe_link; ?>?blog=<?php   echo $cParentID; ?>&user=<?php   echo $u->getUserID(); ?>" onClick="javascript:;" class="subscribe_to_blog btn btn-default btn-small" data-status="<?php       if ($subscribed_status) { echo 'unsubscribe';} else { echo 'subscribed';}?>"> <?php       if ($subscribed_status) {echo t('Unsubscribe from this Blog'); } else { echo t('Subscribe to this Blog'); }?> </a>
        </div>
        <?php  
    }
    if (!$previewMode && $controller->rss) {
        ?>
        <div id="rss-feed">
            <p>
                <img src="<?php   echo $rss_img_url; ?>" width="25" alt="iCal feed"/>&nbsp;&nbsp;
                <a href="<?php  echo URL::to('/problog/routes/rss')?>?bID=<?php   echo $blogBockID; ?>&problogRss=true&ordering=<?php   echo $ordering; ?>" id="getFeed"><?php   echo t('get RSS feed'); ?></a>
            </p>
            <link href="<?php  echo URL::to('/problog/routes/rss')?>?bID=<?php   echo $blogBockID; ?>&problogRss=true" rel="alternate" type="application/rss+xml" title="<?php   echo t('RSS'); ?>"/>
        </div>
        <?php  
        }
        ?>
</div>
<?php    } ?>

<?php   if ($paginate): ?>
    <?php   echo $pagination; ?>
<?php   endif; ?>

<script type="text/javascript">
/*<![CDATA[*/
    $(document).ready(function () {
        prettyPrint();
        $('.subscribe_to_blog').click(function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax(url,{
                error: function (r) {
                },
                success: function (r) {
                    if ($('.subscribe_to_blog').attr('data-status') == 'subscribed') {
                        $('.subscribe_to_blog').html('<?php       echo t('Unsubscribe from this Blog'); ?>');
                        $('.subscribe_to_blog').attr('data-status','unsubscribe');
                    } else {
                        $('.subscribe_to_blog').html('<?php       echo t('Subscribe to this Blog'); ?>');
                        $('.subscribe_to_blog').attr('data-status','subscribed');
                    }
                }
            });
        });
    });
/*]]>*/
</script>
