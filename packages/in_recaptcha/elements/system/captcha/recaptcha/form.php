<?php  
defined('C5_EXECUTE') or die('Access denied.');

/** @var \Concrete\Core\Form\Service\Form $form */
$form = Core::make('helper/form');
$config = Package::getByHandle('in_recaptcha')->getConfig();
?>

<div class="form-group">
    <?php  echo  $form->label('type', t('reCaptcha Type')) ?>
    <?php  echo  $form->select('type', ['v2' => 'Version2 reCaptcha', 'invisible' => 'Invisible Recaptcha'], $config->get('captcha.type', '')) ?>
</div>

<p><?php  echo  t('A site key and secret key must be provided. They can be obtained from the <a href="%s" target="_blank">reCAPTCHA website</a>.', 'https://www.google.com/recaptcha/admin') ?></p>

<div class="form-group">
    <?php  echo  $form->label('site', t('Site Key')) ?>
    <?php  echo  $form->text('site', $config->get('captcha.site_key', '')) ?>
</div>

<div class="form-group">
    <?php  echo  $form->label('secret', t('Secret Key')) ?>
    <?php  echo  $form->text('secret', $config->get('captcha.secret_key', '')) ?>
</div>
