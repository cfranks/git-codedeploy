<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

$df = Loader::helper('form/date_time');
$form = Loader::helper('form');
$fp = FilePermissions::getGlobal();
$tp = new TaskPermission();
$set = AttributeSet::getByHandle('problog_additional_attributes');
$setAttribs = $set->getAttributeKeys();

$AJAXblogPost = URL::to('/problog/tools/post_blog');
?>

<link rel="stylesheet" type="text/css" href="<?php   echo DIR_REL; ?>/concrete/css/redactor.css"></link>
<script type="text/javascript" src="<?php   echo DIR_REL; ?>/concrete/js/redactor.js"></script>
<script type="text/javascript" src="<?php   echo DIR_REL; ?>/concrete/js/file-manager.js"></script>
<script type="text/javascript" src="<?php   echo DIR_REL; ?>/concrete/js/jquery-fileupload.js"></script>
<script type="text/javascript" src="<?php echo URL::to('/tools/required/i18n_redactor_js')?>"></script>

<style type="text/css">
    .help {
        font-style: normal;
        font-weight: normal;
        border-color: #02890d;
        border-width: 1px;
        border-style: solid;
        max-width: 235px;
        padding: 16px;
        MARGIN-left: 85px;
        background-color: #f5f5f5;
        position: absolute;
        -moz-border-radius: 5px; /* this works only in camino/firefox */
        -webkit-border-radius: 5px; /* this is just for Safari */
    }

    .entry-form td {
        padding: 12px !important;
    }

    .allday_form {
        padding-top: 12px !important;
    }

    select {
        margin-bottom: 6px !important;
    }

    div#ccm-dashboard-content {
        padding-left: 45px;
    }

    select.form-control {
        max-width: 250px;
    }

    #add_event {
        width: 100%;
    }

    .clearfix{margin-bottom: 18px;}

    .redactor-editor{min-height: 400px!important;}
</style>
<div class="ccm-ui">
    <form method="post" action="" id="blog-post-form">

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
        <?php  
        if ($blog) {
            echo $form->hidden('blogID',$blog->getCollectionID());
        }
        ?>
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
                $values = array(0=>t('save normal'),1=>t('save as draft')); //,2=>t('save & notify approvers')
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
                            'fontsize','fontcolor','fontfamily','subscript','superscript','undo','redo','concrete5'
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
        <?php  
        $akt = CollectionAttributeKey::getByHandle('composer_optimizer');
        if (is_object($blog)) {
            $tvalue = $blog->getAttributeValueObject($akt);
        }
        ?>
        <div class="clearfix">
            <div class="input">
                <div class="input-prepend">
                    <?php   echo $akt->render('form', $tvalue, true);?>
                </div>
            </div>
        </div>
    </div>
    <div id="blog-error">

    </div>
    <br style="clear: both;"/>
    <button class="btn btn-primary pull-right" id="ccm-submit-blog-form"><?php  echo $buttonText?></button>
    </form>

    <br style="clear: both;"/>
    <div id="blog-message">

    </div>
</div>

<script type="text/javascript">
    var CCM_EDITOR_SECURITY_TOKEN = "<?php  echo Loader::helper('validation/token')->generate('editor')?>";
    /*<![CDATA[*/
    $('document').ready(function () {

        $('#ccm-submit-blog-form').click(function () {
            $('#blog-post-form').show();
            $('#blog-message').html('');
            $('#blog-error').html('');

            var form = $('#blog-post-form').serialize();
            var url = '<?php     echo $AJAXblogPost?>?';

            $.post(url, form, function (response) {
                if (response != 'success') {
                    message = '<ul>';
                    $.each(response, function (key, r) {

                        message += '<li>' + r + '</li>';
                    });
                    message += '</ul>';
                    $('#blog-error').html('<div class="alert alert-danger">' + message + '</div>').slideDown();
                } else {
                    $('#blog-post-form').slideUp();
                    $('#blog-message').html('<div class="alert alert-success"><?php  echo t('Your Blog Post has been posted successfully!')?></div>').slideDown();
                }
            }, 'json');

            return false;
        });
    });
    /*]]>*/
</script>
