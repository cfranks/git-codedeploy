<?php defined("C5_EXECUTE") or die("Access Denied."); ?>
<?php if ($featureimg) { ?>
    <div class="fun-img"><img src="<?php echo $featureimg->getURL(); ?>" alt="<?php echo $featureimg->getTitle(); ?>"/></div><?php } ?>
<div class="feature">
<i class="fa fa-check-circle-o"></i>
<?php if (isset($featurehdr) && trim($featurehdr) != "") { ?>
    <h3><?php echo $featurehdr; ?></h3><?php } ?>
<?php if (isset($featureblurb) && trim($featureblurb) != "") { ?>
    <?php echo h($featureblurb); ?><?php } ?>
<?php if (!empty($featurelink) && ($featurelink_c = Page::getByID($featurelink)) && !$featurelink_c->error && !$featurelink_c->isInTrash()) {
    ?>
    <p class="btn-text"><?php echo '<a href="' . $featurelink_c->getCollectionLink() . '">' . (isset($featurelink_text) && trim($featurelink_text) != "" ? $featurelink_text : $featurelink_c->getCollectionName()) . '</a>';
?></p><?php } ?>
</div>