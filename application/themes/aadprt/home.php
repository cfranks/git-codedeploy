<?php
defined('C5_EXECUTE') or die("Access Denied.");
$view->inc('inc/header.php');
?>
<main>
  <header id="hero" class="animate fadeinup delay-1">
    <figure class="parallax-2">
      <?php
		$a = new Area('Hero Video');
		$a->display($c);
	  ?>
    </figure>
    <div class="hero-content animate fadeinup delay-3">
      <?php
		$a = new Area('Hero Header');
		$a->display($c);
	  ?>
      <form class="form" method="get" action="/search/">
          <input type="text" name="query" placeholder="Search This Site" aria-label="Search">
          <button type="submit" name="search-button" value="Search"><i class="fa fa-search" aria-label="Search Icon"></i><span class="sr-only">Search This Site</span></button>
      </form>
      <div class="hero-links">
        <?php
			$a = new Area('Hero Links');
			$a->display($c);
		  ?>
      </div>
    </div>
  </header>
  
  <section class="wrapper animate fadeinup delay-4">
    <div class="row home-first-row">
	  <div class="col-sm-4">
        <?php
		  $a = new Area('First Row Col Left');
		  $a->display($c);
		?>
      </div>
      <div class="col-sm-8">
        <?php
		  $a = new Area('First Row Col Right');
		  $a->display($c);
		?>
      </div>
	</div>
    
    <hr />
	<div class="row">
    	<?php
		  $a = new Area('Additional Content');
		  $a->setAreaGridMaximumColumns(12);
		  $a->display($c);
		?>
    </div>
    
    <hr />
    <div class="client-wrapper">
	
    <?php
	  $a = new Area('Client Header');
	  $a->display($c);
	?>

    <div class="clients">
      <?php
		  $a = new Area('Client Logos');
		  $a->display($c);
		?>
    </div>
    </div>  
  
  </section>
</main>
    
<?php $view->inc('inc/footer.php'); ?>
