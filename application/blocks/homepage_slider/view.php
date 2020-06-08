<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>

<?php  if ($sliderImage){ ?>
    <div class="slide">
<img src="<?php  echo $sliderImage->getURL(); ?>" alt="<?php  echo $sliderImage->getTitle(); ?>"/><?php  } ?>
<?php  if (isset($TitleText) && trim($TitleText) != "") { ?>
 <div class="slide-text">
    <h3><?php  echo h($TitleText); ?></h3><?php  } ?>
<?php  if (isset($HeaderText) && trim($HeaderText) != "") { ?>
    <h2><?php  echo h($HeaderText); ?></h2><?php  } ?>
<?php  if (isset($description_1) && trim($description_1) != "") { ?>
    <p><?php  echo h($description_1); ?></p><?php  } ?>
<?php  if (!empty($LinkTo)) {
    $linkToC = Page::getByID($LinkTo);
    $linkURL = empty($linkToC) || $linkToC->error ? "" : $linkToC->getCollectionLink();
    echo '<a href="' . $linkURL . '">' . (isset($LinkTo_text) && trim($LinkTo_text) != "" ? $LinkTo_text : $linkToC->getCollectionName()) . '</a>';
} ?>
</div>
</div>