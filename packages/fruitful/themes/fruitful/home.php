<?php
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('inc/header.php');
$themePath = $this->getThemePath();
?>

<section id="slider">
    <div class="cycle-slideshow"
         data-cycle-fx=fade
         data-cycle-timeout=6000
         data-cycle-slides="> div"
         data-cycle-pager="#pager"
         data-cycle-height="calc"
         data-cycle-prev="#prev"
         data-cycle-next="#next"
         >
             <?php
             $a = new Area('Homepage Slider');
             $a->display($c);
             ?>
    </div>
    <div id="slide-pager">
        <div id="pager"></div>
        <a href="#.html" class="slide-nav" id="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
        <a href="#.html" class="slide-nav" id="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
    </div>
</section>

<section class="cols">
    <hgroup>
        <h2><?php
            $a = new Area('Welcome Title');
            $a->display($c);
            ?>
        </h2>
        <p class="lead"><?php
            $a = new Area('Welcome Description');
            $a->display($c);
            ?></p>
    </hgroup>

    <?php
    $a = new Area('QuickLinks Area');
    $a->display($c);
    ?>


</section>

<section class="two-cols">
    <div class="two-col">
        <h2><?php
            $a = new Area('News Header');
            $a->display($c);
            ?>
        </h2>
        <h2><?php
            $a = new Area('News List');
            $a->display($c);
            ?>
        </h2>


        <?php
        $a = new Area('AllNews Link');
        $a->display($c);
        ?>
    </div>
    <div class="two-col meeting">
        <?php
        $a = new Area('Meeting Area');
        $a->display($c);
        ?>
    </div>
</section>
</div>
<?php if (strtotime(date("Y-m-d H:i:s")) < strtotime(date("Y-07-01 00:00:00")) && strtotime(date("Y-m-d H:i:s")) > strtotime(date("Y-05-01 00:00:00"))) { ?>
	<div id="modalAnnouncement" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-body">
	        <p>AADPRT's annual membership year is 7/1 - 6/30 and is not prorated. Please sign up or renew on/after 7/1. The renewal period ends 9/30.</p>
		Thank you.
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>

	  </div>
	</div>
	<script>
		$(document).ready(function() {
	    		$('#modalAnnouncement').modal('show');
	    	});
	</script>
<?php } ?>
<?php $this->inc('inc/footer.php'); ?>	