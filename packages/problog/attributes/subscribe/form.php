<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
$form = Loader::helper('form');
?>
<div class="clearfix">
	<div class="input">
		<?php  echo $form->checkbox('send_to_subscribers',1).' Yes, send email to subscribers on save.';?>
	</div>
</div>
