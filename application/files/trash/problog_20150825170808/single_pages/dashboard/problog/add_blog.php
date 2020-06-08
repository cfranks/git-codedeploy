<?php   defined('C5_EXECUTE') or die("Access Denied."); ?>
<style type="text/css">
    #blogBody_ifr{height: 900px!important;}
    div#ccm-dashboard-content {
        padding-left: 45px;
    }
    .redactor-editor{min-height: 600px!important;}
    button.save{margin-right: 12px;}
    div#ccm-dashboard-content header{padding: 1px 80px 14px 105px;}
    .clearfix{margin: 12px 0;}
</style>
<?php  
$df = Loader::helper('form/date_time');
$fp = FilePermissions::getGlobal();
$tp = new TaskPermission();

if (is_object($blog)) {
    $blogTitle = $blog->getCollectionName();
    $blogDescription = $blog->getCollectionDescription();
    $blogDate = $blog->getCollectionDatePublic();
    $ptID = $blog->getPageTemplateID();
    $blogBody = '';
    $eb = $blog->getBlocks('Main');
    foreach ($eb as $b) {
        if ($b->getBlockTypeHandle()=='content' || $b->getBlockTypeHandle()=='sb_add_blog') {
            $blogBody = $b->getInstance()->getContent();
        }
    }
    $task = 'edit';
    $buttonText = t('Update Blog Entry');
    $title = t('Update');
} else {
    $task = 'add';
    $buttonText = t('Add Blog Entry');
    $title= t('Add');
}

$set = AttributeSet::getByHandle('problog_additional_attributes');
$setAttribs = $set->getAttributeKeys();
?>

<?php  echo  Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper($title . t(' Blog Post'),false,'span10 offset1',false) ?>

<?php       if ($this->controller->getTask() == 'edit') { ?>
<form method="post" action="<?php       echo $this->action($task,$blog->getCollectionID())?>" id="blog-form">
<?php       echo $form->hidden('blogID', $blog->getCollectionID())?>
<?php       } else { ?>
<form method="post" action="<?php       echo $this->action($task)?>" id="blog-form">
<?php       } ?>

<ul class="nav nav-tabs">
    <li class="active"><a href="javascript:void(0)" onclick="$('ul.nav-tabs li').removeClass('active'); $(this).parent().addClass('active'); $('.pane').hide(); $('div.info').show();"><?php      echo t('Info')?></a>
    </li>
    <li><a href="javascript:void(0)" onclick="$('ul.nav-tabs li').removeClass('active'); $(this).parent().addClass('active'); $('.pane').hide(); $('div.post').show();"><?php      echo t('Post')?></a>
    </li>
    <li><a href="javascript:void(0)" onclick="$('ul.nav-tabs li').removeClass('active'); $(this).parent().addClass('active'); $('.pane').hide(); $('div.options').show();"><?php      echo t('Options')?></a>
    </li>
    <?php   if (count($setAttribs) > 0) { ?>
        <li><a href="javascript:void(0)" onclick="$('ul.nav-tabs li').removeClass('active'); $(this).parent().addClass('active'); $('.pane').hide(); $('div.attributes').show();"><?php       echo t('Attributes')?></a>
        </li>
    <?php   } ?>
    <li><a href="javascript:void(0)" onclick="$('ul.nav-tabs li').removeClass('active'); $(this).parent().addClass('active'); $('.pane').hide(); $('div.meta').show();"><?php      echo t('Meta')?></a>
    </li>
    <li><a href="javascript:void(0)" onclick="$('ul.nav-tabs li').removeClass('active'); $(this).parent().addClass('active'); $('.pane').hide(); $('div.seo').show();" class="seo-tools"><?php      echo t('Optimize')?></a>
    </li>
</ul>
<br style="clear:both;"/>
<div class="pane info" style="display: block;">
    <?php       echo $form->hidden('front_side',1)?>
    <div class="clearfix">
        <?php       echo $form->label('blogTitle', t('Blog Title'))?> *
        <div class="input">
            <?php       echo $form->text('blogTitle', $blogTitle, array('style' => 'width: 230px'))?>
        </div>
    </div>

    <div class="clearfix">
        <?php       echo $form->label('blog_author', t('Author'))?>
        <div class="input">
            <?php  
            $auth = CollectionAttributeKey::getByHandle('blog_author');
            if (is_object($blog)) {
                $authvalue = $blog->getAttributeValueObject($auth);
            }
            ?>
            <div class="blog-attributes">
                <div style="width: 230px;">
                    <?php       echo $auth->render('form', $authvalue);?>
                </div>
            </div>
        </div>
    </div>
    <?php  
    $akt = CollectionAttributeKey::getByHandle('thumbnail');
    if (is_object($blog)) {
        $tvalue = $blog->getAttributeValueObject($akt);
    }
    ?>
    <div class="clearfix">
        <?php    echo $akt->render('label');?>
        <div class="input">
            <table class="bordered-table" style="width: 230px;">
                <tr>
                    <td>
                        <?php   echo $akt->render('form', $tvalue, true);?>
                    </td>
                </tr>
            </table>
        </div>
    </div>


    <div class="clearfix">
        <?php       echo $form->label('blogDescription', t('Blog Description'))?>
        <div class="input">
            <div><?php       echo $form->textarea('blogDescription', $blogDescription, array('style' => 'width: 98%; height: 90px; font-family: sans-serif;'))?></div>
        </div>
    </div>
</div>
<div class="pane post" style="display: none;">
    <div class="clearfix">
        <?php       echo $form->label('draft',t('Draft Copy'));?>
        <div class="input">
            <?php  
            if ($blog) {
                $pa = $blog->getVersionObject();
                if ($pa->isApproved()==1) {$draft = 0;} else {$draft = 1;}
            } else {
                $draft = 0;
            }
            $values = array(0=>t('save normal'),1=>t('save as draft'));//,2=>t('save & notify approvers')
            ?>
            <?php      echo $form->select('draft',$values,$draft)?>
        </div>
    </div>
    <br styl="clear:both;"/>
    <div class="clearfix">
        <?php  
        print $form->textarea(
        'blogBody',
        $blogBody,
        array(
        'class' => $class,
        'style' => 'height: 380px'
        )
        );
        ?>
        <script type="text/javascript">
            var CCM_EDITOR_SECURITY_TOKEN = "<?php  echo Loader::helper('validation/token')->generate('editor')?>";
            $(document).ready(function () {
                $('#blogBody').redactor({
                    'concrete5': {
                        filemanager: <?php   echo $fp->canAccessFileManager()?>,
                        sitemap: <?php   echo $tp->canAccessSitemap()?>,
                        lightbox: true
                    },
                    'plugins': [
                        'fontsize','fontcolor','fontfamily','subscript','superscript','undo','redo', 'concrete5'
                    ]
                });
            });
        </script>
    </div>
</div>
<div class="pane options" style="display: none;">
    <div class="clearfix">
        <?php       echo $form->label('blogDate', t('Date/Time'))?>
        <div class="input">
            <?php       echo $df->datetime('blogDate', $blogDate)?>
        </div>
    </div>

    <?php  
    $akt = CollectionAttributeKey::getByHandle('tags');
    if (is_object($blog)) {
        $tvalue = $blog->getAttributeValueObject($akt);
    }
    ?>
    <div class="clearfix">
        <?php   echo $akt->render('label');?>
        <div class="input">
            <?php   echo $akt->render('form', $tvalue, true);?>
        </div>
    </div>

    <?php  
    $akct = CollectionAttributeKey::getByHandle('blog_category');
    if (is_object($blog)) {
        $tcvalue = $blog->getAttributeValueObject($akct);
    }
    ?>
    <div class="clearfix">
        <?php   echo $form->label('blogCategory', t('Blog Category'))?>
        <div class="input">
            <?php   echo $akct->render('form', $tcvalue, true);?>
        </div>
    </div>


    <div class="clearfix">
        <?php   echo $form->label('cParentID', t('Section/Location'))?>
        <div class="input">
            <?php       if (count($sections) == 0) { ?>
                <div><?php       echo t('No sections defined. Please create a page with the attribute "blog_section" set to true.')?></div>
            <?php       } else { ?>
                <?php  
                if ($ubp->cID) {
                    if (array_key_exists($cParentID,$user_sections)) {
                        ?>
                        <div style="display: none;"><?php       echo $form->select('cParentID', $user_sections, $cParentID)?></div>
                        <?php  
                        echo '<br/><i>'.$user_sections[$cParentID].'</i><br/>';
                    } else {
                        ?>
                        <div><?php       echo $form->select('cParentID', $sections, $cParentID)?></div>
                    <?php  
                    }
                } else {
                    ?>
                    <div><?php       echo $form->select('cParentID', $sections, $cParentID)?></div>
                <?php       }

            }
            ?>
        </div>
    </div>

    <?php  
    if (!$ptID) {
        $ptID = $settings['pageTemplate'];
    }
    ?>
    <div class="clearfix">
        <?php       echo $form->label('ptID', t('Page Template'))?>
        <div class="input">
            <?php    echo $form->select('ptID', $pageTemplates, $ptID)?>
        </div>
    </div>

	<?php  
    $akt = CollectionAttributeKey::getByHandle('send_subscription');
    if (is_object($blog)) {
        $tvalue = $blog->getAttributeValueObject($akt);
    }
    ?>
    <div class="clearfix">
        <?php     echo $form->label('send_subscription', t('Send To Subscribers?'))?>
        <div class="input">
            <div class="input-prepend">
                <?php   echo $akt->render('form', $tvalue, true);?>
            </div>
        </div>
    </div>
    
    <?php  
    $akt = CollectionAttributeKey::getByHandle('post_to_twitter');
    if (is_object($blog)) {
        $tvalue = $blog->getAttributeValueObject($akt);
    }
    ?>
    <div class="clearfix">
        <?php     echo $form->label('notify', t('Post to Twitter?'))?>
        <div class="input">
            <div class="input-prepend">
                <?php   echo $akt->render('form', $tvalue, true);?>
            </div>
        </div>
    </div>
</div>
<div class="pane attributes" style="display: none;">
    <?php  
    if ($setAttribs) {
        foreach ($setAttribs as $ak) {
            if (is_object($blog)) {
                $aValue = $blog->getAttributeValueObject($ak);
            }
            ?>
            <div class="clearfix">
                <?php       echo $ak->render('label');?>
                <div class="input">
                    <?php       echo $ak->render('form', $aValue)?>
                </div>
            </div>
        <?php  
        }
    }
    ?>
</div>
<div class="pane meta" style="display: none;">
    <div class="clearfix">
        <?php       echo $form->label('akID[1][value]', t('Meta Title'))?>
        <div class="input">
            <?php  
            if (is_object($blog)) {
                $metaTitle = $blog->getAttribute('meta_title');
            }
            ?>
            <?php       echo $form->text('akID[1][value]', $metaTitle, array('style' => 'width: 230px'))?>
        </div>
    </div>

    <div class="clearfix">
        <?php       echo $form->label('akID[2][value]', t('Meta Description'))?>
        <div class="input">
            <?php  
            if (is_object($blog)) {
                $metaDescription = $blog->getAttribute('meta_description');
            }
            ?>
            <?php       echo $form->textarea('akID[2][value]', $metaDescription, array('style' => 'width: 98%; height: 90px; font-family: sans-serif;'))?>
        </div>
    </div>

    <div class="clearfix">
        <?php       echo $form->label('akID[3][value]', t('Meta Tags'))?>
        <div class="input">
            <?php  
            if (is_object($blog)) {
                $metaKeywords = $blog->getAttribute('meta_keywords');
            }
            ?>
            <?php       echo $form->textarea('akID[3][value]', $metaKeywords, array('style' => 'width: 98%; height: 90px; font-family: sans-serif;'))?>
        </div>
    </div>
</div>
<div class="pane seo" style="display: none;">
<!--
<a href="javascript:;" id="control" class="btn btn-info"><i class="fa fa-refresh"></i> Refresh</a><br/><br/>
-->
<div class="row">
    <div class="col-xs-12">
        <div class="alert block-message alert-info">
            <a class="close" href="javascript:;">×</a>
            <p><strong><?php        echo t('SEO Tools to help you maximize your impact!');?></strong></p>
            <p><?php        echo t('<p>Below you will find some helpful SEO tools to aid you in the delicate balance of keywords, keyword phrases, images, and links.</p><p>To the right you will find three important checklists.  While nothing on this report is mandatory, making sure as many items are checked on these lists as possible will ensure better readability and ranking by search engine algorithms.</p>');?></p>
            <div class="alert-actions">

            </div>
        </div>
    </div>

    <?php   if (!$settings['alchemy']) { ?>
        <div class="col-xs-12">
            <div class="alert block-message alert-success">
                <a class="close" href="javascript:;">×</a>
                <p><strong><?php        echo t('You have not enabled your Alchemy API!');?></strong></p>
                <p><?php        echo t('<p>You can enable your Alchemy API in your ProBlog settings area.</p>');?></p>
                <div class="alert-actions">

                </div>
            </div>
        </div>
    <?php   } else { ?>
        <?php   Loader::packageElement('tools/optimizer','problog',array('settings'=>$settings)); ?>
    <?php   } ?>
</div>

</div>
<br/>
<div class="ccm-pane-footer">
    <button type="submit" class="btn btn-primary" style="float: right;"><?php   echo t('%s Post', $title) ?></button>
    <button class="btn btn-info save" style="float: right;"><?php   echo t('Save & Continue') ?></button>
    <a href="<?php  echo  $this->url('/dashboard/problog/blog_list/') ?>" class="btn btn-danger"><?php   echo t('Cancel') ?></a>
</div>

</form>
