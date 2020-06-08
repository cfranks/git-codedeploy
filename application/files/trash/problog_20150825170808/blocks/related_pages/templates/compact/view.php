<?php  
defined('C5_EXECUTE') or die("Access Denied.");
$textHelper = Loader::helper("text");
$nh = Loader::helper('navigation');
?>
<h2><?php   echo t('Related Posts')?></h2>
<?php  
if (is_array($cArray)) {

    foreach ($cArray as $cobj) {
        //$cobj = $cArray[$i];
        $i++;
        $ak_g = CollectionAttributeKey::getByHandle('thumbnail');
        $image = $cobj->getCollectionAttributeValue($ak_g);

        if ($image->fID) {
            $thumbnail = File::getRelativePathFromID($image->fID);
        }

        if ($thumbnail) {
            echo '<img src="'.$thumbnail.'" alt="image thumbnail" width="70px" style="float: left; padding: 0 5px 5px 0px;"/>';
        }
        echo '<h3 class="related_page_titles"><a href="'.$nh->getLinkToCollection($cobj).'">'.$cobj->getCollectionName().'</a></h3>';
        echo '<p class="related_page_results">'.$textHelper->shorten($cobj->getCollectionDescription(),$controller->truncateChars).'</p>';
        echo '<br style="clear: left;"/>';
        if ($i == $num) {
            break;
        }
    }
}
