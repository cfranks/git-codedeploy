<div class="ccm-pane-body">
    <div class="ccm-dashboard-header-buttons">
        <?php echo($ih->button(t('Back to Listing'), $this->action(''), 'left', 'btn-default')); ?>
    </div>
    <form action="<?php echo $this->action('save_translation'); ?>" method="post">
        <table class="footable table table-striped" style="table-layout: fixed;" data-filtering="true" data-paging="true" data-paging-size="1000">
            <thead>
            <tr>
                <th>Default</th>
                <th></th>
            </tr>
            </thead>
            <?php foreach($langkeys as $key => $value) { 
                //Config::get('mass_enrollment::'.$language . '.'. $key);
                ?>
                <tr>
                    <td><?php echo $value; ?></td>
                    <td data-filter-value="<?php echo Config::get('mass_enrollment::'.$language . '.'. $key); ?>">
                        <?php echo $form->textarea($language . '.'. $key, Config::get('mass_enrollment::'.$language . '.'. str_replace(' ', '.', $key)), ['placeholder' => 'Type your translation here...']); ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <div class="ccm-dashboard-form-actions-wrapper">
            <div class="ccm-dashboard-form-actions">
                <?php echo($ih->submit(t('Save Transalation'), 'directory-form', 'right', 'btn-success')); ?>
            </div>
        </div>
    </form>
</div>
<style>
.footable-filtering-search .input-group-btn .dropdown-toggle {
    display: none;
}
</style>