<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php  echo $form->label('SocialMedia', t("Social Media")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('SocialMedia', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo $form->select($view->field('SocialMedia'), $SocialMedia_options, $SocialMedia, array()); ?>
</div>

<div class="form-group">
    <?php  echo $form->label('Link', t("Link")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('Link', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo $form->text($view->field('Link'), $Link, array()); ?>
</div>
<div class="form-group">
    <?php  echo $form->label('Link_text', t("Link") . " " . t('Text')); ?>
    <?php  echo $form->text($view->field('Link_text'), $Link_text, array()); ?>
</div>

<div class="form-group">
    <?php  echo $form->label('NewTab', t("Open Link in New Tab?")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('NewTab', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo $form->select($view->field('NewTab'), $NewTab_options, $NewTab, array()); ?>
</div>