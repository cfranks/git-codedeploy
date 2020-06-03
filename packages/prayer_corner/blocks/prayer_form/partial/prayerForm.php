<?php
defined('C5_EXECUTE') or die("Access Denied.");
$captcha = Core::make('captcha');
?>
<?php if (isset($message_custom)) { ?>
    <div style="margin-top:20px"  class="alert alert-info">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $message_custom; ?>
    </div>
<?php } else { ?>
    <form class="form" method="POST" action="<?php echo $this->action('submit'); ?>">
        <?php if (isset($error_messages)) { ?>
            <div style="margin-top:20px"  class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <p><?php translatepc('CorrectFollowing', $bLanguage); ?></p>
                <ul>
                    <?php
                    $message_shown = array();
                    foreach ($error_messages as $error_message) {
                        if (!in_array($error_message->getMessage(), $message_shown)) {
                            ?>
                            <li><?php $message_shown[] = $error_message->getMessage(); ?>
                            <?php translatepc($error_message->getMessage(), $bLanguage); ?>
                            </li> 
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        <?php } ?>
        <div class="clearfix"></div>
        <div class="form-group">
            <?php echo $form->label('first_name', translatepc('FirstName', $bLanguage, false), ['class'=>'required']);?>
            <?php echo $form->text('first_name', $data['first_name'], ['maxlength' => 45, 'placeholder' => translatepc('FirstName', $bLanguage, false)]); ?>
        </div>
        <div class="form-group">
            <?php echo $form->label('last_name', translatepc('LastName', $bLanguage, false), ['class'=>'required']);?>
            <div class="text-muted"><?php translatepc('LastNameExtra', $bLanguage) ?></div> 
            <?php echo $form->text('last_name', $data['last_name'], ['maxlength' => 45, 'placeholder' => translatepc('LastName', $bLanguage, false)]); ?>
        </div>
        <div class="form-group">
            <?php echo $form->label('email', translatepc('EmailAddress', $bLanguage, false),['class'=>'required']);?>
            <div class="text-muted"><?php translatepc('EmailAddressExtra', $bLanguage); ?></div> 
            <?php echo $form->text('email', $data['email'], ['placeholder' => translatepc('EmailAddress', $bLanguage, false)]); ?>
        </div>
        <div class="form-group">
            <?php echo $form->label('city', translatepc('City', $bLanguage, false));?> 
            <?php echo $form->text('city', $data['city'], ['placeholder' => translatepc('City', $bLanguage, false)]); ?>
        </div>
        <div class="form-group">
            <?php echo $form->label('country', translatepc('Country', $bLanguage, false));?> 
            <?php echo $form->select('country', ['' => translatepc('SelectCountry', $bLanguage, false)] + $countries, $data['country'], ['placeholder' => translatepc('Country', $bLanguage, false)]); ?>
        </div>
        <div class="form-group">
            <?php echo $form->label('prayer_request', translatepc('PrayerRequest', $bLanguage, false), ['class'=>'required']);?> 
            <?php echo $form->textarea('prayer_request', $data['prayer_request'], 
                ['placeholder' => translatepc('PrayerRequest', $bLanguage, false), 'rows' => 5]); ?>
        </div>
        <div class="form-group">
            <label>
                <?php echo $form->checkbox('post_public', 1); ?> 
                <?php translatepc('PostPublicCheckbox', $bLanguage) ?>
            </label>
        </div>
        <div class="form-group">
            <label>
                <?php echo $form->checkbox('email_consent', 1); ?> 
                <?php translatepc('ConsentCheckbox', $bLanguage) ?>
            </label>
        </div>
        <?php echo $form->hidden('bID', $bID); ?>
        <?php echo $form->hidden('language', $bLanguage); ?>
        <div class="">
            <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal"><?php translatepc('PolicyLabel', $bLanguage) ?></a>
        </div>
        <div class="form-group">
            <label class="control-label"><?=$captcha->label()?></label>
            <div><?php $captcha->display(); ?></div>
            <div><?php $captcha->showInput(); ?></div>
        </div>
        <div class="actions">
            <button type="submit" class="btn btn-primary"><?php translatepc('SubmitLabel', $bLanguage); ?></button>
        </div>
    </form>
<?php } ?>
<style>
.required:after {
    content: "*";
    color: red;
}
</style>