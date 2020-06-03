<?php defined("C5_EXECUTE") or die("Access Denied."); ?>
<div class="feature-card">
<?php if ($cardimg) { ?>
    <img src="<?php echo $cardimg->getURL(); ?>" alt="<?php echo $cardimg->getTitle(); ?>"/><?php } ?>
<?php if (isset($cardtitle) && trim($cardtitle) != "") { ?>
    <div class="text">
	<h3><?php echo h($cardtitle); ?></h3><?php } ?>
<?php if (isset($cardblurb) && trim($cardblurb) != "") { ?>
    <p><?php echo h($cardblurb); ?></p><?php } ?>
<?php if (isset($cardmoreinfo) && trim($cardmoreinfo) != "") { ?>
    <p><?php echo h($cardmoreinfo); ?></p><?php } ?>
<?php if (!empty($cardlink) && ($cardlink_c = Page::getByID($cardlink)) && !$cardlink_c->error && !$cardlink_c->isInTrash()) {
    ?>
	</div>
    <?php echo '<a href="' . $cardlink_c->getCollectionLink() . '">' . (isset($cardlink_text) && trim($cardlink_text) != "" ? $cardlink_text : $cardlink_c->getCollectionName()) . '</a>';
?><?php } ?>
</div>