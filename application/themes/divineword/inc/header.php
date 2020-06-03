<!DOCTYPE html>
<html lang="en">

<head>
    <?php
        View::element('header_required',
            [
            'pageTitle' => isset($pageTitle) ? $pageTitle : '',
            'pageDescription' => isset($pageDescription) ? $pageDescription : '',
            'pageMetaKeywords' => isset($pageMetaKeywords) ? $pageMetaKeywords : ''
        ]);
        $activeLanguage = Localization::activeLanguage();
        $searchURL      = "/search/";
        $url = '/ways-to-give-annuities/donate-now';
        switch($activeLanguage) {
            case 'en':
                $searchURL = "/search/";
                $p = Page::getByID(195);
                $url = $p->getCollectionPath();
            break;
            case 'pl':
                $p = Page::getByID(280);
                $url = $p->getCollectionPath();
                $searchURL = "/".$activeLanguage."/search/"; 
            break; 
            case 'es':
                $searchURL = "/sp/search/";
                $p = Page::getByID(322);
                $url = $p->getCollectionPath();
            break;
            case 'pt':
                $p = Page::getByID(360);
                $url = $p->getCollectionPath();
                $searchURL = "/".$activeLanguage."/search/";
            break;
            case 'vi':
                $p = Page::getByID(398);
                $url = $p->getCollectionPath();
                $searchURL = "/".$activeLanguage."/search/";  
            break;
            default:
            $searchURL = "/".$activeLanguage."/search/";            
        }
		$nobreadcrumb = $c->getAttribute('no_breadcrumb');
        ?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900|Shadows+Into+Light&display=swap" rel="stylesheet">
    <link href="<?= $view->getThemePath() ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $view->getThemePath() ?>/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?= $view->getThemePath() ?>/css/custom.css" rel="stylesheet">

    <!--LOGGED IN STYLES-->
    <?php
        $u = new User();
        $g = Group::getByName('Administrators');
        if ($u->isLoggedIn()) {
            ?>
    <style>
    #navbar.affix {
        top: 48px;
    }
    </style>
    <?php } ?>

    <!--EDIT MODE STYLES-->
    <?php if ($c->isEditMode()) { ?>
    <style>
    .content-main {
        padding: 50px 0;
    }

    .content header figure {
        top: 0;
        height: 100px;
        position: relative;
    }

    .content header figure img {
        position: relative;
        height: 100px;
    }

    .content-main {
        z-index: auto !important;
    }
    </style>
    <?php } ?>
</head>
<?php if (!empty($nobreadcrumb)) { ?>

<body class="no-breadcrumb">
    <?php } else { ?>

    <body>
        <?php } ?>
        <div class="<?= $c->getPageWrapperClass() ?> ccm-page">
            <?php if (!$c->getAttribute('no_head_foot')) { ?>
            <div class="fix-affix-mobile">
                <nav class="navbar" role="navigation" id="header">
                    <div class="navbar-header">
                        <?php
                        $a = new GlobalArea('Header Logo '.Localization::activeLanguage());
                        $a->display($c);
                        ?>
                        <a href="<?php echo $url; ?>" class="btn-theme" id="hdr-donate"><?php echo t('Donate Now') ?></a>
                        <div id="hdr-lang">
                            <?php $a = new GlobalArea('Header Language');
                                $a->display($c);
                            ?>
                        </div>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false"
                                aria-controls="navbar" id="toggle">
                            <div class="button-wrap">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </div>
                            <p>Menu</p>
                        </button>
                    </div>
                    <div class="fix-affix">
                        <div id="navbar" class="collapse navbar-collapse" tabindex="-1" data-spy="affix" data-offset-top="140">
                            <ul id="nav-main">
                                <?php
                                $nav                                      = BlockType::getByHandle('autonav');
                                $nav->controller->orderBy                 = 'display_asc';
                                $nav->controller->displayPages            = 'top';
                                $nav->controller->displaySubPages         = 'all';
                                $nav->controller->displaySubPageLevels    = 'custom';
                                $nav->controller->displaySubPageLevelsNum = 1;
                                $nav->render('templates/main_menu');
                                ?>
                                <div id="hdr-tools">
                                    <form class="form" method="get" action="<?php echo $searchURL; ?>">
                                        <input type="text" name="query" size="25" placeholder="<?php echo t('Search this site') ?>"
                                               title="<?php echo t('Search this site') ?>">
                                        <button type="submit" name="search" value="Search"><span class="fa fa-search"></span></button>
                                    </form>
                                </div>
                            </ul>
                        </div>
                        <!--/.nav-collapse -->
                    </div>
                </nav>
            </div>
            <a onclick="scrollToTop(1000)" id="btn-top"><i class="fa fa-arrow-circle-o-up"></i></a>
            <?php } ?>