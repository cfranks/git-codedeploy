<?php
defined('C5_EXECUTE') or die("Access Denied.");
$view->inc('inc/header.php');
?>
<main>
  <header id="hero" class="secondary animate fadeinup delay-1">
    <figure class="parallax-2">
      <?php
        $a = new Area('Header Image');
        $a->display($c);
	  ?>
    </figure>
    <div class="hero-content">
    	<h1 style="text-align: center;"><?php echo($c->GetCollectionName()); ?></h1>
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
    </div>
  </header>
  
  <section class="wrapper animate fadeinup delay-4">
  <div class="row">
      <div class="sec-beta">
        <?php print $innerContent; ?>
      </div>
      <div class="sec-alpha">
        <nav id="sec-nav">
        <?php
			$nav = BlockType::getByHandle('autonav');
			$nav->controller->orderBy = 'display_asc';
			if ($c->getAttribute('exclude_nav')) {
			$nav->controller->displayPages = 'above';
			} else {
			$nav->controller->displayPages = 'current';
			}
			$nav->controller->displaySubPages = 'none';
			$nav->controller->displaySubPageLevels = 'custom';
			$nav->controller->displaySubPageLevelsNum = 1;
			$nav->render('');
		 ?>
        </nav>
        <?php
			$a = new GlobalArea('Sidebar Content');
			$a->display($c);
		?>
        <?php
			$a = new Area('Only This Page Sidebar Content');
			$a->display($c);
		?>
      </div>
	</div>
  </section>
</main>

<?php $view->inc('inc/footer.php'); ?>