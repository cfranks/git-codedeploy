<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>

<div class="bio">
<div class="bio-img">
<?php  if ($img){ ?>
    <img src="<?php  echo $img->getURL(); ?>" alt="<?php  echo $img->getTitle(); ?>"/><?php  } ?>
</div>    
<?php  if (isset($content) && trim($content) != "") { ?>
    <div class="bio-text"><?php  echo $content; ?></div></div><?php  } ?>