<?php    defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php 
/**
 * Settings view
 *
* @package     FS Open Graph Protocol Lite
* @author      Fagan Systems
* @copyright   Copyright (c) 2015. (http://www.fagan-systems.com)
 * @license     http://www.fagan-systems.com/faqhelp/commercial-license Commercial License
 *
 */
?>

<form method="post" id="site-form" action="<?php    echo $this->action('save_settings'); ?>"  enctype="multipart/form-data">

<?php    echo $this->controller->token->output('save_settings'); ?>
    <fieldset>
        <legend><?php    echo t('Facebook APP Settings'); ?></legend>
        <div class="form-group">
            <?php    echo $form->label('fb_admin', t('fb:admins')); ?>
            <?php    echo $form->text('fb_admin', $fb_admin, array('placeholder'=>t('The value of the facebook Admin ID or blank'))); ?>
        </div>
        <div class="form-group">
            <?php    echo $form->label('fb_app_id', t('fb:app_id')); ?>
            <?php    echo $form->text('fb_app_id', $fb_app_id, array('placeholder'=>t('The value of the facebook App ID or blank'))); ?>
        </div>
    </fieldset>
    <fieldset>
        <legend><?php    echo t('Default Values'); ?></legend>
        <div class="form-group">
            <?php    echo $form->label('default_title', t('Title'))?>
            <?php    echo $form->text('default_title', $default_title, array('placeholder'=>t('The default page title to use if one is not specifed on the page'))); ?>
        </div>

        <div class="form-group">
            <?php    echo $form->label('seo_select', t('Seo Select'))?>
            <?php    echo $form->checkbox('seo_select', 1, $seo_select); ?>
            <span class="help-block">
                <?php    echo t('When set the og:title value will be [SITE NAME]:[PAGE TITLE]'); ?>
            </span>
        </div>

        <div class="form-group">
            <?php    echo $form->label('default_description', t('Description'))?>
            <textarea style="display: block;max-width:100%;width:100%;" id="default_description" name="default_description"><?php  echo $default_description?></textarea>
            <?php  echo t("The default description to use when the page description is not set");?>
        </div>
    </fieldset>
    <fieldset>
        <legend><?php    echo t('Default Thumbnail'); ?></legend>
        <div class="form-group">
            <?php    echo $form->label('og_thumbnail_id', t('og:image'))?>
            <?php    $al = Loader::helper('concrete/asset_library'); ?>
            <?php    echo $al->image('og-thumbnail-id', 'og_thumbnail_id', t('Select Default Thumbnail'), $imageObject); ?>
            <span class="help-block">
                <?php    echo t('Image referenced by og:image must be at least 600x315 pixels.'); ?>
            </span>
        </div>
    </fieldset>
    <div class="panel panel-default">
        <div class="panel-heading"><?php    echo t('Page Attribute Reference'); ?></div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th><?php    echo t('Handle'); ?></th>
                        <th><?php    echo t('Description'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th><?php    echo t('fb:admins'); ?></th>
                        <td><?php    echo t('This setting can be safely left blank. This Tag allows you to just specify each Facebook user ID that you would like to give the permission to acces the INSIGHTS data for this website'); ?></td>
                    </tr>
                    <tr>
                        <th><?php    echo t('fb:app_id'); ?></th>
                        <td><?php    echo t('This setting can safely be left blank. Setting the fb:app_id will allow the Facebook scraper to associate your Open Graph entity with your Facebook account. This will allow any admins of that app to view Insights about that URL and any social plugins connected with it'); ?></td>
                    </tr>
                    <tr>
                        <th><?php    echo t('og:title'); ?></th>
                        <td><?php    echo t('The title of the entity. This can be changed on a page basis by specifying the attribute value.<br>If this value is empty, the "meta_title" attribute is used otherwise the page name is used. Is the SEO option is set then the title will look like [SITE NAME]:[PAGE TITLE]'); ?></td>
                    </tr>
                    <tr>
                        <th><?php    echo t('og:description'); ?></th>
                        <td><?php    echo t('The default description for the website. OGP uses the OGP page attribute description, if empty then tries to use the page description, followed by the meta_description and finally this value.'); ?></td>
                    </tr>
                    <tr>
                    <tr>
                        <th><?php     echo t('og:image'); ?></th>
                        <td><?php     echo t('An image that represents the website. If this value is empty,the page "thumbnail" attribute used instead.'); ?></td>
                    </tr>
                    <tr>
                        <th><?php    echo 'SEO Select'; ?></th>
                        <td><?php    echo t('This option determines how the Page Title is presented, when clear just the page Title is used but when it is ticked then the title will look like [SITE NAME]:[PAGE TITLE]'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="panel-heading"><?php    echo t('<b>Auto Generated Attribute Reference</b>'); ?></div>
        <div class="panel-body">
            <div><?php  echo t('These attributes are automatically extracted from the website, the user has no options to change these values, they are documented here for clarity'); ?> </div>
            <table class="table">
                <thead>
                    <th><?php    echo t('og:type'); ?></th>
                        <td><?php    echo t('This will be set to "website".'); ?></td>
                    </tr>
                    <tr>
                        <th><?php    echo t('og:url'); ?></th>
                        <td><?php    echo t('This will be set to website url, it will include the HTTP or HTTPS as dictated by the page.'); ?></td>
                    </tr>

                    <tr>
                        <th><?php     echo t('og:width'); ?></th>
                        <td><?php     echo t('The selected image width.'); ?></td>
                    </tr>
                    <tr>
                        <th><?php     echo t('og:height'); ?></th>
                        <td><?php     echo t('The selected image height.'); ?></td>
                    </tr>
                    <tr>
                        <th><?php     echo t('og:sitename'); ?></th>
                        <td><?php     echo t('The website name.'); ?></td>
                    </tr>
                    <tr>
                        <th><?php     echo ('og:locale'); ?></th>
                        <td><?php     echo t('The website locale.'); ?></td>
                    </tr>
                    <tr>
                        <th><?php     echo t('og:updated_time'); ?></th>
                        <td><?php     echo t('The last tme the page was changed.'); ?></td>
                    </tr>
                    <tr>
                        <th><?php     echo t('Order of precedence<br>Applies to og:image, og:title, og:description'); ?></th>
                        <td><?php     echo t('For these elements there is an order that the value is selected. If you have set a page level og: attribute that is used, else the page value is used, else the meta value is used, finally the site level og: default is used.'); ?></td>
                    </tr>
                    <tr>
                        <th><?php     echo t('Changing &lt;HEAD&gt;'); ?></th>
                        <td><?php     echo t('Optional: If you want to be fully compliant to the og: schema you should declare the Name Spaces used. To declare the Named Spaces change the &lt;HEAD&gt; in the header_top include file to this &lt;HEAD prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#"&gt; '); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="ccm-dashboard-form-actions-wrapper">
    <div class="ccm-dashboard-form-actions">
        <button class="pull-right btn btn-success" type="submit" ><?php    echo t('Save')?></button>
    </div>
    </div>

</form>