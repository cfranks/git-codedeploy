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
                    <li class=""><a href="/awards/awards_listing-1" target="_self" class="">Awards Listing</a></li>
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
    <?php
    if (isset($_GET["awardsid"])) {
        $json_results = $this->controller->getAwardsDetailByID($_GET["awardsid"]);

        //Check if call is successful
        if (isset($json_results) && $json_results["code"] == "101" && $json_results["status"] == "Success") {

            // Get the awards from the JSON feed
            $json_awards_detail = $json_results["data"];
            ?>
            <script type="text/javascript">
                document.title = "AADPRT :: <?= $json_awards_detail["awardName"]; ?>"
            </script>
            <div class="col-sm-9">
                <hgroup>
                    <p><a href="/awards/awards_listing-1"><< Back to awards listing</a></p>
                    <h2><?php echo $json_awards_detail["awardName"] ?></h2>
                    <p><strong>Submission Deadline: <?php echo date("F d", strtotime($json_awards_detail["SubmissionDeadline"])) ?></strong></p>
                </hgroup>
                <?php
                // display award information
                echo "<p>" . $json_awards_detail["Description"] . "</p>";
                echo "<p><strong>Submission Deadline: " . date("F d", strtotime($json_awards_detail["SubmissionDeadline"])) . "<b><br></b></strong></p>";

                // Check for any uploaded files
                if (is_array($json_awards_detail["materials"])) {
                    echo "<p>";
                    foreach ($json_awards_detail["materials"] as $material) {
                        echo "<a href=\"" . $material["path"] . "\" target=\"_blank\">" . $material["Name"] . "</a><br/>";
                    }
                    echo "</p>";
                }
                $redirectURL = urlencode("https://aadprt.informaticsinc.net/user/award/nomination?awardID=" . $json_awards_detail["AwardID"]);
                $currentDate=date("Y-m-d");
                if($json_awards_detail["SubmissionDeadline"] >= $currentDate){
                echo "<a href=\"https://aadprt.informaticsinc.net/login?redirect=" . $redirectURL . "\" class=\"button\" target=\"_blank\">Submit Nomination</a><br/>";
                }
                echo "<p></p><p>Please be prepared to upload all necessary documents.<br><br>A confirmation email will be sent when your complete electronic submission is received. If you do not receive a confirmation within two business days".(isset($json_awards_detail["awardName"]) && stripos($json_awards_detail["awardName"], "Teichner") === false && stripos($json_awards_detail["awardName"], "LIFETIME") === false ? "" : " of submitting your paper").", email AADPRT at <a href=\"mailto:exec@aadprt.org\">exec@aadprt.org</a>." . (isset($json_awards_detail["awardName"]) && stripos($json_awards_detail["awardName"], "Teichner") === false && stripos($json_awards_detail["awardName"], "LIFETIME") === false ? "<br/><br/>All requests for confirmation must be received before the deadline. Materials will not be accepted or considered after the deadline." : "") ."</p>";
            } else {
                echo "<p>There was a problem in retrieving the data. Please try again.</p>";
            }
        } else {
            echo "<p>There was a problem in retrieving the data. Please try again.</p>";
        }
        ?>
    </div>
</div>