<?php
/*
 * Created by: MA 09112015
 * For : Single page for displaying awards detail based on id from listing page, and gets results from backedn API
 * 
 */
 
 function getAwardsDetailByID($awardsID) {
        //Set api URL
        $url = Config::get('custom.PortalURL').'api/v1/awardDetail/' . $awardsID;

        // Initiate a CURL object
        $ch = curl_init();
        $data = array('authKey' => '367qm9scs83H3EMw');
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);                //set to 1 for post method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_VERBOSE, TRUE); // Change to FALSE on live server
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        // Get CURL Response
        $response = curl_exec($ch);

        // Decode the JSON feed
        $json_results = json_decode($response, true);
        return $json_results;
    }
?>
    <?php
    if (isset($_GET["awardsid"])) {
        $json_results = getAwardsDetailByID($_GET["awardsid"]);

        //Check if call is successful
        if (isset($json_results) && $json_results["code"] == "101" && $json_results["status"] == "Success") {

            // Get the awards from the JSON feed
            $json_awards_detail = $json_results["data"];
            ?>
            <script type="text/javascript">
                document.title = "<?= $json_awards_detail["awardName"]; ?> | AADPRT"
            </script>
                <hgroup>
                    <p><a href="/annual-meeting/awards-fellowships"><< Back to awards listing</a></p>
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
                $redirectURL = urlencode(Config::get('custom.PortalURL')."user/award/nomination?awardID=" . $json_awards_detail["AwardID"]);
                $currentDate=date("Y-m-d");
                if($json_awards_detail["SubmissionDeadline"] >= $currentDate){
                echo "<a href=\"".Config::get('custom.PortalURL')."login?redirect=" . $redirectURL . "\" class=\"btn-theme\" target=\"_blank\">Submit Nomination</a><br/>";
                }
                echo "<p></p><p>Please be prepared to upload all necessary documents.<br><br>A confirmation email will be sent when your complete electronic submission is received. If you do not receive a confirmation within two business days".(isset($json_awards_detail["awardName"]) && stripos($json_awards_detail["awardName"], "Teichner") === false && stripos($json_awards_detail["awardName"], "LIFETIME") === false ? "" : " of submitting your paper").", email AADPRT at <a href=\"mailto:exec@aadprt.org\">exec@aadprt.org</a>." . (isset($json_awards_detail["awardName"]) && stripos($json_awards_detail["awardName"], "Teichner") === false && stripos($json_awards_detail["awardName"], "LIFETIME") === false ? "<br/><br/>All requests for confirmation must be received before the deadline. Materials will not be accepted or considered after the deadline." : "") ."</p>";
            } else {
                $url = 'Location: /annual-meeting/awards-fellowships';
        header($url);
        exit;
            }
        } else {
	
            $url = 'Location: /annual-meeting/awards-fellowships';
        header($url);
        exit;
        }
        ?>