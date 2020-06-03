
<div class="ccm-pane-body">
    <div class="ccm-dashboard-header-buttons">
        <?php echo($ih->button(t('Add New Language'), $this->action('add'), 'left', 'btn-primary')); ?>
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
            $percentage = number_format($percentage,1);
            ?>
            <tr>
                <td><?php echo $value; ?></td>
                <td><?php echo $percentage >= 100 ? 100: $percentage; ?> %</td>
                <td><a href="<?php echo $this->action('edit', $key); ?>" class="btn btn-sm btn-primary">Configure</a></td>
            </tr>
        <?php } ?>
    </table>
</div> 