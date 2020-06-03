<?php defined("C5_EXECUTE") or die("Access Denied."); ?>
<div class="feature">
<?php if ($img) { ?>
    <img src="<?php echo $img->getURL(); ?>" alt="<?php echo isset($altText) && !empty($altText) ? $altText : $img->getTitle(); ?>"/><?php } ?>
<?php if (isset($content) && trim($content) != "") { ?>
    <div class="text"><?php echo $content; ?></div></div><?php } ?>