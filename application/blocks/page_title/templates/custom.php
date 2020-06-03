<?php  defined('C5_EXECUTE') or die("Access Denied.");
$dh = Core::make('helper/date'); /* @var $dh \Concrete\Core\Localization\Service\Date */
$page = Page::getCurrentPage();
$date = $dh->formatDate($page->getCollectionDatePublic(), true);
$user = UserInfo::getByID($page->getCollectionUserID());
$ownerID = $page->getCollectionUserID();
$ui = UserInfo::getByID($ownerID);
$author_info = $page->getAttribute('author_info');
?>
    <?php if (!empty($author_info)) { ?>
    <div style="margin-bottom:10px">
    <?php echo "<span style='font-style:italic'>".$author_info."</span><br/>"; ?>
    </div>
    <?php } ?>
