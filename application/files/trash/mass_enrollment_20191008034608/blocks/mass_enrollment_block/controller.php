<?php

/** Include Block Namespaces * */

namespace Concrete\Package\MassEnrollment\Block\MassEnrollmentBlock;

/** Use Blocks and Package * */
use Concrete\Core\Block\BlockController;
use Config;
use Package;
use Loader;
use Core;
use Database;
use Concrete\Package\MassEnrollment\Src\EnrollmentModel;
use Concrete\Package\MassEnrollment\Src\SafeSave;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController
{
    /**
     * Protected variables
     * 
     * @btTable defines the name of the block table
     * @btInterfaceWidth Defines the width of add/edit block interface 
     * @btInterfaceHeight Defines the width of add/edit block interface 
     */
    protected $btTable = 'btMassEnrollment';
    protected $btInterfaceWidth = "375";
    protected $btInterfaceHeight = "450";

    /**
     * Function to return Block Description
     * 
     * @return string
     */
    public function getBlockTypeDescription()
    {
        return t("Mass Enrollment Block");
    }

    /**
     * Function to return Block Type Name
     * 
     * @return string
     */
    public function getBlockTypeName()
    {
        return t("Mass Enrollment");
    }

    /**
     * Function to set up variables
     * 
     * @return type
     * @author JS 09/24/2019
     */
    public function setupForm()
    {
        $package = Package::getByHandle('mass_enrollment');
        $this->set('languages', Config::get('mass_enrollment::languages'));
        $this->set('form_type', Config::get('mass_enrollment::variables.FormType'));
        $this->set('images', $this->getImages());
        $this->set('folders', $this->getfolder());
        if ($this->ifsessionExists($_SESSION['enrollment'])){
            $this->set('data',$_SESSION['enrollment']);
        }
        $this->set('RelativePath', $package->getRelativePath());
    }

    /**
     * Function to process the view and display the view
     * 
     * @return View Resposne
     * @author JS 09/24/2019
     */
    public function view()
    {
        // Safe Save Payment Gateway
        // $gw = new SafeSave;
        // $gw->setLogin();
        // $gw->setBilling("John","Smith","Acme, Inc.","123 Main St","Suite 200", "Beverly Hills",
        //         "CA","90210","US","555-555-5555","555-555-5556","support@example.com",
        //         "www.example.com");
        // $gw->setShipping("Mary","Smith","na","124 Shipping Main St","Suite Ship", "Beverly Hills",
        //         "CA","90210","US","support@example.com");
        // $gw->setOrder("1234","Big Order",1, 2, "PO1234","65.192.14.10");
        // $r = $gw->doSale("51.00","4111111111111111","1010");
        
        //Setup form
        $this->setupForm();
        if (in_array($this->bFormType, [1,2,3,4])) {
            $this->set('format', 'enrollment');
        } else {
            $this->set('format', 'intention');
        }
        $this->addFooterItem("<script>
        $(document).ready(function() {
        $('.datepicker').datepicker();
        });
        </script>");
    }

    /**
     * Function to set the format to open enrollent page
     * 
     * @author SR 09/26/2019
     */
    public function action_enrollment()
    {
        $this->set('format', 'enrollment');
        if ($this->ifsessionExists($_SESSION['enrollment'])){
            $this->set('data',$_SESSION['enrollment']);
        }
    }

    /**
     * Function to set the format to open contact page
     * 
     * @author SR 09/26/2019
     */
    public function action_contact()
    {
        $this->set('format', 'contact');
        if ($this->ifsessionExists($_SESSION['contact'])){
            $this->set('data',$_SESSION['contact']);
        }
    }

    /**
     * Function to set the format to open payment page
     * 
     * @author SR 09/26/2019
     */
    public function action_payment()
    {
        $this->set('format', 'payment');
        if ($this->ifsessionExists($_SESSION['payment'])){
            $this->set('data', $_SESSION['payment']);
        }
    }

     /**
     * Function to set the format to open receoit page
     * 
     * @author SR 09/26/2019
     */
    public function action_receipt()
    {
        $this->set('format', 'receipt');
    }

    /**
     * Function called when the block is added
     * 
     * @author SR 09/26/2019
     */
    public function add()
    {   
        $this->setupForm();
    }

    /**
     * Function called when the block is edited
     * 
     * @author SR 09/26/2019
     */
    public function edit()
    {
        $this->setupForm();
    }

    /**
     * Function called when the block is added
     * 
     * @author SR 09/26/2019
     */
    public function save($args)
    {
        parent::save($args);
    }

    /**
     * Function to get the Model Object
     * 
     * @return type
     * @author SR 09/26/2019
     */
    public function getModel()
    {
        if (!is_object($this->model)) {
            $this->model = new EnrollmentModel();
        }
        return $this->model;
    }

    /**
     * Function to validate the form
     * 
     * @return e
     * @author SR 09/26/2019
     */
    public function validate($args)
    {
        $e = Core::make("helper/validation/error");
        if ($args['bFormType'] == "") {
            $e->add(t('The "Type of Form" is a required field.'));
        }
        if ($args['bLanguage'] == "") {
            $e->add(t('The "Form Language" is a required field.'));
        }
       
        return $e;
    }

    /**
     * Function to get the images.
     * 
     * @return files
     * @author SR 09/26/2019
     */
    public function getImages()
    {
        $db = Database::connection();
        return $db->GetAll("SELECT * FROM ctr_card_images");
    }

    public function getfolder()
    {
        $folder_final = array();
        $db = Database::connection();
        $folder = $db->GetAll("SELECT * FROM ctr_folder");
        foreach($folder as $fol) {
            $folder_final[$fol['fID']] = $fol;
        }
        return $folder_final;
    }

    /**
     * Function to store the fields in the enrollment detail page
     * 
     * @return type
     * @author GJ 26/09/19
     */    
    public function action_submit_enrollment()
    {
        if ($this->isPost()) {
            $data = $this->request->request->all();
            $error = $this->validate_enrollment($data);
            if (!$error->has()) {
                if($data['e_country'] == "USA"){
                    $data['e_state'] = $data['e_stateDropdown'];
                } else {
                    $data['e_state'] = $data['e_stateText'];
                }
                unset($data['e_stateDropdown']);
                unset($data['e_stateText']);
                session_start();
                // foreach($data as $key => $values){
                //     echo "'".$key."'";
                //     echo "<br/>";
                // }
                // die;
                $_SESSION['enrollment'] = $data;
                // print_r($_SESSION['enrollment']);
                // die;
                $this->action_contact();                         
            } else {
                $this->set('error_form', true);
                $this->set('error_messages', $error->getList());
                $this->action_enrollment();                         
            }
        }
        else{
            $this->action_enrollment();   
        }
    }

    /**
     * Function to store the fields in the contact detail page
     * 
     * @return type
     * @author SR 25/09/19
     */
    public function action_submit_contact()
    {
        if ($this->isPost()) {
            $data = $this->request->request->all();
            $error = $this->validate_contact($data);
            if (!$error->has()) {
                if($data['c_country'] == "USA"){
                    $data['c_state'] = $data['c_stateDropdown'];
                } else {
                    $data['c_state'] = $data['c_stateText'];
                }
                unset($data['c_stateDropdown']);
                unset($data['c_stateText']);
                session_start();
                // foreach($data as $key => $values){
                //     echo "'".$key."'";
                //     echo "<br/>";
                // }
                // die;
                $_SESSION['contact'] = $data;
                // print_r($_SESSION['contact']['c_state']);
                // die();

                $this->action_payment();                         
            } else {
                $this->set('error_form', true);
                $this->set('error_messages', $error->getList());
                $this->action_contact();                         
            }
        }
        else{
            $this->action_contact();   
        }
    }

    public function action_submit_payment()
    {   
         if ($this->isPost()) {
            $data = $this->request->request->all();
            $error = $this->validate_payment($data);
            // $model = $this->getModel(); 
            if (!$error->has()) {
                if($data['p_country'] == "USA"){
                    $data['p_state'] = $data['p_stateDropdown'];
                } else {
                    $data['p_state'] = $data['p_stateText'];
                }
                unset($data['p_stateDropdown']);
                unset($data['p_stateText']);
                session_start();
                // foreach($data as $key => $values){
                //     echo "'".$key."'";
                //     echo "<br/>";
                // }
                // die;
                
                // Adding Contact details form Contact tabs to Billing Details.
                if(isset($data['chkSameAsContact'])){
                    $contact = $_SESSION['contact'];
                    $data['p_address'] = $contact['c_address'];
                    $data['p_city'] = $contact['c_city'];
                    $data['p_country'] = $contact['c_country'];
                    $data['p_zip'] = $contact['c_zip'];
                } 
                $_SESSION['payment'] = $data;
                $_SESSION['data'] = array_merge((array) $_SESSION['enrollment'],(array) $_SESSION['contact'], (array) $_SESSION['payment']);
                // print_r($_SESSION['data']);
                // die;
                $this->getModel()->add($_SESSION['data']);
                $this->set('message_custom', translate('MessageSuccess', $this->bLanguage, false));
               // Unset all of the session variables.
                $_SESSION = array();
                // Finally, destroy the session.
                session_destroy();
                $this->view();
            } else {
                $this->set('error_form', true);
                $this->set('error_messages', $error->getList());
                $this->action_payment();
            }
        }else{
         $this->action_payment();    
        }
    }

     /**
     * Function to validate the fields
     * 
     * @return type
     * @author SR 25/09/19
     */
    public function validate_enrollment($data = array())
    {
        if (empty($data)) {
            $data = $this->request->request->all();
        }
        $val = Loader::helper('validation/form');
        $vals = Loader::helper('validation/strings');
        $required = array();
        if($data['dChkSendNotification'] == 'checked'){
            $required = [
                'e_notification_language' => 'NotificationLanguageRequired',
                'e_first_name' => 'FirstNameRequrired',
                'e_last_name' => 'LastNameRequrired',
                'e_address' => 'AddressRequired',
                'e_city' => 'CityRequired',
                'e_country' => 'CountryRequired',
                'e_zip' => 'ZipRequired',
            ]; 
        }

         // Add required Fields
         foreach ($required as $key => $value) {
            $val->addRequired($key, $value);
        }

        $val->setData($data);
        $val->test();
        $e = $val->getError();
        
        //adding validation for dropdown and textbox depending the country chosen (USA)
        if (isset($data['e_support_donation']) && $data['e_support_donation'] == '') {
            $e->add('SupportWithDonationRequired');
        } 
        //validate when send notification checkbox is checked.
        if ($data['dChkSendNotification'] == 'checked') {
            if (isset($data['e_email']) && !empty($data['e_email']) && !filter_var($data['e_email'], FILTER_VALIDATE_EMAIL)) {
                $e->add('EmailAddressInvalid');
            }
              //adding validation for dropdown and textbox depending the country chosen (USA)
            if (isset($data['e_country']) && $data['e_country'] == "USA") {
                $val->addRequired('e_stateDropdown','At least one "State" should be selected');
            } else if (!empty($data['e_country'])) {
                $val->addRequired('e_stateText','The "State" field is required');
            }   
        }
          
        return $e;
    }

    /**
     * Function to validate the contact form
     * 
     * @return type
     * @author SR 24/09/2019
     */
    protected function validate_contact($data = array())
    {
        if (empty($data)) {
            $data = $this->request->request->all();
        }
        $val = Loader::helper('validation/form');
        $vals = Loader::helper('validation/strings');
        $required = [
            'c_first_name' => 'FirstNameRequrired',
            'c_last_name' => 'LastNameRequrired',
            'c_address' => 'AddressRequired',
            'c_city' => 'CityRequired',
            'c_country' => 'CountryRequired',
            'c_email' => 'EmailAddressRequrired',
            'c_zip' => 'ZipRequired'

        ];

        // Add required Fields
        foreach ($required as $key => $value) {
            $val->addRequired($key, $value);
        }
        //adding validation for dropdown and textbox depending the country chosen (USA)
        if (isset($data['c_country']) && $data['c_country'] == "USA") {
            $val->addRequired('c_stateDropdown','StateRequired');
        } else if (!empty($data['c_country'])) {
            $val->addRequired('c_stateText','StateRequired');
        }  
        //adding validation zip to be integr
        // if (isset($data['c_zip']) && !empty($data['c_zip'])) {
        //     $val->addInteger('c_zip','ZipRequired');
        // }
        $val->setData($data);
        $val->test();
        $e = $val->getError();
        //validate email
        if (isset($data['c_email']) && !empty($data['c_email']) && !filter_var($data['c_email'], FILTER_VALIDATE_EMAIL)) {
            $e->add('EmailAddressInvalid');
        } 
        
        return $e;
    }

    
    protected function validate_payment($data = array())
    {
        if (empty($data)) {
            $data = $this->request->request->all();
        }
        $val = Loader::helper('validation/form');
        $vals = Loader::helper('validation/strings');
        $required = [
            'p_card_holder_name' => 'CardHolderNameRequired',
            'p_card_type' => 'CardTypeRequired',
            'p_card_number' => 'CardNumberRequired',
            'p_exp_month' => 'ExpiryMonthRequired',
            'p_exp_year' => 'ExpiryYearRequired',
            'p_cvv' => 'CVVRequired'
            
        ];
        //if Same as contact checkbox is checked then add the validation for Billing Details
        if(!isset($data['chkSameAsContact'])) {
            $required['p_address'] = 'AddressRequired';
            $required['p_city'] = 'CityRequired';
            $required['p_country'] = 'CountryRequired';
            $required['p_zip'] = 'ZipRequired';
        }
         // Add required Fields
         foreach ($required as $key => $value) {
            $val->addRequired($key, $value);
        }
        //adding validation for dropdown and textbox depending the country chosen (USA)
        if (isset($data['p_country']) && $data['p_country'] == "USA") {
            $val->addRequired('p_stateDropdown','At least one "State" should be selected');
        } else if (!empty($data['p_country'])) {
            $val->addRequired('p_stateText','The "State" field is required');
        } 
        //adding integer validation to CVV
        if (isset($data['p_cvv']) && !empty($data['p_cvv'])) {
            $val->addInteger('p_cvv','CVVInteger');
        }
        //adding integer validation to zip
        if (isset($data['p_zip']) && !empty($data['p_zip'])) {
            $val->addInteger('p_zip','ZipInteger');
        }

        $val->setData($data);
        $val->test();
        $e = $val->getError();
        
         //validate email
         if (isset($data['p_email']) && !empty($data['p_email']) && !filter_var($data['p_email'], FILTER_VALIDATE_EMAIL)) {
            $e->add('Invalid Email given.');
        } 

        return $e;
    }

    public function ifsessionExists($session){
        // check if session exists?
          if(!empty($session)){
            return true;
          } else {
            return false;
          }
    }

}
