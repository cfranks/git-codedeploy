<?php defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php
    if (isset($heroimg) && $heroimg > 0) {
        $heroimg_o = File::getByID($heroimg);
        if (!is_object($heroimg_o)) {
            unset($heroimg_o);
        }
    } ?>
    <?php echo $form->label($view->field('heroimg'), t("Hero Image")); ?>
    <?php echo isset($btFieldsRequired) && in_array('heroimg', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make("helper/concrete/asset_library")->image('ccm-b-hero_homepage-heroimg-' . $identifier_getString, $view->field('heroimg'), t("Choose Image"), $heroimg_o); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('heroheader'), t("Header")); ?>
    <?php echo isset($btFieldsRequired) && in_array('heroheader', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->text($view->field('heroheader'), $heroheader, array (
  'maxlength' => 255,
)); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('blurb'), t("Blurb")); ?>
    <?php echo isset($btFieldsRequired) && in_array('blurb', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo $form->textarea($view->field('blurb'), $blurb, array (
  'rows' => 5,
)); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('btnone'), t("Button One")); ?>
    <?php echo isset($btFieldsRequired) && in_array('btnone', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make("helper/form/page_selector")->selectPage($view->field('btnone'), $btnone); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('btnone_text'), t("Button One") . " " . t("Text")); ?>
    <?php echo $form->text($view->field('btnone_text'), $btnone_text, []); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('btntwo'), t("Button Two")); ?>
    <?php echo isset($btFieldsRequired) && in_array('btntwo', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make("helper/form/page_selector")->selectPage($view->field('btntwo'), $btntwo); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('btntwo_text'), t("Button Two") . " " . t("Text")); ?>
    <?php echo $form->text($view->field('btntwo_text'), $btntwo_text, []); ?>
</div>

<div class="form-group">
    <?php echo $form->label($view->field('imgcaption'), t("Image Caption")); ?>
    <?php echo isset($btFieldsRequired) && in_array('imgcaption', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php echo Core::make('editor')->outputBlockEditModeEditor($view->field('imgcaption'), $imgcaption); ?>
</div>