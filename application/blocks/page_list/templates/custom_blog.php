<?php
defined('C5_EXECUTE') or die("Access Denied.");

$c = Page::getCurrentPage();

/** @var \Concrete\Core\Utility\Service\Text $th */
$th = Core::make('helper/text');
/** @var \Concrete\Core\Localization\Service\Date $dh */
$dh = Core::make('helper/date');

if (is_object($c) && $c->isEditMode() && $controller->isBlockEmpty()) {
    ?>
    <div class="ccm-edit-mode-disabled-item"><?php echo t('Empty Page List Block.') ?></div>
    <?php
} else {
    ?>

    <div class="ccm-block-page-list-wrapper">

        <?php if (isset($pageListTitle) && $pageListTitle) {
            ?>
            <div class="ccm-block-page-list-header">
                <h5><?php echo h($pageListTitle) ?></h5>
            </div>
            <?php
        } ?>

        <?php if (isset($rssUrl) && $rssUrl) {
            ?>
            <a href="<?php echo $rssUrl ?>" target="_blank" class="ccm-block-page-list-rss-feed">
                <i class="fa fa-rss"></i>
            </a>
            <?php
        } ?>

        <div class="ccm-block-page-list-pages">

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

            foreach ($pages as $page) {

                // Prepare data for each page being listed...
                $buttonClasses = 'ccm-block-page-list-read-more';
                $entryClasses = 'ccm-block-page-list-page-entry';
                $title = $page->getCollectionName();
                if ($page->getCollectionPointerExternalLink() != '') {
                    $url = $page->getCollectionPointerExternalLink();
                    if ($page->openCollectionPointerExternalLinkInNewWindow()) {
                        $target = '_blank';
                    }
                } else {
                    $url = $page->getCollectionLink();
                    $target = $page->getAttribute('nav_target');
                }
                $target = empty($target) ? '_self' : $target;
                $description = $page->getCollectionDescription();
                $description = $controller->truncateSummaries ? $th->wordSafeShortText($description, $controller->truncateChars) : $description;
                $thumbnail = false;
                if ($displayThumbnail) {
                    $thumbnail = $page->getAttribute('thumbnail');
                }
                if (is_object($thumbnail) && $includeEntryText) {
                    $entryClasses = 'ccm-block-page-list-page-entry-horizontal clean-list-item';
                }

                $date = date("M j, Y", strtotime($page->getCollectionDatePublic()));

                //Other useful page data...

                //$last_edited_by = $page->getVersionObject()->getVersionAuthorUserName();

                /* DISPLAY PAGE OWNER NAME
                 * $page_owner = UserInfo::getByID($page->getCollectionUserID());
                 * if (is_object($page_owner)) {
                 *     echo $page_owner->getUserDisplayName();
                 * }
                 */

                /* CUSTOM ATTRIBUTE EXAMPLES:
                 * $example_value = $page->getAttribute('example_attribute_handle', 'display');
                 *
                 * When you need the raw attribute value or object:
                 * $example_value = $page->getAttribute('example_attribute_handle');
                 */

                /* End data preparation. */

                /* The HTML from here through "endforeach" is repeated for every item in the list... */ ?>

                <div class="<?php echo $entryClasses ?>">
                    <div class="row">
                        <div class="col-sm-3">
                            <?php if (is_object($thumbnail)) { ?>
                                    <?php $img = Core::make('html/image', array($thumbnail));
                                    $tag = $img->getTag();
                                    $tag->addClass('img-responsive');
                                    echo $tag; ?>
                            <?php } ?>
                        </div>
                        <div class="col-sm-9">
                            <?php if ($includeEntryText) { ?>
                                <?php if (isset($includeName) && $includeName) { ?>
                                    <h2>
                                        <?php if (isset($useButtonForLink) && $useButtonForLink) { ?>
                                            <?php echo h($title); ?>
                                        <?php } else { ?>
                                            <a href="<?php echo h($url) ?>"
                                            target="<?php echo h($target) ?>"><?php echo h($title) ?></a>
                                        <?php } ?>
                                    </h2>
                                <?php } ?>

                                <?php if (isset($includeDate) && $includeDate) { ?>
				    <!-- SR updates: interactive map design updates -->
                                    <!--<h4><?php echo h($date) ?></h4>-->
                                <?php } ?>

                                <?php if (isset($includeDescription) && $includeDescription) { ?>
                                    <div class="ccm-block-page-list-description"><?php echo h($description) ?></div>
                                <?php } ?>

                                <?php if (isset($useButtonForLink) && $useButtonForLink) { ?>
                                    <div class="ccm-block-page-list-page-entry-read-more">
                                        <a href="<?php echo h($url) ?>" target="<?php echo h($target) ?>"
                                        class="<?php echo h($buttonClasses) ?>"><?php echo h($buttonLinkText) ?></a>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>

                    
                </div>

                <?php
            } ?>
        </div><!-- end .ccm-block-page-list-pages -->

        <?php if (count($pages) == 0) { ?>
            <div class="ccm-block-page-list-no-pages"><?php echo h($noResultsMessage) ?></div>
        <?php } ?>

    </div><!-- end .ccm-block-page-list-wrapper -->


    <?php if ($showPagination) { ?>
        <?php echo $pagination; ?>
    <?php } ?>

    <?php

} ?>
<style>
.clean-list-item {
    overflow: hidden;
    border-bottom: 1px dotted #ccc;
    padding: 25px 0;
}

.clean-list-item h2 {
    font-size: 24px;
    line-height: 30px;
    margin: 0 0 20px 0;
}

.clean-list-item h2 a {
    color: #333;
}

.clean-list-item h2 a:hover {
    text-decoration: underline;
    color: #23527c;
}

.clean-list-item h4 {
    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--yellow);
}

.clean-list-item p {
    font-size: 15px;
    margin: 0;
}
</style>