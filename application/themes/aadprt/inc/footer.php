<footer class="footer">
  <div class="flex">
    <div class="flex-item">
      <div class="feature">
        <i class="fa fa-map-marker"></i>
        <?php
		  $a = new GlobalArea('Footer Location');
		  $a->display($c);
		?>
      </div>
    </div>
    <div class="flex-item">
      <div class="feature">
        <i class="fa fa-envelope-o"></i>
        <?php
		  $a = new GlobalArea('Footer Email');
		  $a->display($c);
		?>
      </div>
    </div>
    <div class="flex-item">
      <div class="feature">
        <i class="fa fa-mobile-phone"></i>
        <?php
		  $a = new GlobalArea('Footer Phone');
		  $a->display($c);
		?>
      </div>
    </div>
  </div>
  <p class="copy">© <?php echo date("Y"); ?> American Association of Directors of Psychiatric Residency Training. All Rights Reserved.  Web Application by <a href="http://www.informaticsinc.com/">Informatics, Inc</a><span style="color: transparent"><?php echo $_SERVER['SERVER_ADDR']; ?></span></p>
  <?php
	$a = new GlobalArea('Footer Image');
	$a->display($c);
  ?>
</footer>

<!-- LOGIN MODAL -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="login-modal" id="login-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
    <h3>Account Login</h3>
    <form method="POST" action="<?php echo Config::get('custom.PortalURL'); ?>clogin" accept-charset="UTF-8">
        <input name="_token" type="hidden" value="ltN9A3xJeVXLYUxxblS2yULsnKFmDdoS3IqPQPTE">
        <input required="required" type="text" name="email" placeholder="Email" />
        <input required="required" name="password" type="password" value="" placeholder="Password" />
        <div class="form-action">
        <button type="submit" class="btn-theme">Login</button>
        <p class="help-block"><a href="<?php echo Config::get('custom.PortalURL'); ?>sendresetcode">Forgot Password?</a> | <a href="<?php echo Config::get('custom.PortalURL'); ?>register">Become a Member</a></p>
        </div>
    </form>
</div>
</div>
</div>

<!-- Placed at the end of the document so the pages load faster -->
<?php if (!$c->isEditMode()) { ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link href="<?= $view->getThemePath() ?>/css/animation.css" rel="stylesheet">
	<script src="<?= $view->getThemePath() ?>/js/bootstrap.min.js"></script>
    <script src="<?= $view->getThemePath() ?>/js/slick.min.js"></script>
    <script src="<?= $view->getThemePath() ?>/js/scripts.js"></script>
<?php } ?>
</div>
<?php Loader::element('footer_required') ?>
	
</body>
</html>