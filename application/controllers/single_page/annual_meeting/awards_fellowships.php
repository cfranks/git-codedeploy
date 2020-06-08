<?php

/*
 * Created by: MA 09112015
 * For : Single page for listing awards from backend API
 * 
 */

namespace Application\Controller\SinglePage\AnnualMeeting;
use PageController;

class AwardsFellowships extends PageController {

    public function getAwardsListing() {
        //Set api URL
        $url = 'https://portal.aadprt.org/api/v1/awards';

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

}