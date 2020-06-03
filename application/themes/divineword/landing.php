<?php
defined('C5_EXECUTE') or die("Access Denied.");
$view->inc('inc/header.php');
?>
<main class="content">
<?php 
if (!$c->getAttribute('no_head_foot')) { ?>
<header>
    <figure class="parallax-1">
		<?php
			$a = new Area('Header Image');
			$a->display($c);
		?>
    </figure>
    <div class="text">
    <h1><?php echo($c->GetCollectionName()); ?></h1>
    <ol class="breadcrumb">
      <?php
		$nav = BlockType::getByHandle('autonav');
		$nav->controller->orderBy = 'display_asc';
		$nav->controller->displayPages = 'top';
		$nav->controller->displaySubPages = 'relevant_breadcrumb';
		$nav->controller->displaySubPageLevels = 'enough';
		$nav->render('templates/breadcrumb');
	  ?>
    </ol>
    <?php
	  $a = new Area('Image Caption');
		if ($c->isEditMode() || $a->getTotalBlocksInArea($c) > 0 ) {
			echo '<div class="caption">';
			$a->display($c);
			echo '</div>';
		}
	?>
    </div>
</header>
<?php } ?>
<div class="content-main">
<?php
	$a = new Area('Main');
	$a->setAreaGridMaximumColumns(12);
	$a->enableGridContainer(); 
	$a->display($c);
?>
</div>

</main>

<?php $view->inc('inc/footer.php'); ?>