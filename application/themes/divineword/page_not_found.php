<?php
defined('C5_EXECUTE') or die("Access Denied.");
$view->inc('inc/header.php');

$activeLanguage = Localization::activeLanguage();
	$return_url = '/';
        switch($activeLanguage) {
            case 'pl':
		$return_url = '/pl';
            break; 
            case 'es':
		$return_url = '/sp';
            break;
            case 'pt':
		$return_url = '/pt';
            break;
            case 'vi':
		$return_url = '/vi'; 
            break;
            default:
            $return_url = '/';            
        }
?>
<main class="content">
<header class="simple">
    
    <h1><?php echo($c->GetCollectionName()); ?></h1>
    <a href="<?php echo $return_url; ?>" class="btn-theme"><?php echo t('Return Home'); ?></a>
    
</header>

<div class="content-main">
	<h3 class="text-center">The page you requested could not be found at this address. </h3>
    <?php
		$a = new Area('Main');
		$a->setAreaGridMaximumColumns(12);
		$a->enableGridContainer();
		$a->display($c);
	?>
</div>

</main>

<?php $view->inc('inc/footer.php'); ?>