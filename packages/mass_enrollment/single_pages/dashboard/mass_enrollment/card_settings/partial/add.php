<div class="ccm-pane-body">
    <div class="ccm-dashboard-header-buttons">
        <?php echo($ih->button(t('Back to Listing'), $this->action(''), 'left', 'btn-default')); ?>
    </div>
    <form action="<?php echo $this->action('save_language'); ?>" method="post">
        <div class="form-group">
            <?php echo $form->label('language', t('Language'), array('class' => 'required')); ?>
            <?php echo $form->select('language', $languages); ?> 
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary pull-right">Add Language</button>
        </div>
    </div>
</div>