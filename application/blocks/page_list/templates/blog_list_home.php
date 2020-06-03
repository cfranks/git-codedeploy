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


    <div class="page-list-blog">

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
        $buttonClasses = 'ccm-block-page-list-read-more';
    $entryClasses = 'ccm-block-page-list-page-entry';
    $title = $th->entities($page->getCollectionName());
    $url = ($page->getCollectionPointerExternalLink() != '') ? $page->getCollectionPointerExternalLink() : $nh->getLinkToCollection($page);
    $target = ($page->getCollectionPointerExternalLink() != '' && $page->openCollectionPointerExternalLinkInNewWindow()) ? '_blank' : $page->getAttribute('nav_target');
    $target = empty($target) ? '_self' : $target;
    $description = $page->getCollectionDescription();
    $description = $controller->truncateSummaries ? $th->wordSafeShortText($description, $controller->truncateChars) : $description;
    $description = $th->entities($description);
    $tags = $page->getCollectionAttributeValue('tags');

    
    $thumbnail = false;
    if ($displayThumbnail) {
        $thumbnail = $page->getAttribute('thumbnail');
	$thumbnail_alt = $page->getAttribute('thumbnail_alt');
    }
    if (is_object($thumbnail) && $includeEntryText) {
        $entryClasses = 'ccm-block-page-list-page-entry-horizontal';
    }

    $date = $dh->formatDate($page->getCollectionDatePublic(), true);

        //Other useful page data...

        //$last_edited_by = $page->getVersionObject()->getVersionAuthorUserName();

        //$original_author = Page::getByID($page->getCollectionID(), 1)->getVersionObject()->getVersionAuthorUserName();

        /* CUSTOM ATTRIBUTE EXAMPLES:
         * $example_value = $page->getAttribute('example_attribute_handle');
         *
         * HOW TO USE IMAGE ATTRIBUTES:
         * 1) Uncomment the "$ih = Loader::helper('image');" line up top.
         * 2) Put in some code here like the following 2 lines:
         *      $img = $page->getAttribute('example_image_attribute_handle');
         *      $thumb = $ih->getThumbnail($img, 64, 9999, false);
         *    (Replace "64" with max width, "9999" with max height. The "9999" effectively means "no maximum size" for that particular dimension.)
         *    (Change the last argument from false to true if you want thumbnails cropped.)
         * 3) Output the image tag below like this:
         *		<img src="<?php echo $thumb->src ?>" width="<?php echo $thumb->width ?>" height="<?php echo $thumb->height ?>" alt="" />
         *
         * ~OR~ IF YOU DO NOT WANT IMAGES TO BE RESIZED:
         * 1) Put in some code here like the following 2 lines:
         * 	    $img_src = $img->getRelativePath();
         *      $img_width = $img->getAttribute('width');
         *      $img_height = $img->getAttribute('height');
         * 2) Output the image tag below like this:
         * 	    <img src="<?php echo $img_src ?>" width="<?php echo $img_width ?>" height="<?php echo $img_height ?>" alt="" />
         */

        /* End data preparation. */

        /* The HTML from here through "endforeach" is repeated for every item in the list... */ ?>
		
        
        
        <div class="blog-item">

        <?php if ($includeEntryText): ?>
           

                <?php if (isset($includeName) && $includeName): ?>
                    <?php if (isset($useButtonForLink) && $useButtonForLink) {
    ?>
                        <?php echo $title;
    ?>
                    <?php
} else {
    ?>
                        <h3><?php echo $title ?></h3>
                    <?php
}
    ?>
                <?php endif;
    ?>

                <?php if (isset($includeDate) && $includeDate): ?>
                    <date><?php echo $date?></date>
                <?php endif;
    ?>

                <?php if (isset($includeDescription) && $includeDescription): ?>
                    <p class="blurb">
                        <?php echo $description ?>
                    </p>
                <?php endif;
    ?>
				<a href="<?php echo $url ?>" target="<?php echo $target ?>" class="btn-theme">Continue Reading</a>

                
        <?php endif;
    ?>
        
	</div>
	<?php endforeach;
    ?>
    </div>

    <?php if (count($pages) == 0): ?>
        <div class="ccm-block-page-list-no-pages"><?php echo h($noResultsMessage)?></div>
    <?php endif;
    ?>
<?php
} ?>