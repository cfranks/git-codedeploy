
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
  
  <section class="wrapper animate fadeinup delay-4 text-center">
  	<p>The page you requested could not be found at this address. <br/> Back to <a href="/">Home</a></p>  
  </section>
</main>

<?php $view->inc('inc/footer.php'); ?>