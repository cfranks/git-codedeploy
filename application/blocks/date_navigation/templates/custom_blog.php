<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="ccm-block-date-navigation-wrapper news-sidebar">

    <h3><?=h($title)?></h3>

    <?php if (count($dates)) {
    ?>
<div class="ccm-page-list">            
    <?php foreach ($dates as $date) {
    ?>
                <a href="<?=$view->controller->getDateLink($date)?>"
                        <?php if ($view->controller->isSelectedDate($date)) {
    ?>
                            class="ccm-block-date-navigation-date-selected"
                        <?php 
}
    ?>><?=$view->controller->getDateLabel($date)?></a><br/>
            <?php 
}
    ?>
        </div>
    <?php 
} else {
    ?>
        <?=t('None.')?>
    <?php 
} ?>


</div>
