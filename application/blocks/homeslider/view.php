<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>

<?php  if ($Image){ ?>
<div class="slide">
    <img src="<?php  echo $Image->getURL(); ?>" alt="<?php  echo $Image->getTitle(); ?>"/><?php  } ?>
<div class="slide-text">
        <?php  if (isset($Header) && trim($Header) != "") { ?>
    <h3><?php  echo h($Header); ?></h3><?php  } ?>
<?php  if (isset($Title) && trim($Title) != "") { ?>
    <h2><?php  echo h($Title); ?></h2><?php  } ?>
<?php  if (isset($Description_1) && trim($Description_1) != "") { ?>
    <p><?php  echo h($Description_1); ?></p><?php  } ?>
<?php  if (!empty($Link)) {
    $linkToC = Page::getByID($Link);
    $linkURL = empty($linkToC) || $linkToC->error ? "" : $linkToC->getCollectionLink();
    echo '<a href="' . $linkURL . '">' . (isset($Link_text) && trim($Link_text) != "" ? $Link_text : $linkToC->getCollectionName()) . '</a>';
} ?>
</div>
        </div>
