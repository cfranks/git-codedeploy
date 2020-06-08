<?php
/*
 * Created by: MA 09112015
 * For : Single page for listing awards from backend API
 * 
 */
?>

<div class="row">
    <div class="col-sm-3">
        <aside>
            <h3>
                <?php
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
                ?>
            </h3>
            <nav id="sec">
                <ul>
                    <li class="nav-selected nav-path-selected"><a href="/awards/awards_listing-1" target="_self" class="nav-selected nav-path-selected">Awards Listing</a></li>
                </ul> 
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
            <h2><?php
                $a = new Area('Page-Title');
                $a->display($c);
                ?>
            </h2>
            <p class="lead"><?php
                $a = new Area('Title Description');
                $a->display($c);
                ?>
            </p>
        </hgroup>
        <?php
        $json_results = $this->controller->getAwardsListing();
        //Check if call is successful
        if (isset($json_results) && $json_results["code"] == "101" && $json_results["status"] == "Success") {

            // Get the awards from the JSON feed
            $json_awards_list = $json_results["data"];

            // Check that values were returned
            if (is_array($json_awards_list)) {

                // Loop through the awards list
                foreach ($json_awards_list as $awards) {
                    // Get the content of the award
                    echo "<h3>" . $awards["awardName"] . "</h3>";
                    echo "<p>" . $awards["Teaser"] . "</p>";
                    echo "<p><strong>Submission Deadline: " . date("F d", strtotime($awards["SubmissionDeadline"])) . "</strong><br/>";
                    echo "<span class=\"label label-info\">Previous award winners are not eligible.</span><br/>";
                    echo "<a style=\"text-decoration:none;\" class=\"button\" href=\"awards_detail?awardsid=" . $awards["AwardID"] . "\">View Award</a></p>";
                    echo "<hr>";
                }
            } else {
                echo "<p>There are no awards available at the moment.</p>";
            }
        } else {
            echo "<p>There was a problem in retrieving the data. Please try again.</p>";
        }
        ?>
	<?php
        $a = new Area('Awards Content');
        $a->display($c);
        ?>
    </div>
</div>