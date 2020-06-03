<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
?>
<div class="form-group clearfix">
    <div>
        <?php echo $form->label('bLanguage', 'Select Language'); ?>
    </div>
    <div>
        <?php echo $form->select('bLanguage', ['' => '-- Select Language --'] + $languages, $bLanguage); ?>
    </div>
</div>