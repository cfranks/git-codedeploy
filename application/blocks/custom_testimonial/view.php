<?php defined("C5_EXECUTE") or die("Access Denied."); ?>
<div class="testimonial">
    <?php if ($sidebarimage) { ?>
        <img src="<?php echo $sidebarimage->getURL(); ?>" alt="<?php echo $sidebarimage->getTitle(); ?>"/>
    <?php } ?>

    <?php if (isset($shorttestimonial) && trim($shorttestimonial) != "") { ?>
        <p class="short-quote"><?php echo nl2br($shorttestimonial); ?></p>
    <?php } ?>
    <?php if (trim($buttonlabel) == "") {
        $buttonlabel = "Learn More";
    }?>
    <a class="btn btn-text" data-toggle="modal" data-target="#testimonial-<?=$bID?>">
        <?php echo h($buttonlabel); ?>
    </a>
</div>