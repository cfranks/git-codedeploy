<?php  defined("C5_EXECUTE") or die("Access Denied."); ?>
<div class="col-sm-3">
    	<div class="hpg-col">
<?php  if (isset($Title) && trim($Title) != "") { ?>
    <h3><?php  echo h($Title); ?></h3><?php  } ?>
<?php  if (isset($Description_1) && trim($Description_1) != "") { ?>
    <p><?php  echo h($Description_1); ?></p><?php  } ?>
<?php  if (!empty($LinkURL)) {
    $linkToC = Page::getByID($LinkURL);
    $linkURL = empty($linkToC) || $linkToC->error ? "" : $linkToC->getCollectionLink();
    echo '<a class="button" href="' . $linkURL . '">' . (isset($LinkURL_text) && trim($LinkURL_text) != "" ? $LinkURL_text : $linkToC->getCollectionName()) . '</a>';
} ?>
    </div>
    </div>