<ol class="form-steps">
    <li <?php echo $step=='enroll' ? 'class="active"' : '';?>>
        <?php if (isset($data['StepCompleted']) && $data['StepCompleted']>=1 && $step!='enroll') { ?>
            <a href="<?php echo $this->action('enrollment')?>">
        <?php } ?>
        <?php echo translate('DonationDetailsTab', $bLanguage, false) ?>
        <?php if (isset($data['StepCompleted']) && $data['StepCompleted']>=1 && $step!='enroll') { ?>
            </a>
        <?php } ?>
    </li>
    <li <?php echo $step=='contact' ? 'class="active"' : '';?>>
        <?php if (isset($data['StepCompleted']) && $data['StepCompleted']>=1 && $step!='contact') { ?>
            <a href="<?php echo $this->action('contact')?>" onclick="return submitTheStep('step-1-submit')">
        <?php } ?>
        <?php echo translate('ContactDetailsTab', $bLanguage, false) ?>
        <?php if (isset($data['StepCompleted']) && $data['StepCompleted']>=1 && $step!='contact') { ?>
            </a>
        <?php } ?>
    </li>
    <li <?php echo $step=='payment' ? 'class="active"' : '';?>>
        <?php if (isset($data['StepCompleted']) && $data['StepCompleted']>=2 && $step!='payment') { ?>
            <a href="<?php echo $this->action('payment')?>" onclick="return submitTheStep('step-2-submit')">
        <?php } ?>
        <?php echo translate('PaymentDetailsTab', $bLanguage, false) ?>
        <?php if (isset($data['StepCompleted']) && $data['StepCompleted']>=2 && $step!='payment') { ?>
            </a>
        <?php } ?>
    </li>
</ol>
<?php if (isset($error_messages)) {?>
    <div style="margin-top:20px"  class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <p><?php translate('CorrectFollowing', $bLanguage) ?></p>
        <ul>
            <?php
                $message_shown = array();
                foreach ($error_messages as $error_message) {
                    if (!in_array($error_message->getMessage(), $message_shown)) {
            ?>
                        <li>
                            <?php $message_shown[] = $error_message->getMessage();?>
                            <?php translate($error_message->getMessage(), $bLanguage, true, $error_message->getMessage());?>
                        </li>
            <?php
                    }
                }
        ?>
        </ul>
    </div>
<?php }?>
<?php if (isset($_SESSION['message'])) {?>
    <div style="margin-top:20px"  class="alert alert-info">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php translate($_SESSION['message'], $bLanguage, true);
            unset($_SESSION['message']);
        ?>
    </div>
<?php }?>