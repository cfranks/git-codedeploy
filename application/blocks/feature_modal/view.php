<?php defined("C5_EXECUTE") or die("Access Denied."); ?>
<?php if (isset($modalid) && trim($modalid) != "") ?>
    
<button class="feature-modal-trigger" data-toggle="modal" data-target="#<?php echo h($modalid); ?><?php?>">
<?php if ($triggerimg) { ?>
    <img src="<?php echo $triggerimg->getURL(); ?>" alt="<?php echo $triggerimg->getTitle(); ?>"/><?php } ?>
<?php if (isset($triggercontent) && trim($triggercontent) != "") { ?>
    <?php echo $triggercontent; ?><?php } ?>
	<span>Learn More</span>
</div>
<div class="modal fade feature-modal" tabindex="-1" role="dialog" aria-labelledby="<?php echo h($modalid); ?><?php?>" id="<?php echo h($modalid); ?><?php?>">
<div class="modal-dialog" role="document">
<h2>
<?php if (isset($modaltitle) && trim($modaltitle) != "") { ?>
    <?php echo h($modaltitle); ?><?php } ?>
<a type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a></h2>
<div class="modal-inner">
<?php if ($modalimg) { ?>
    <img src="<?php echo $modalimg->getURL(); ?>" alt="<?php echo $modalimg->getTitle(); ?>"/><?php } ?>
<div class="modal-text">
<?php if (isset($modalcontent) && trim($modalcontent) != "") { ?>
    <?php echo $modalcontent; ?><?php } ?>
<?php if (isset($modalcolleft) && trim($modalcolleft) != "") { ?>
    <div class="left"><?php echo $modalcolleft; ?></div><?php } ?>
<?php if (isset($modalcolright) && trim($modalcolright) != "") { ?>
    <div class="right"><?php echo $modalcolright; ?></div><?php } ?>
</div></div></div></button>