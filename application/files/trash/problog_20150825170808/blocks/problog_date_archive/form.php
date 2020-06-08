<?php        defined('C5_EXECUTE') or die("Access Denied.");
$form = Loader::helper('form');
$c = Page::getCurrentPage();
?>

<div class="ccm-block-field-group">
	<h4><?php   echo  tc('Input of a title', 'Display Title'); ?></h4>
	<?php   echo $form->text('title',$title);?>
	<br/><br/>
	<h4><?php   echo t('# of Months to display'); ?></h4>
	<?php   echo $form->text('numMonths',$numMonths, array('size'=>3));?>
</div>

<div class="ccm-block-field-group">
	<h4><?php   echo t('Months Link to Page'); ?></h4>
	<div class="ccm-tags">
		<?php  
        $form_selector = Loader::helper('form/page_selector');
        print $form_selector->selectPage('targetCID', $targetCID);
        ?>
	</div>
	<br/><br/>
</div>
