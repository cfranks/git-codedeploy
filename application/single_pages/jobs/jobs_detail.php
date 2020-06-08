<?php
/*
 * Created by: MA 09112015
 * For : Single page for displaying awards detail based on id from listing page, and gets results from backedn API
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
                    <?php
                    if ($_GET["type"] == "1") {
                        ?>
                        <li class="nav-selected nav-path-selected"><a href="/jobs/jobs_listing_faculty" target="_self" class="nav-selected nav-path-selected">Current Available Positions</a></li>
                        <?php
                    } else {
                        ?>
                        <li class="nav-selected nav-path-selected"><a href="/jobs/jobs_listing_fellowship" target="_self" class="nav-selected nav-path-selected">Current Available Positions</a></li>
                    <?php } ?>
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
            <?php
            if ($_GET["type"] == "1") {
                ?>
                <p><a href="/jobs/jobs_listing_faculty"><< Back to positions listing</a></p>
                <?php
            } else {
                ?>
                <p><a href="/jobs/jobs_listing_fellowship"><< Back to positions listing</a></p>
            <?php } ?>
            <?php
            if (isset($_GET["jobsid"])) {
                $json_results = $this->controller->getJobsDetailByID($_GET["jobsid"]);

                //Check if call is successful
                if (isset($json_results) && $json_results["code"] == "101" && $json_results["status"] == "Success") {

                    // Get the awards from the JSON feed
                    $json_jobs_detail = $json_results["data"];
                    ?>
                    <script type="text/javascript">
                        document.title = "AADPRT :: <?= $json_jobs_detail["Title"]; ?>"
                    </script>
                    <h2><?php echo $json_jobs_detail["Title"] ?></h2>
                    <p><strong>Application Deadline: <?php echo date("F d, Y", strtotime($json_jobs_detail["ApplicationExpirationDate"])) ?></strong></p>
                </hgroup>

                <?php
                // display award information
                echo "<p>" . $json_jobs_detail["JobDescription"] . "</p>";
                echo "<p><strong>Contact Information:</strong><br/><br/>";
                echo $json_jobs_detail["ContactFirstName"] . " " . $json_jobs_detail["ContactLastName"] . "<br/>";
                if ($json_jobs_detail["ConatctTitle"] != "") {
                    echo $json_jobs_detail["ConatctTitle"] . "<br/>";
                }

                echo $json_jobs_detail["ContactEmail"] . "<br/>";
                echo $json_jobs_detail["ContactAddress1"] . "<br/>";

                if ($json_jobs_detail["ContactAddress2"] != "") {
                    echo $json_jobs_detail["ContactAddress2"] . "<br/>";
                }
                echo $json_jobs_detail["ContactCity"] . ", " . $json_jobs_detail["ContactSate"] . " " . $json_jobs_detail["ContactZipcode"] . "<br/>";

                if ($json_jobs_detail["ContactPhone"] != "") {
                    echo $json_jobs_detail["ContactPhone"] . "<br/>";
                }
                echo "</p>";
            } else {
                echo "<p>There was a problem in retrieving the data. Please try again.</p>";
            }
        } else {
            echo "<p>There was a problem in retrieving the data. Please try again.</p>";
        }
        ?>
    </div>
</div>