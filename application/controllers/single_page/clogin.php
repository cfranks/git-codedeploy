<?php namespace Application\Controller\SinglePage;

use PageController;
use User;
use Config;

/**
 * Clogin Class for providing the login logout functionalities
 * 
 * @author DS 17/09/2015
 */
class Clogin extends PageController
{

    /**
     * User Array to define the user ids based on user roles.
     * 
     * @var Array 
     * @author DS 17/09/2015
     */
    protected $userArray = array(
        'affiliate' => 2, // Affiliate member
        'coordinator' => 3, // Institute Coordinato
        'imember' => 4, // Institute member
        'cadmin' => 5 // Portal Admin
    );

    /**
     * View function the default function that will be called when the page is hit
     * 
     * @author DS 17/09/2015
     */
    public function view()
    {
        if (isset($_GET['type'])) {
            // If login type set then login
            $type = $_GET['type'];
            $this->getLoggedIn($type);
        } else {
            // If a user is logged on log out
            $u = new User();
            if ($u->isLoggedIn()) {
               $u->logout();
            }
        }
        // Redirect back to login page in the portal side
        $url = 'Location: '.Config::get('custom.PortalURL').'login';
        header($url);
        exit;
    }

    /**
     * Function to login the user
     * 
     * @param type $type
     * @author DS 17/09/2015
     */
    public function getLoggedIn($type)
    {
        // Get User ID
        $userID = $this->userArray[$type];
        User::loginByUserID($userID); // Log in the user based on user id
    }
}
