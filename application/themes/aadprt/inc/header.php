<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        View::element('header_required', [
            'pageTitle' => isset($pageTitle) ? $pageTitle : '',
            'pageDescription' => isset($pageDescription) ? $pageDescription : '',
            'pageMetaKeywords' => isset($pageMetaKeywords) ? $pageMetaKeywords : ''
        ]);
        ?>
        
        <meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
		<link href="https://fonts.googleapis.com/css?family=Lora:400,700|Rubik:400,700&display=swap" rel="stylesheet">
        <link href="<?= $view->getThemePath() ?>/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?= $view->getThemePath() ?>/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?= $view->getThemePath() ?>/css/slick.css" rel="stylesheet">
        <link href="<?= $view->getThemePath() ?>/css/custom.css" rel="stylesheet">

		<!--LOGGED IN STYLES-->
        <?php
        $u = new User();
        $g = Group::getByName('Administrators');
        if ($u->isLoggedIn() && $u->inGroup($g)) {
            ?>
            <style>
                #site-header {top: 48px !important; position: absolute;}
            </style>
		<?php } ?>
        
        <!--EDIT MODE STYLES-->
        <?php if ($c->isEditMode()) { ?>
            <style>
			  #hero {margin-top: 175px;}
			  #hero figure {height: 100px;}
			  #hero .hero-content {margin-top: 50px; background: none;}
			  .clients img {max-width: 300px;}
            </style>
        <?php } ?>
    </head>

<body>
<div class="<?= $c->getPageWrapperClass() ?> ccm-page">

<!-- SITE HEADER -->
<header id="site-header">
  <div class="navbar-top">
  <div class="container">
    <a href="/" id="logo"><img src="<?= $view->getThemePath() ?>/img/logo.png" alt="AADPRT Logo"/></a>
    <button id="btn-menu">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="btn-label">Menu</span>
    </button>
  </div>
  </div>
	<!-- Menu -->
    <nav id="menu">
      <div class="container">
      <ul id="nav-main">
     	 <?php
            $nav = BlockType::getByHandle('autonav');
            $nav->controller->orderBy = 'display_asc';
            $nav->controller->displayPages = 'top';
            $nav->controller->displaySubPages = 'all';
            $nav->controller->displaySubPageLevels = 'custom';
            $nav->controller->displaySubPageLevelsNum = 1;
            $nav->render('templates/main_menu');
         ?>
      </ul>

      <div id="hdr-tools">
        <?php
		  $a = new GlobalArea('Header Links');
		  $a->display($c);
		?>
        <!--
        <ul id="nav-mini">
          <li><a href="/contact-us/">Contact Us</a></li>
        </ul>
        -->
        <a href="<?php echo Config::get('custom.PortalURL'); ?>register" class="btn-theme">Become a Member</a>
        <a href="#.html" type="button" class="btn-theme" data-toggle="modal" data-target="#login-modal">
          Login
        </a>
      </div><!-- /TOOLS -->
      </div><!-- /WRAPPER -->
    </nav>
</header>