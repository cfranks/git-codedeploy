<?php defined("C5_EXECUTE") or die("Access Denied."); ?>
<div class="card">
<?php if ($cardbkgimg) { ?>
    <img src="<?php echo $cardbkgimg->getURL(); ?>" alt="<?php echo $cardbkgimg->getTitle(); ?>"/><?php } ?>
<?php if (isset($cardcontent) && trim($cardcontent) != "") { ?>
    <div class="text"><?php echo $cardcontent; ?></div></div><?php } ?>