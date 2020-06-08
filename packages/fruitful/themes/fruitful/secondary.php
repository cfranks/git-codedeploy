<?php
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('inc/header.php');
$themePath = $this->getThemePath();
?>

<div class="row">
    <div class="col-sm-3">
        <aside>
            <h3><?php
                $level = 0;
                $current = $p = Page::getCurrentPage();
                $tree = array();
                while ($p->getCollectionParentID() >= HOME_CID) {
                    array_push($tree, $p);
                    $p = Page::getByID($p->getCollectionParentID());
                }
                $tree = array_reverse($tree);
                if (isset($tree[$level])) {
                    $parent = $tree[$level];
                    echo $parent->getCollectionName();
                }
                ?></h3>
            <nav id="sec">
                <?php 
					  $bt_main = BlockType::getByHandle('autonav'); 
					  $bt_main->controller->displayPages = 'second_level'; // top, above, below, second_level, third_level, custom (Specify the displayPagesCID below)
					  $bt_main->controller->orderBy = 'display_asc';  // display_asc, display_desc, chrono_asc, chrono_desc, alpha_desc 
					  $bt_main->controller->displaySubPages = 'relevant';  // none,  relevant, relevant_breadcrumb, all
					  $bt_main->controller->displaySubPageLevels = 'custom'; //custom, none
					  $bt_main->controller->displaySubPageLevelsNum = '2'; // Specify how deep level 
					  $bt_main->render('templates/sidenav'); // Specify your template or type "view" to use default
				  ?> 
            </nav>
            <h3>
				<?php
                $a = new GlobalArea('Sidelink Header');
                $a->display();
                ?>
            </h3>
            <?php
            $a = new GlobalArea('Sidelink List');
            $a->display();
            ?>
            <?php
            $a = new GlobalArea('Sidelink Image');
            $a->display();
            ?>   

        </aside>    
    </div>
    <div class="col-sm-9">
        <hgroup>
            <h2> <?php echo $c->getCollectionName(); ?></h2>
            <p class="lead"><?php
                $a = new Area('Title Description');
                $a->display($c);
                ?></p>
        </hgroup>
        <?php
        $a = new Area('Main');
        $a->display($c);
        ?>

    </div>
</div>

</div>
<?php $this->inc('inc/footer.php'); ?>	