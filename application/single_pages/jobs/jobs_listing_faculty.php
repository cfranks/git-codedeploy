<?php
/*
 * Created by: MA 09112015
 * For : Single page for listing jobs for faculty from backend API
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
                    <li class="nav-selected nav-path-selected"><a href="/jobs/jobs_listing_faculty" target="_self" class="nav-selected nav-path-selected">Current Available Positions</a></li>
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
        $json_results = $this->controller->getJobsListing();
        //Check if call is successful
       if (isset($json_results) && $json_results["code"] == "101" && $json_results["status"] == "Success") {

            // Get the awards from the JSON feed
            $json_jobs_list = $json_results["data"];

            // Check that values were returned
            if (is_array($json_jobs_list)) {
                if (count($json_jobs_list) > 0) {
                    // Loop through the awards list
                    foreach ($json_jobs_list as $jobs) {
                        // Get the content of the award
                        echo "<h3>" . $jobs["Title"] . "</h3>";

                        $jobDescriptionNoTag = strip_tags($jobs["JobDescription"]);
                        $jobDescription = substr($jobDescriptionNoTag, 0, 200) . " ... ";

                        echo $jobDescription . "<br/><br/>";
                        echo "<p><strong>Application Deadline: " . date("F d, Y", strtotime($jobs["ApplicationExpirationDate"])) . "</strong><br/>";
                        echo "<a style=\"text-decoration:none;\" class=\"button\" href=\"jobs_detail?type=1&jobsid=" . $jobs["JobDetailID"] . "\">View Position</a></p>";
                        echo "<hr>";
                    }
                } else {
                    echo "<p>There are no positions posted at this time.</p>";
                }
            } else {
                echo "<p>There are no positions posted at this time.</p>";
            }
        } else {
            echo "<p>There was a problem in retrieving the data. Please try again.</p>";
        }
        ?>
    </div>
</div>