<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php echo $form->label($view->field('modalid'), t("Modal ID") . ' <i class="fa fa-question-circle launch-tooltip" data-original-title="' . t("A unique name that only this modal will use.") . '"></i>'); ?>
    <?php echo isset($btFieldsRequired) && in_array('modalid', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('modalid'), $modalid, array (
  'maxlength' => 255,
)); ?>
</div>

<div class="form-group">
    <?php
    if (isset($triggerimg) && $triggerimg > 0) {
        $triggerimg_o = File::getByID($triggerimg);
        if (!is_object($triggerimg_o)) {
            unset($triggerimg_o);
        }
    } ?>
    <?php echo $form->label($view->field('triggerimg'), t("Trigger Image (600x350)")); ?>
    <?php echo isset($btFieldsRequired) && in_array('triggerimg', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make("helper/concrete/asset_library")->image('ccm-b-feature_modal-triggerimg-' . $identifier_getString, $view->field('triggerimg'), t("Choose Image"), $triggerimg_o); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('triggercontent'), t("Trigger Content")); ?>
    <?php echo isset($btFieldsRequired) && in_array('triggercontent', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make('editor')->outputBlockEditModeEditor($view->field('triggercontent'), $triggercontent); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('modaltitle'), t("Modal Title")); ?>
    <?php echo isset($btFieldsRequired) && in_array('modaltitle', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('modaltitle'), $modaltitle, array (
  'maxlength' => 255,
)); ?>
</div>

<div class="form-group">
    <?php
    if (isset($modalimg) && $modalimg > 0) {
        $modalimg_o = File::getByID($modalimg);
        if (!is_object($modalimg_o)) {
            unset($modalimg_o);
        }
    } ?>
    <?php echo $form->label($view->field('modalimg'), t("Modal Image (400x500)")); ?>
    <?php echo isset($btFieldsRequired) && in_array('modalimg', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make("helper/concrete/asset_library")->image('ccm-b-feature_modal-modalimg-' . $identifier_getString, $view->field('modalimg'), t("Choose Image"), $modalimg_o); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('modalcontent'), t("Modal Content")); ?>
    <?php echo isset($btFieldsRequired) && in_array('modalcontent', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make('editor')->outputBlockEditModeEditor($view->field('modalcontent'), $modalcontent); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('modalcolleft'), t("Modal Column left")); ?>
    <?php echo isset($btFieldsRequired) && in_array('modalcolleft', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make('editor')->outputBlockEditModeEditor($view->field('modalcolleft'), $modalcolleft); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('modalcolright'), t("Modal Column Right")); ?>
    <?php echo isset($btFieldsRequired) && in_array('modalcolright', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make('editor')->outputBlockEditModeEditor($view->field('modalcolright'), $modalcolright); ?>
</div>