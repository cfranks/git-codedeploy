<?php  defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('inc/header.php'); ?>

    
            <?php  if($error){?>
            <div class="container">
                <div class="twelvecol sixcol-medium pushthree-medium">
                    <?php  Loader::element('system_errors', array('error' => $error)); ?>
                </div>
            </div>
            <?php  } ?>
            <?php  print $innerContent; ?>                
</div>        
    
<?php  $this->inc('inc/footer.php'); ?> 