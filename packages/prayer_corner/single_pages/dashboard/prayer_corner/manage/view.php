<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

$ih = Core::make('helper/concrete/ui');
switch ($mode) {
    case 'edit':
        include_once 'partial/edit.php';
        break;
    default:
        include_once 'partial/listing.php';
        break;
}
?>