<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>
<div class="form-group clearfix">
    <div>
        <?php echo $form->label('bTotal', 'Total Records(0 for all approved)'); ?>
    </div>
    <div>
        <?php echo $form->number('bTotal', $bTotal); ?>
    </div>
</div>

<div class="form-group clearfix">
    <div>
        <?php echo $form->label('bHeight', 'Height of the div'); ?>
    </div>
    <div>
        <?php echo $form->number('bHeight', $bHeight, ['min' => 250]); ?>
    </div>
</div>