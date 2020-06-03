<?php defined("C5_EXECUTE") or die("Access Denied."); ?>
<div class="full-feature">
<?php if ($img) { ?>
    <img src="<?php echo $img->getURL(); ?>" alt="<?php echo $img->getTitle(); ?>"/><?php } ?>
<?php if (isset($content) && trim($content) != "") { ?>
    <div class="container">
<div class="text"><?php echo $content; ?></div>
</div>
</div><?php } ?>