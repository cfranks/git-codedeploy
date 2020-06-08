<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>

<?php  $al = Core::make("helper/concrete/asset_library"); ?>
<div class="form-group">
    <?php 
    if ($sliderImage > 0) {
        $sliderImage_o = File::getByID($sliderImage);
        if ($sliderImage_o->isError()) {
            unset($sliderImage_o);
        }
    } ?>
    <?php  echo $form->label('sliderImage', t("Slider Image")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('sliderImage', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo $al->file($view->field('ccm-b-file-sliderImage'), "sliderImage", t("Choose File"), $sliderImage_o); ?>
</div>

<div class="form-group">
    <?php  echo $form->label('TitleText', t("H3 Title Text")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('TitleText', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo $form->text($view->field('TitleText'), $TitleText, array (
  'maxlength' => 255,
  'placeholder' => NULL,
)); ?>
</div>

<div class="form-group">
    <?php  echo $form->label('HeaderText', t("H2 Header Text")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('HeaderText', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo $form->text($view->field('HeaderText'), $HeaderText, array (
  'maxlength' => 255,
  'placeholder' => NULL,
)); ?>
</div>

<div class="form-group">
    <?php  echo $form->label('description_1', t("Description")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('description_1', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo $form->textarea($view->field('description_1'), $description_1, array (
  'rows' => 5,
)); ?>
</div>

<div class="form-group">
    <?php  echo $form->label('LinkTo', t("LinkTo")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('LinkTo', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo Core::make("helper/form/page_selector")->selectPage($view->field('LinkTo'), $LinkTo); ?>    <?php  echo $form->label('LinkTo_text', t("LinkTo") . " " . t("Text")); ?>
    <?php  echo $form->text($view->field('LinkTo_text'), $LinkTo_text, array()); ?></div>