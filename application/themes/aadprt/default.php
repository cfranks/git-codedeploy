<?php
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('inc/header.php');
$themePath = $this->getThemePath();
?>

<main role="main">
<section id="page-header">
  <div class="container">
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
      <h1><?php echo($c->GetCollectionName()); ?></h1>
  </div>
</section>

<div class="section">
<div class="container">
	<?php
		$a = new Area('Main');
		$a->setAreaGridMaximumColumns(12);
		$a->display($c);
    ?> 
</div>
</div>

<section class="full">
	<?php
		$a = new Area('Full Main');
		$a->setAreaGridMaximumColumns(12);
		$a->display($c);
    ?> 
</section>

<?php $this->inc('inc/footer.php'); ?>