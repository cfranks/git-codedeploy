<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php
    if (isset($sidebarimage) && $sidebarimage > 0) {
        $sidebarimage_o = File::getByID($sidebarimage);
        if (!is_object($sidebarimage_o)) {
            unset($sidebarimage_o);
        }
    } ?>
    <?php echo $form->label($view->field('sidebarimage'), t("Sidebar Image (600x350)")); ?>
    <?php echo isset($btFieldsRequired) && in_array('sidebarimage', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make("helper/concrete/asset_library")->image('ccm-b-custom_testimonial-sidebarimage-' . $identifier_getString, $view->field('sidebarimage'), t("Choose Image"), $sidebarimage_o); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('shorttestimonial'), t("Short Testimonial")); ?>
    <?php echo isset($btFieldsRequired) && in_array('shorttestimonial', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->textarea($view->field('shorttestimonial'), $shorttestimonial, array (
  'rows' => 5,
)); ?>
</div>

<div class="form-group">
    <?php
    if (isset($fullimage) && $fullimage > 0) {
        $fullimage_o = File::getByID($fullimage);
        if (!is_object($fullimage_o)) {
            unset($fullimage_o);
        }
    } ?>
    <?php echo $form->label($view->field('fullimage'), t("Full Image (800x400)")); ?>
    <?php echo isset($btFieldsRequired) && in_array('fullimage', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make("helper/concrete/asset_library")->image('ccm-b-custom_testimonial-fullimage-' . $identifier_getString, $view->field('fullimage'), t("Choose Image"), $fullimage_o); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('fulltestimonial'), t("Full Testimonial")); ?>
    <?php echo isset($btFieldsRequired) && in_array('fulltestimonial', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make('editor')->outputBlockEditModeEditor($view->field('fulltestimonial'), $fulltestimonial); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('buttonlabel'), t("View More Button Label")); ?>
    <?php echo isset($btFieldsRequired) && in_array('buttonlabel', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('buttonlabel'), $buttonlabel); ?>
</div>