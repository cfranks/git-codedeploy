<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php
    if (isset($cardbkgimg) && $cardbkgimg > 0) {
        $cardbkgimg_o = File::getByID($cardbkgimg);
        if (!is_object($cardbkgimg_o)) {
            unset($cardbkgimg_o);
        }
    } ?>
    <?php echo $form->label($view->field('cardbkgimg'), t("Background Image (600x800)")); ?>
    <?php echo isset($btFieldsRequired) && in_array('cardbkgimg', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make("helper/concrete/asset_library")->image('ccm-b-annual_meeting_feature-cardbkgimg-' . $identifier_getString, $view->field('cardbkgimg'), t("Choose Image"), $cardbkgimg_o); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('cardcontent'), t("Content")); ?>
    <?php echo isset($btFieldsRequired) && in_array('cardcontent', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make('editor')->outputBlockEditModeEditor($view->field('cardcontent'), $cardcontent); ?>
</div>