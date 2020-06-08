<?php
/*
 * Created by: MA 09112015
 * For : Single page for displaying awards detail based on id from listing page, and gets results from backedn API
 * 
 */

function getJobsDetailByID($jobsID) {
    //Set api URL
    $url = Config::get('custom.PortalURL') .'api/v1/jobDetail/' . $jobsID;

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
    <hgroup>
    <?php
    if ($_GET["type"] == "1") {
        ?>
        <p><a href="/training-directors/faculty-positions"><< Back to positions listing</a></p>
        <?php
    } else {
        ?>
        <p><a href="/jobs/jobs_listing_fellowship"><< Back to positions listing</a></p>
    <?php } ?>
    <?php
    if (isset($_GET["jobsid"])) {
        $json_results = getJobsDetailByID($_GET["jobsid"]);

        //Check if call is successful
        if (isset($json_results) && $json_results["code"] == "101" && $json_results["status"] == "Success") {

            // Get the awards from the JSON feed
            $json_jobs_detail = $json_results["data"];
            ?>
            <script type="text/javascript">
                document.title = "<?= $json_jobs_detail["Title"]; ?> | AADPRT"
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
        if ($_GET["type"] == "1") {
            $url = '/training-directors/faculty-positions';
        } else {
            $url = '/';
        }
        $url = 'Location: '.$url;
        header($url);
        exit;
    }
    } else {
        if ($_GET["type"] == "1") {
            $url = '/training-directors/faculty-positions';
        } else {
            $url = '/';
        }
        $url = 'Location: '.$url;
        header($url);
        exit;
    }
