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
                <div class="addthis_toolbox addthis_default_style">
                    <?php  
                    if ($tweet>0) {
                    ?>
                        <span class="st_twitter" st_url="<?php   echo BASE_URL.$url?>" st_title="<?php   echo $blogTitle?>"></span>
                    <?php   }
                    if ($fb_like==1) {
                    ?>
                        <span class="st_facebook" st_url="<?php   echo BASE_URL.$url?>" st_title="<?php   echo $blogTitle?>"></span>

                    <?php  
                    }
                    if ($google==1) {
                    ?>
                        <span class="st_plusone" st_url="<?php   echo BASE_URL.$url?>" st_title="<?php   echo $blogTitle?>"></span>
                    <?php  
                    }
                    ?>
                </div>
                <script type="text/javascript">var switchTo5x=true;</script>
                <script type="text/javascript" src="//ws.sharethis.com/button/buttons.js"></script>
                <?php   if ($sharethis) { ?>
                <script type="text/javascript">stLight.options({publisher:'<?php   echo $sharethis;?>'});</script>
                <?php   } ?>
                <div class="content-sbBlog-contain">
                    <div id="content-sbBlog-title">
                        <h3 class="ccm-page-list-title"><a href="<?php   echo $url;?>"><?php   echo $blogTitle?></a></h3>
                        <div id="content-sbBlog-date">
                        <?php    echo Core::make('helper/date')->formatCustom(t('M d, Y'),strtotime($blogDate)); ?>
                        </div>
                    </div>
                    <div>
                    <?php   if ($comments) { ?>
                    <div class="content-sbBlog-commentcount"><?php   echo $comment_count;?></div>
                    <?php   } ?>
                    <?php  
                    echo t('Category').': '.'<a href="'.BASE_URL.$search.'categories/'.str_replace(' ','_',$cat).'/">'.$cat.'</a>';;
                    ?>
                    <br/><br/>
                    </div>
                    <div class="content-sbBlog-post">
                    <?php  
                        if ($thumb) {
                            echo '<div class="thumbnail">';
                            echo '<img src="'.$image.'"/>';
                            echo '</div>';
                        }
                    ?>
                    <?php  
                        echo $blogify->closetags($content);
                    ?>
                    </div>
                </div>
                <a class="readmore" href="<?php   echo $url?>"><?php   echo t('Read More')?></a>
                <div id="tags">
                <b><?php   echo t('Tags')?> : </b>
                <?php  
                if (!empty($tag_list)) {
                    $x = 0;
                    foreach ($tag_list as $akct) {
                        if ($x) {echo ', ';}
                        echo '<a href="'.BASE_URL.$search.str_replace(' ','_',$akct->getSelectAttributeOptionValue()).'/">'.$akct->getSelectAttributeOptionValue().'</a>';
                        $x++;

                    }
                }
                ?>
            </div>
            </div>
            <br class="clearfloat" />
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
