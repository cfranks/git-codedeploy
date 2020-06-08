<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

if($title!=''){
    echo '<h3>'.t($title).'</h3>';
}
extract($blog_settings);

$bt_main = BlockType::getByHandle('problog_date_archive');
$bt_main->controller->title = $title;
$bt_main->controller->targetCID = $search_path;     
$bt_main->controller->page_type = $controller->page_type;
$bt_main->controller->numMonths = 72;
$bt_main->render('view');  
