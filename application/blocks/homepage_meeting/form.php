<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php  echo $form->label('Header', t("Header")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('Header', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo $form->text($view->field('Header'), $Header, array (
  'maxlength' => 255,
  'placeholder' => NULL,
)); ?>
</div>

<?php  $al = Core::make("helper/concrete/asset_library"); ?>
<div class="form-group">
    <?php 
    if ($Image > 0) {
        $Image_o = File::getByID($Image);
        if ($Image_o->isError()) {
            unset($Image_o);
        }
    } ?>
    <?php  echo $form->label('Image', t("Image")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('Image', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo $al->file($view->field('ccm-b-file-Image'), "Image", t("Choose File"), $Image_o); ?>
</div>

<div class="form-group">
    <?php  echo $form->label('Title', t("Title")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('Title', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo $form->textarea($view->field('Title'), $Title, array (
  'rows' => 5,
)); ?>
</div>

<div class="form-group">
    <?php  echo $form->label('Date', t("Date")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('Date', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo $form->textarea($view->field('Date'), $Date, array (
  'rows' => 5,
)); ?>
</div>

<div class="form-group">
    <?php  echo $form->label('Description_1', t("Description")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('Description_1', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo $form->textarea($view->field('Description_1'), $Description_1, array (
  'rows' => 5,
)); ?>
</div>

<div class="form-group">
    <?php  echo $form->label('URL', t("URL")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('URL', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo Core::make("helper/form/page_selector")->selectPage($view->field('URL'), $URL); ?>    <?php  echo $form->label('URL_text', t("URL") . " " . t("Text")); ?>
    <?php  echo $form->text($view->field('URL_text'), $URL_text, array()); ?></div>