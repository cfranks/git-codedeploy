<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="form-group">
    <?php  echo $form->label('Title', t("Title")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('Title', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo $form->text($view->field('Title'), $Title, array (
  'maxlength' => 255,
  'placeholder' => NULL,
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
    <?php  echo $form->label('LinkURL', t("LinkURL")); ?>
    <?php  echo isset($btFieldsRequired) && in_array('LinkURL', $btFieldsRequired) ? '<small class="required">' . t('Required') . '</small>' : null; ?>
    <?php  echo Core::make("helper/form/page_selector")->selectPage($view->field('LinkURL'), $LinkURL); ?>    <?php  echo $form->label('LinkURL_text', t("LinkURL") . " " . t("Text")); ?>
    <?php  echo $form->text($view->field('LinkURL_text'), $LinkURL_text, array()); ?></div>