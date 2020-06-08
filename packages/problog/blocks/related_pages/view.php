<?php   
defined('C5_EXECUTE') or die("Access Denied.");
$textHelper = Loader::helper("text"); 
?>
<h2><?php   echo t('Related Posts')?></h2>
<?php  
if (count($cArray) > 0) { ?>
<div class="ccm-page-list">
<?php    for ($i = 0; $i < count($cArray); $i++ ) {
$cobj = $cArray[$i]; 
$target = $cobj->getAttribute('nav_target');
$title = $cobj->getCollectionName();
?>
<h3 class="ccm-page-list-title"><a <?php   if ($target != '') { ?> target="<?php   echo $target;?>" <?php   } ?> href="<?php   echo $nh->getLinkToCollection($cobj);?>"><?php   echo $title?></a></h3>
<div class="ccm-page-list-description">
<?php   
if(!$controller->truncateSummaries){
echo $cobj->getCollectionDescription();
}else{
echo $textHelper->shorten($cobj->getCollectionDescription(),$controller->truncateChars);
}
?>
</div>
<?php   } ?>
</div>
<?php   } ?>