<div class="ccm-pane-body">
    <form action="<?php echo $this->action('save_item'); ?>" method="post">
        <div class="form-group">
            <?php echo $form->label('key', t('Key'), array('class' => 'required')); ?>
            <?php echo $form->text('key', ''); ?> 
        </div>
        <div class="form-group">
            <?php echo $form->label('default', t('Default Value'), array('class' => 'required')); ?>
            <?php echo $form->text('default', ''); ?> 
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary pull-right">Add Item</button>
        </div>
    </div>
</div>