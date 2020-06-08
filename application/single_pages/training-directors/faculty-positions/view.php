<?php
/*
 * Created by: MA 09112015
 * For : Single page for listing jobs for faculty from backend API
 * 
 */
function getJobsListing() {
    //Set api URL
    $url = Config::get('custom.PortalURL') .'api/v1/jobs/1';

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

 $json_results = getJobsListing();
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

                $jobDescriptionNoTag = trim(strip_tags($jobs["JobDescription"]));
                $jobDescription = substr($jobDescriptionNoTag, 0, 200) . (strlen($jobDescriptionNoTag) > 200 ? " ... " : "");
                

                echo $jobDescription . "<br/><br/>";
                echo "<p><strong>Application Deadline: " . date("F d, Y", strtotime($jobs["ApplicationExpirationDate"])) . "</strong><br/>";
                echo "<br/><a class=\"btn-theme\" href=\"/training-directors/faculty-positions/jobs-detail?type=1&jobsid=" . $jobs["JobDetailID"] . "\">View Position</a></p>";
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