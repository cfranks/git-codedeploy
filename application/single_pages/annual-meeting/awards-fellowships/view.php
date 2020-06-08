<?php
/*
 * Created by: MA 09112015
 * For : Single page for listing awards from backend API
 * 
 */
 
 function getAwardsListing() {
        //Set api URL
        $url = Config::get('custom.PortalURL').'api/v1/awards';

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
        $a = new Area('Awards Content 1');
        $a->display($c);
        ?>
        <?php
        $json_results = getAwardsListing();
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
                    echo "<br/><a class=\"btn-theme\" href=\"/annual-meeting/awards-fellowships/award-detail?awardsid=" . $awards["AwardID"] . "\">View Award</a></p>";
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
        $a = new Area('Awards Content 2');
        $a->display($c);
        ?>