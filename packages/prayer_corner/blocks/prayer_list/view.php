<?php
use Concrete\Core\Tree\Node\Type\FileFolder;
use Concrete\Core\File\FolderItemList;

defined("C5_EXECUTE") or die("Access Denied.");
$pkg = Package::getByHandle('prayer_corner');
include_once 'partial/'. $format .'.php';
?>