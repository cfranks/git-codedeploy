
<div class="ccm-pane-body">
    <div class="ccm-dashboard-header-buttons">
        <?php //echo($ih->button(t('Add New'), $this->action('add'), 'left', 'btn-primary')); ?>
    </div>
    <h3>Language Settings</h3>
    <table class="table table-stripped">
        <tr>
            <th><?php echo t('Language'); ?></th>
            <th><?php echo t('% Configured'); ?></th>
            <th></th>
        </tr>
        <?php foreach($selected_languages as $key=>$value) { 
            $langelements = Config::get('mass_enrollment::card.'.$key);
            if (is_array($langelements)) {
                $langelements = array_filter($langelements);
            }
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

    <h3>Date Format</h3>
    <form action="<?php echo $this->action('save_dates'); ?>" method="POST">
    <table class="table table-stripped">
        <tr>
            <th><?php echo t('Language'); ?></th>
            <th><?php echo t('Date Format'); ?></th>
        </tr>
        <?php foreach($selected_languages as $key=>$value) { 
            $carddateformat = Config::get('mass_enrollment::carddateformat.'.$key);
        
            ?>
            <tr>
                <td><?php echo $value; ?></td>
                <td><?php echo $form->text($key, $carddateformat, ['placeholder' => 'Use M for Month, D for date and Y for Year. Default: M/D/Y']); ?></td>
            </tr>
        <?php } ?>
    </table>
    <button type="submit" class="btn btn-primary pull-right">Save Date Format</button>
    </form>
</div> 