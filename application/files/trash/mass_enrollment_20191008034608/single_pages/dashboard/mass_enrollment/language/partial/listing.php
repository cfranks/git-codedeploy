
<div class="ccm-pane-body">
    <div class="ccm-dashboard-header-buttons">
        <?php echo($ih->button(t('Add New'), $this->action('add'), 'left', 'btn-primary')); ?>
    </div>

    <table class="table table-stripped">
        <tr>
            <th><?php echo t('Language'); ?></th>
            <th><?php echo t('% Configured'); ?></th>
            <th></th>
        </tr>
        <?php foreach($selected_languages as $key=>$value) { 
            $langelements = Config::get('mass_enrollment::'.$key);
            $langelements = array_filter($langelements);
            $percentage = (count($langelements)/count($langkeys)) * 100;
            $percentage = ceil($percentage);
            ?>
            <tr>
                <td><?php echo $value; ?></td>
                <td><?php echo $percentage; ?>%</td>
                <td><a href="<?php echo $this->action('edit', $key); ?>" class="btn btn-sm btn-primary">Configure</a></td>
            </tr>
        <?php } ?>
    </table>
</div> 