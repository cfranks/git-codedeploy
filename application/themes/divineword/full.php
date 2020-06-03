<?php
defined('C5_EXECUTE') or die("Access Denied.");
$view->inc('inc/header.php');
?>
<main class="content">
<header class="simple">
    
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
    
</header>

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