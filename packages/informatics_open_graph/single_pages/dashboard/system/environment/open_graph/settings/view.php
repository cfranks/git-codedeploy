<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<form method="post" id="site-form" action="<?php echo $this->action('save_settings'); ?>"  enctype="multipart/form-data">

<?php echo $this->controller->token->output('save_settings'); ?>
        <legend><?php echo t('Facebook APP Settings'); ?></legend>
        <div class="form-group">
            <?php echo $form->label('fb_admin', t('fb:admins')); ?>
            <?php echo $form->text('fb_admin', $fb_admin, array('placeholder' => t('The value of the facebook Admin ID or blank'))); ?>
        </div>
        <div class="form-group">
            <?php echo $form->label('fb_app_id', t('fb:app_id')); ?>
            <?php echo $form->text('fb_app_id', $fb_app_id, array('placeholder' => t('The value of the facebook App ID or blank'))); ?>
        </div>
        <legend><?php echo t('Default Values'); ?></legend>
        <div class="form-group">
            <?php echo $form->label('default_title', t('Title')) ?>
            <?php echo $form->text('default_title', $default_title, array('placeholder' => t('The default page title to use if one is not specifed on the page'))); ?>
        </div>

        <div class="form-group">
            <?php echo $form->label('seo_select', t('Seo Select')) ?>
            <?php echo $form->checkbox('seo_select', 1, $seo_select); ?>
            <br/>
            <?php echo $form->label('seo_select', t('If yes, then format for title')) ?>
            <?php echo $form->text('default_format', $default_format ? $default_format : '[SITE NAME] : [PAGE TITLE]', array('placeholder' => t('[SITE NAME]:[PAGE TITLE]'))); ?>
            <span class="help-block">
                <?php echo t('When set the og:title value will be [SITE NAME]:[PAGE TITLE]'); ?>
            </span>
        </div>

        <div class="form-group">
            <?php echo $form->label('default_description', t('Description')) ?>
            <?php echo $form->textarea('default_description', $default_description, ['placeholder'=>'Default Description']) ?>
            <?php echo t("The default description to use when the page description is not set"); ?>
        </div>
        <legend><?php echo t('Default Thumbnail'); ?></legend>
        <div class="form-group">
            <?php echo $form->label('og_thumbnail_id', t('og:image')) ?>
            <?php $al = Loader::helper('concrete/asset_library'); ?>
            <?php echo $al->image('og-thumbnail-id', 'og_thumbnail_id', t('Select Default Thumbnail'), $imageObject); ?>
            <span class="help-block">
                <?php echo t('Image referenced by og:image must be at least 600x315 pixels.'); ?>
            </span>
        </div>
    <div class="ccm-dashboard-form-actions-wrapper">
    <div class="ccm-dashboard-form-actions">
        <button class="pull-right btn btn-success" type="submit" ><?php echo t('Save') ?></button>
    </div>
    </div>

</form>