<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

$ih = Core::make('helper/concrete/ui');
switch ($mode) {
    case 'add':
        include_once 'partial/add.php';
        break;
    case 'add_item':
        include_once 'partial/add_item.php';
        break;
    case 'edit':
        include_once 'partial/edit.php';
        break;
    default:
        include_once 'partial/listing.php';
        break;
}
?>
