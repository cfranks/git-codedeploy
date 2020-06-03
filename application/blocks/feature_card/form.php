<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php
    if (isset($cardimg) && $cardimg > 0) {
        $cardimg_o = File::getByID($cardimg);
        if (!is_object($cardimg_o)) {
            unset($cardimg_o);
        }
    } ?>
    <?php echo $form->label($view->field('cardimg'), t("Image")); ?>
    <?php echo isset($btFieldsRequired) && in_array('cardimg', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make("helper/concrete/asset_library")->image('ccm-b-feature_card-cardimg-' . $identifier_getString, $view->field('cardimg'), t("Choose Image"), $cardimg_o); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('cardtitle'), t("Title")); ?>
    <?php echo isset($btFieldsRequired) && in_array('cardtitle', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('cardtitle'), $cardtitle, array (
  'maxlength' => 255,
)); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('cardblurb'), t("Blurb")); ?>
    <?php echo isset($btFieldsRequired) && in_array('cardblurb', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->textarea($view->field('cardblurb'), $cardblurb, array (
  'rows' => 5,
)); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('cardmoreinfo'), t("More Info")); ?>
    <?php echo isset($btFieldsRequired) && in_array('cardmoreinfo', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->textarea($view->field('cardmoreinfo'), $cardmoreinfo, array (
  'rows' => 5,
)); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('cardlink'), t("Link")); ?>
    <?php echo isset($btFieldsRequired) && in_array('cardlink', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make("helper/form/page_selector")->selectPage($view->field('cardlink'), $cardlink); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('cardlink_text'), t("Link") . " " . t("Text")); ?>
    <?php echo $form->text($view->field('cardlink_text'), $cardlink_text, []); ?>
</div>