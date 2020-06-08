<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        session_start();
        $themePath = $this->getThemePath();
        Loader::element('header_required');
        
        //check if query string has value, if yes set session for logged in status and username
        if ($_GET['status'] == 1 && !empty($_GET['username'])) {
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $_GET['username'];
        }
        //if query string exist, redirect to same page to hide query string from url
        if (!empty($_GET['username'])) {
            $_SESSION["hasQueryString"] = true;
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
        //Expire the session if user is inactive for 30minutes or more.
        $expireAfter = 60;

        //Check to see if our "last action" session variable has been set.
        if (isset($_SESSION['last_action'])) {

            //Figure out how many seconds have passed since the user was last active.
            $secondsInactive = time() - $_SESSION['last_action'];
            //Convert our minutes into seconds.
            $expireAfterSeconds = $expireAfter * 60;
            //Check to see if they have been inactive for too long.
            if ($secondsInactive >= $expireAfterSeconds) {
                //User has been inactive for too long.Kill their session.
                session_unset();
                session_destroy();
            }
        }
        //Assign the current timestamp as the user's latest activity
        $_SESSION['last_action'] = time();
?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="">
        <!-- CSS -->
        <link href="<?php echo $themePath; ?>/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo $themePath; ?>/css/custom.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Lato:400,300,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0,  maximum-scale=1.0, user-scalable=no">
    </head>
    <body>
        <div class="<?php echo $c->getPageWrapperClass() ?>">
            <div class="container main">
                <nav class="navbar" role="navigation" id="header">
                    <div class="navbar-header">
                        <a href="/"><img src="<?php echo $themePath; ?>/img/logo.jpg" id="logo" /></a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar" id="toggle">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div id="navbar" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav" id="nav-main">
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
                        <?php
                        //check if username session has value to display username on home page, else display login form
                        if (!empty($_SESSION['loggedin'])) {
                            if (!empty($_SESSION['username'])) {
                                $username = $_SESSION['username'];
                            } else {
                                $username = "";
                            }
                        ?>
                        <div id="login" style="padding-right:10px;">Welcome back <b><a style="text-decoration: underline;" href="https://portal.aadprt.org/user"><?php echo $username;?></a></b></div>
                        <?php } else { ?>
                            <form method="POST" action="https://portal.aadprt.org/clogin" accept-charset="UTF-8">
                                <input name="_token" type="hidden" value="ltN9A3xJeVXLYUxxblS2yULsnKFmDdoS3IqPQPTE">
                                <div id="login">
                                    <input required="required" type="text" name="email" placeholder="Email" />
                                    <input required="required" name="password" type="password" value="" placeholder="Password" />
                                    <button type="submit">Login</button>
                                </div>
                            </form>
                        <?php } ?>
                        
                        <div class="dropdown" id="search">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-search"></i><span class="caret"></span></a>
                            <ul class="dropdown-menu" style="padding:12px;">
                                <form class="form-inline" method="get" action="/index.php/search/" id="hdr-search">
                                    <button type="submit" class="btn btn-default pull-right"><i class="glyphicon glyphicon-search"></i></button><input name="query" class="form-control pull-left" placeholder="Search" type="text">
                                </form>
                            </ul>
                        </div>
                        <ul class="nav navbar-nav" id="nav-mini">
                            <?php
                            //hide forgot password and registration once logged in.
                            if (empty($_SESSION['loggedin'])) {
                                ?>
                                <li><a href="https://portal.aadprt.org/sendresetcode">Forgot Password?</a></li>
                                <li><a href="https://portal.aadprt.org/register">Become a Member</a></li>
                            <?php } ?>   
                            <li><a href="/about-aadprt/contact-information">Contact Us</a></li>
                        </ul>
                    </div>
                </nav>




