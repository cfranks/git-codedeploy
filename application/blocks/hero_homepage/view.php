<?php defined("C5_EXECUTE") or die("Access Denied."); ?>
<?php if ($heroimg) { ?>
    <figure class="parallax-1"><img src="<?php echo $heroimg->getURL(); ?>" alt="<?php echo $heroimg->getTitle(); ?>" class="zoom"/></figure><?php } ?>
<div class="text">
<?php if (isset($heroheader) && trim($heroheader) != "") { ?>
    <h1><?php echo h($heroheader); ?></h1><?php } ?>
<?php if (isset($blurb) && trim($blurb) != "") { ?>
    <p><?php echo h($blurb); ?></p><?php } ?>
<div class="cta-btns">
	<?php if (!empty($btnone) && ($btnone_c = Page::getByID($btnone)) && !$btnone_c->error && !$btnone_c->isInTrash()) { ?>
    <?php echo '<a href="' . $btnone_c->getCollectionLink() . '" class="btn-theme">' . (isset($btnone_text) && trim($btnone_text) != "" ? $btnone_text : $btnone_c->getCollectionName()) . '</a>'; ?><?php } ?>
	<?php if (!empty($btntwo) && ($btntwo_c = Page::getByID($btntwo)) && !$btntwo_c->error && !$btntwo_c->isInTrash()) { ?>
    <?php echo '<a href="' . $btntwo_c->getCollectionLink() . '" class="btn-theme">' . (isset($btntwo_text) && trim($btntwo_text) != "" ? $btntwo_text : $btntwo_c->getCollectionName()) . '</a>';
?><?php } ?>
</div>
<?php if (isset($imgcaption) && trim($imgcaption) != "") { ?>
    <div class="caption"><?php echo $imgcaption; ?></div><?php } ?>
</div>