<?php
$page = Page::getCurrentPage();
if (!$page->getAttribute('no_head_foot')) {
    ?>
    <div class="cta-full">
        <?php
        $a = new GlobalArea('Call to Action Image');
        $a->display($c);
        ?>
        <div class="container">
            <div class="text">
                <?php
                $a = new GlobalArea('Call to Action Text '.Localization::activeLanguage());
                $a->display($c);
                ?>
            </div>
        </div>
    </div>
<?php } ?>

<footer class="footer">
    <?php if (!$page->getAttribute('no_head_foot')) { ?>
        <div class="container">
            <div class="col-sm-3 col-lg-4">
                <?php
                $a = new GlobalArea('Footer 1 '.Localization::activeLanguage());
                $a->display($c);
                ?>
            </div>
            <div class="col-sm-3 col-lg-3 social">
                <?php
                $a = new GlobalArea('Footer 2 '.Localization::activeLanguage());
                $a->display($c);
                ?>
            </div>
            <div class="col-sm-3 col-lg-3">
                <?php
                $a = new GlobalArea('Footer 3 '.Localization::activeLanguage());
                $a->display($c);
                ?>
            </div>
            <div class="col-sm-3 col-lg-2">
                <?php
                $a = new GlobalArea('Footer 4 '.Localization::activeLanguage());
                $a->display($c);
                ?>
            </div>
        </div>
    <?php } ?>
    <p class="copy">Copyright © <?php echo date("Y"); ?> Society of the Divine Word. All Rights Reserved. Web Application by <a href="http://www.informaticsinc.com/" target="_blank">Informatics, Inc</a>
        <span style="color: transparent"><?php echo $_SERVER['SERVER_ADDR']; ?></span>
    </p>
</footer>

<?php if (!$c->isEditMode()) { ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link href="<?= $view->getThemePath() ?>/css/animation.css" rel="stylesheet">
    <script src="<?= $view->getThemePath() ?>/js/bootstrap.min.js"></script>
    <script src="<?= $view->getThemePath() ?>/js/scripts.js"></script>
<?php } ?>
</div>

<?php Loader::element('footer_required') ?>

</body>
</html>

