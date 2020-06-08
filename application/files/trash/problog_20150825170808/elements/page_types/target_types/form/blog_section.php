<?php  
defined('C5_EXECUTE') or die("Access Denied.");
$form = Loader::helper('form');
$cParentID = false;
if (is_object($page)) {
    $cParentID = $page->getPageDraftTargetParentPageID();
}
if (is_object($pagetype)) {
    print $form->select('cParentID', $configuration->getBlogSectionPages(),$cParentID);
}
