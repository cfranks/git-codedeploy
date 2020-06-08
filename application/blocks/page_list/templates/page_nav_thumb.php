<?php
defined('C5_EXECUTE') or die("Access Denied.");
$th = Loader::helper('text');
$c = Page::getCurrentPage();
$dh = Core::make('helper/date'); /* @var $dh \Concrete\Core\Localization\Service\Date */
$themePath = $this->getThemePath();

?>

<?php if ($c->isEditMode() && $controller->isBlockEmpty()) {
    ?>
    <div class="ccm-edit-mode-disabled-item"><?php echo t('Empty Page List Block.')?></div>
<?php
} else {
    ?>

<div class="page-list">

    <?php

    $includeEntryText = false;
    if (
        (isset($includeName) && $includeName)
        ||
        (isset($includeDescription) && $includeDescription)
        ||
        (isset($useButtonForLink) && $useButtonForLink)
    ) {
        $includeEntryText = true;
    }

    foreach ($pages as $page):

    // Prepare data for each page being listed...
    $entryClasses = 'ccm-block-page-list-page-entry';
    $title = $th->entities($page->getCollectionName());
    $url = ($page->getCollectionPointerExternalLink() != '') ? $page->getCollectionPointerExternalLink() : $nh->getLinkToCollection($page);
    $target = ($page->getCollectionPointerExternalLink() != '' && $page->openCollectionPointerExternalLinkInNewWindow()) ? '_blank' : $page->getAttribute('nav_target');
    $target = empty($target) ? '_self' : $target;
    $description = $page->getCollectionDescription();
    $description = $controller->truncateSummaries ? $th->wordSafeShortText($description, $controller->truncateChars) : $description;
    $description = $th->entities($description);
    
    $thumbnail = false;
    if ($displayThumbnail) {
        $thumbnail = $page->getAttribute('thumbnail');
		$thumbnail_alt = $page->getAttribute('thumbnail_alt');
    }
    if (is_object($thumbnail) && $includeEntryText) {
        $entryClasses = 'ccm-block-page-list-page-entry-horizontal';
    }
	 ?>
	
    <a href="<?php echo $url ?>" target="<?php echo $target ?>" class="page-list-item">        

        <?php if (is_object($thumbnail)){ ?>
			<?php
            $img = Core::make('html/image', array($thumbnail));
            $imagePath = $img->getTag()->src;
			$altText = $thumbnail->getTitle();
            ?>
			<?php if (!empty($imagePath)) {  ?>
                <img src="<?php echo $imagePath; ?>" alt="<?php echo $altText; ?>" />
            <?php } else { ?>
              <img src="<?= $view->getThemePath() ?>/img/logo-fullcolor.png" alt="Lil' Drug Store Logo"/>
            <?php } ?>    
            <?php } else { ?>
            <i class="fa fa-check"></i>
		<?php } ?>             
		
        <h3><?php echo $title ?></h3>
        <?php if (isset($includeDescription) && $includeDescription): ?>
            <p class="blurb">
                <?php echo $description ?>
            </p>
        <?php endif;?>
        
	</a>
	<?php endforeach; ?>

</div><!-- end page list -->

<?php
} ?>
