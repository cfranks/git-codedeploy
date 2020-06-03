<div class="ccm-pane-body">
    <?php if (isset($error_messages)) { ?>
        <div style="margin-top:20px"  class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <p>Please correct the following errors before proceeding</p>
            <ul>
                <?php
                $message_shown = array();
                foreach ($error_messages as $error_message) {
                    if (!in_array($error_message->getMessage(), $message_shown)) {
                        ?>
                        <li><?php echo $message_shown[] = $error_message->getMessage(); ?></li> 
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
        <?php
    }
    ?>
    <form action="<?php echo $this->action('set_language'); ?>" method="post">
        <div class="row">
            <div class="col-sm-10">
                <div class="form-group">
                    <?php echo $form->label('language', t('Language'), array('class' => 'required')); ?>
                    <?php echo $form->select('language', ['Default' => 'Default'] + $languages); ?> 
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <?php echo $form->label('language', t('&nbsp;'), ['style' => 'width: 100%']); ?>
                    <button type="submit" class="btn btn-primary btn-block">Change</button>
                </div>
            </div>
        </div>
    </form>
    <form  action="<?php echo $this->action('save_email'); ?>" method="post" class="well">
        <?php echo $form->hidden('language', $language); ?>
        <div class="form-group">
            <?php echo $form->label('AdminEmail', t('Admin Email'), array('class' => 'required')); ?>
            <?php echo $form->text('AdminEmail', $AdminEmail); ?>
        </div>
        <div class="form-group">
            <?php echo $form->label('FromEmail', t('From Email'), array('class' => 'required')); ?>
            <?php echo $form->email('FromEmail', $FromEmail); ?>
        </div>
        <div class="form-group">
            <?php echo $form->label('FromName', t('From Name'), array('class' => 'required')); ?>
            <?php echo $form->text('FromName', $FromName); ?>
        </div>
        <div class="form-group">
            <?php echo $form->label('UserEmailSub', t('User Email Subject'), array('class' => 'required')); ?>
            <?php echo $form->text('UserEmailSub', $UserEmailSub); ?>
        </div>
        <div class="form-group">
            <?php echo $form->label('UserEmailTemplate', t('User Email Template'), array('class' => 'required')); ?>
            <div class="row">
                <div class="col-sm-8">
                    <?php
                        $editor = Core::make('editor');
                        echo $editor->outputStandardEditor('UserEmailTemplate', 
                        (isset($_POST['UserEmailTemplate']) ? $_POST['UserEmailTemplate'] : ($UserEmailTemplate ? $UserEmailTemplate : '')));
                    ?>
                </div>
                <div class="col-sm-4">
                    <?php $merge = Config::get('mass_enrollment::custom.MergeKeys'); ?>
                    <p><strong>Merge Keys</strong></p>
                    <?php foreach($merge as $key => $mer) { ?>
                        <span><?=$mer?>: <strong><?=$key?></strong></span><br/>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->label('AdminEmailSub', t('Admin Email Subject'), array('class' => 'required')); ?>
            <?php echo $form->text('AdminEmailSub', $AdminEmailSub); ?>
        </div>
        <div class="form-group">
            <?php echo $form->label('AdminEmailTemp', t('Admin Email Template'), array('class' => 'required')); ?>
            <div class="row">
                <div class="col-sm-8">
                    <?php
                        $editor = Core::make('editor');
                        echo $editor->outputStandardEditor('AdminEmailTemp', 
                        (isset($_POST['AdminEmailTemp']) ? $_POST['AdminEmailTemp'] : ($AdminEmailTemp ? $AdminEmailTemp : '')));
                    ?>
                </div>
                <div class="col-sm-4">
                    <?php $merge = Config::get('mass_enrollment::custom.MergeKeys'); ?>
                    <p><strong>Merge Keys</strong></p>
                    <?php foreach($merge as $key => $mer) { ?>
                        <span><?=$mer?>: <strong><?=$key?></strong></span><br/>
                    <?php } ?>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <?php echo $form->label('NotifyEmailSub', t('Notification Email Subject'), array('class' => 'required')); ?>
            <?php echo $form->text('NotifyEmailSub', $NotifyEmailSub); ?>
        </div>
        <div class="form-group">
            <?php echo $form->label('NotifyEmailTemp', t('Notify Email Template'), array('class' => 'required')); ?>
            <div class="row">
                <div class="col-sm-8">
                    <?php
                        $editor = Core::make('editor');
                        echo $editor->outputStandardEditor('NotifyEmailTemp', 
                        (isset($_POST['NotifyEmailTemp']) ? $_POST['NotifyEmailTemp'] : ($NotifyEmailTemp ? $NotifyEmailTemp : '')));
                    ?>
                </div>
                <div class="col-sm-4">
                    <?php $merge = Config::get('mass_enrollment::custom.MergeKeys'); ?>
                    <p><strong>Merge Keys</strong></p>
                    <?php foreach($merge as $key => $mer) { ?>
                        <span><?=$mer?>: <strong><?=$key?></strong></span><br/>
                    <?php } ?>
                </div>
            </div>
        </div>
        <hr/>
        <div class="form-actions">
            <button type="submit" class="btn btn-success pull-right">Submit</button>
        </div>
        <div class="clearfix"></div>
    </form>    
</div>