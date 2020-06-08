<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<?php if ($linkURL) {
    ?>
    <a href="<?=$linkURL?>">
<?php 
} ?>
<i class="fa fa-<?=$icon?>"></i>
<?=h($title)?>

<?php if ($linkURL) {
    ?>
    </a>
<?php 
} ?>