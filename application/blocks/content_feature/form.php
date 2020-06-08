<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php
    if (isset($featureimg) && $featureimg > 0) {
        $featureimg_o = File::getByID($featureimg);
        if (!is_object($featureimg_o)) {
            unset($featureimg_o);
        }
    } ?>
    <?php echo $form->label($view->field('featureimg'), t("Image (600x300)")); ?>
    <?php echo isset($btFieldsRequired) && in_array('featureimg', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make("helper/concrete/asset_library")->image('ccm-b-content_feature-featureimg-' . $identifier_getString, $view->field('featureimg'), t("Choose Image"), $featureimg_o); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('featurehdr'), t("Header")); ?>
    <?php echo isset($btFieldsRequired) && in_array('featurehdr', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('featurehdr'), $featurehdr, array (
  'maxlength' => 255,
)); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('featureblurb'), t("Blurb")); ?>
    <?php echo isset($btFieldsRequired) && in_array('featureblurb', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->textarea($view->field('featureblurb'), $featureblurb, array (
  'rows' => 5,
)); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('featurelink'), t("Link")); ?>
    <?php echo isset($btFieldsRequired) && in_array('featurelink', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make("helper/form/page_selector")->selectPage($view->field('featurelink'), $featurelink); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('featurelink_text'), t("Link") . " " . t("Text")); ?>
    <?php echo $form->text($view->field('featurelink_text'), $featurelink_text, []); ?>
</div>