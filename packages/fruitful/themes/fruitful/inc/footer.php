<?php defined('C5_EXECUTE') or die("Access Denied."); 
      $themePath = $this->getThemePath();
?>

<footer>
	<div class="container">
	<p class="copy">© <?php echo(date("Y")); ?> American Association of Directors of Psychiatric Residency Training. All Rights Reserved. Web Application by <a href="http://www.informaticsinc.com/" target="_blank" style="text-decoration: none;color:inherit;">Informatics, Inc</a><span style="color:#444;"><br/>&nbsp;<?php echo $_SERVER['SERVER_ADDR']; ?></span>
    </p></div>
</footer>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    
    <script src="<?php echo $themePath; ?>/js/cycle2.min.js"></script>
    <!-- SR(05/15/2020) - Added custom.js file for adding popover -->
    <script src="<?php echo $themePath;?>/js/custom.js"></script>	
    <?php  if ($c->isEditMode()) { ?>
        <?php  } else { ?>
        <!-- include Cycle plugin -->
        <script src="<?php echo $themePath; ?>/js/bootstrap.min.js"></script>
	<?php  } ?>
    
    </div>
	<?php Loader::element('footer_required'); ?>
 
  </body>
</html>
