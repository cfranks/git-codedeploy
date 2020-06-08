<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>

<?php  if (isset($Header) && trim($Header) != "") { ?>
    <h2><?php  echo h($Header); ?></h2><?php  } ?>
<?php  if ($Image){ ?>
    <img src="<?php  echo $Image->getURL(); ?>" alt="<?php  echo $Image->getTitle(); ?>"/><?php  } ?>
<?php  if (isset($Title) && trim($Title) != "") { ?>
    <h3><?php  echo h($Title); ?></h3><?php  } ?>
<?php  if (isset($Date) && trim($Date) != "") { ?>
     <p class="date"><?php  echo h($Date); ?></p><?php  } ?>
<?php  if (isset($Description_1) && trim($Description_1) != "") { ?>
    <p><?php  echo h($Description_1); ?></p><?php  } ?>
<?php  if (!empty($URL)) {
    $linkToC = Page::getByID($URL);
    $linkURL = empty($linkToC) || $linkToC->error ? "" : $linkToC->getCollectionLink();
    echo '<a class="button" href="' . $linkURL . '">' . (isset($URL_text) && trim($URL_text) != "" ? $URL_text : $linkToC->getCollectionName()) . '</a>';
} ?>