<?php
defined('C5_EXECUTE') or die("Access Denied.");
$view->inc('inc/header.php');
?>
<main class="content">
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

<div class="content-main">
  <div class="container">
    <div class="row">
    
    <div class="col-md-8 col-md-push-3">
      <?php
			$a = new Area('Main');
			$a->setAreaGridMaximumColumns(12);
			$a->display($c);
		?>
    </div>
	<div class="col-md-3 col-md-pull-8">
     <?php
			$a = new Area('Testimonial');
			$a->display($c);
		?>
      <nav id="sec">
        <?php
            $bt_main = BlockType::getByHandle('autonav');
            $bt_main->controller->displayPages = 'second_level'; // top, above, below, second_level, third_level, custom (Specify the displayPagesCID below)
            $bt_main->controller->orderBy = 'display_asc';  // display_asc, display_desc, chrono_asc, chrono_desc, alpha_desc 
            $bt_main->controller->displaySubPages = 'relevant';  // none,  relevant, relevant_breadcrumb, all
            $bt_main->controller->displaySubPageLevels = 'custom'; //custom, none
            $bt_main->controller->displaySubPageLevelsNum = '1'; // Specify how deep level 
            $bt_main->render('view'); // Specify your template or type "view" to use default
		?>
      </nav>
      <?php
			$a = new Area('Sidebar Content');
			$a->display($c);
		?>
    </div>
	
    </div>
    <div class="row">
    	<?php
			$a = new Area('Full Width Area');
			$a->setAreaGridMaximumColumns(12);
			$a->display($c);
		?>
    </div>
</div>

</main>

<?php $view->inc('inc/footer.php'); ?>