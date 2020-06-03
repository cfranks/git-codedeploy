<?php
defined('C5_EXECUTE') or die("Access Denied.");
$view->inc('inc/header.php');
?>

<main class="content">
<header id="hero">
	<?php
	  $a = new Area('Hero');
	  $a->display($c);
	?>
</header>
<div class="content-main">

<?php
  $a = new Area('Main');
  $a->setAreaGridMaximumColumns(12); 
  $a->enableGridContainer(); 
  $a->display($c);
?>

<div class="container">
  <div class="col text">
    <?php
	  $a = new Area('Impact Content');
	  $a->setAreaGridMaximumColumns(12); 
	  $a->display($c);
	?>
  </div>
  <div class="col grid">
    <?php
	  $a = new Area('Impact Links');
	  $a->setAreaGridMaximumColumns(12); 
	  $a->display($c);
	?>
  </div>
</div>

</div>
</main>
<?php $view->inc('inc/footer.php'); ?>
