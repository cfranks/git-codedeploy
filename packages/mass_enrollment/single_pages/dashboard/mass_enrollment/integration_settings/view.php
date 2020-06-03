<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
$ih = Core::make('helper/concrete/ui');
$service = Core::make('helper/concrete/file_manager');
?>
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
    <?php if(isset($message_test_mode)) { ?>
    	<div style="margin-top:20px"  class="alert alert-warning">
            <p style="font-size:20px">The Site is in test mode.Currently using test credentials.</p>
        </div>
    <?php } ?>
      <form method="post" action='<?php echo $this->action('save'); ?>'>
            <h3>Safe Save Credentials</h3>
            <hr/>
            <div class="row">
                <div class="form-group clearfix">
                    <div class="col-sm-4">
                        <?php echo $form->label('safeUsername', 'Username', ['class'=>'required']);?>
                    </div>
                    <div class="col-sm-8">
                        <?php echo $form->text('safeUsername',$data['safeUsername'], ['placeholder'=>'Username']); ?>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <div class="col-sm-4">
                        <?php echo $form->label('safePassword', 'Passowrd', ['class'=>'required']);?>
                    </div>
                    <div class="col-sm-8">
                        <?php echo $form->password('safePassword',$data['safePassword'], ['placeholder'=>'Password']); ?>
                    </div>
                </div>
            </div>
            <h3>Donor Perfect Credentials</h3>
            <hr/>
            <div class="row">
                <div class="form-group clearfix">
                    <div class="col-sm-4">
                        <?php echo $form->label('donorPerfectUsername', 'Username', ['class'=>'required']);?>
                    </div>
                    <div class="col-sm-8">
                        <?php echo $form->text('donorPerfectUsername',$data['donorPerfectUsername'], ['placeholder'=>'Username']); ?>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <div class="col-sm-4">
                        <?php echo $form->label('donorPerfectPassword', 'Passowrd', ['class'=>'required']);?>
                    </div>
                    <div class="col-sm-8">
                        <?php echo $form->password('donorPerfectPassword',$data['donorPerfectPassword'],['placeholder'=>'Password']); ?>
                    </div>
		</div>
		<div class="form-group clearfix">
                    <div class="col-sm-4">
                        <?php echo $form->label('donorPerfectLogin', 'Login using API Key');?>
                    </div>
                    <div class="col-sm-8">
                        <?php echo $form->checkbox('donorPerfectLogin',1,$data['donorPerfectLogin'] == 1 ? 1 : 0); ?>
                    </div>
                </div>
                </div>
            </div>
            <?php $ih = Core::make('helper/concrete/ui'); ?>
            <?php echo($ih->submit(t('Save'), 'save', 'right', 'btn-success')); ?>
            <div class="clearfix"></div>
      </form>
</div>
<style>
    .required:after {
        content: "*";
        color: red;
    }
</style>