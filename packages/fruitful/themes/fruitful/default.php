<?php  defined('C5_EXECUTE') or die("Access Denied."); 
$this->inc('inc/header.php'); ?>

	<div id="headerShell">
	    
	    <?php  
            $a = new Area('Header'); 
            $a->enableGridContainer();
            $a->display($c);    
        ?>
	    
	</div>
	
	<main id="mainShell">		
			
		<article>
		
			<?php  
				$a = new Area('Main'); 
                $a->enableGridContainer();
				$a->display($c); 	
			?>				
		
		</article>
				
	</main><!-- #mainShell -->
	
<?php  $this->inc('inc/footer.php'); ?>	