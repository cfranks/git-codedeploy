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
use Page;
use Concrete\Package\MassEnrollment\Src\EnrollmentModel;
use Concrete\Package\MassEnrollment\Src\SafeSave;
use Concrete\Package\MassEnrollment\Src\DonorPerfect;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController {

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
        $country = Core::make('helper/lists/countries');
        $state = Core::make('helper/lists/states_provinces');
        $countries = $country->getCountries();
        $this->requireAsset('jquery/ui');
        $package = Package::getByHandle('mass_enrollment');
        $languages = $this->getTransDD(Config::get('mass_enrollment::cardlanguages'));
        // GJ Update sort language
        asort($languages);
        $occasions = $this->getTransDD(['' => 'Select Occasion'] + Config::get('mass_enrollment::custom.occasion'));
        $support_donation = $this->getTransDD(Config::get('mass_enrollment::custom.SupportDonation'));
        $expireMonth = $this->getTransDD(['' => translate('ExpMonthPlaceholder', $bLanguage, false)] + Config::get('mass_enrollment::custom.ExpireMonth'));
        $notificationLanguage = $this->getNotificationLanguages();
        $novenaCardLanguages =  $this->getTransDD(Config::get('mass_enrollment::languages'));
        unset($novenaCardLanguages['vi']);
        if ($this->ifsessionExists($_SESSION[$this->bID . 'enrollment'])) {
            $this->set('data', $_SESSION[$this->bID . 'enrollment']);
        }
        $states = $state->getStateProvinceArray('US') + $state->getStateProvinceArray('CA');
        asort($states);
        $this->set('states', $states);
        $this->set('countries', $countries);
        $this->set('languages', $languages);
        $this->set('occasions', $occasions);
        $this->set('expireMonth', $expireMonth);
        $this->set('images', $this->getImages());
        $this->set('folders', $this->getfolder());
        $this->set('support_donation', $support_donation);
        $this->set('RelativePath', $package->getRelativePath());
        $this->set('notificationLanguage', $notificationLanguage);
        $this->set('novenaCardLanguages', $novenaCardLanguages);
    }

     /**
     * Function to get notification languages 
     * 
     * @return type
     * @author JS 09/24/2019
     */
    function getNotificationLanguages()
    {
        $languages = $this->getTransDD(Config::get('mass_enrollment::languages'));
        if($this->bLanguage == 'vi'){
            unset($languages['pl']);
            unset($languages['sp']);
            unset($languages['pt']);
        } else {
            unset($languages['vi']);
        }
        return $languages;
    }
    
    /**
     * Function to set up variables
     * 
     * @return type
     * @author JS 09/24/2019
     */
    public function setupFormBlock() 
    {
        $package = Package::getByHandle('mass_enrollment');
        $languages = Config::get('mass_enrollment::languages');
	asort($languages);
        $this->set('languages', $languages);
        $this->set('form_type', Config::get('mass_enrollment::custom.FormType'));
    }

    /**
     * Function to process the view and display the view
     * 
     * @return View Resposne
     * @author JS 09/24/2019
     */
    public function view() 
    {
        $this->setupForm();
        if (in_array($this->bFormType, [1, 2, 3, 4])) {
            $this->set('format', 'enrollment');
        } else {
            $this->set('format', 'intention');
        }
        $this->set('step', 'enroll');
    }

    /**
     * Function to set the format to open enrollent page
     * 
     * @author SR 09/26/2019
     */
    public function action_enrollment() {
        $this->view();
    }

    /**
     * Function to set the format to open contact page
     * 
     * @author SR 09/26/2019
     */
    public function action_contact() {
        if (isset($_SESSION[$this->bID . 'enrollment']['StepCompleted']) && $_SESSION[$this->bID . 'enrollment']['StepCompleted'] >= 1) {
            $this->set('format', 'contact');
            $this->setupForm();
            $this->set('step', 'contact');
        } else {
            $page = Page::getCurrentPage();
            $this->redirect($page->getCollectionPath() . '/enrollment/' . $this->bID);
        }
    }

    /**
     * Function to set the format to open payment page
     * 
     * @author SR 09/26/2019
     */
    public function action_payment() {
        if (isset($_SESSION[$this->bID . 'enrollment']['StepCompleted']) && $_SESSION[$this->bID . 'enrollment']['StepCompleted'] >= 2) {

            $this->set('format', 'payment');
	    $this->set('session',$_SESSION[$this->bID . 'enrollment']);
            $this->setupForm();
            $this->set('step', 'payment');
        } else {
            $page = Page::getCurrentPage();
            $this->redirect($page->getCollectionPath() . '/contact/' . $this->bID);
        }
    }

    public function action_receipt() 
    {
        if (isset($_SESSION[$this->bID . 'enrollment']['StepCompleted']) && $_SESSION[$this->bID . 'enrollment']['StepCompleted'] >= 3) {
            $this->set('format', 'receipt');
            $this->setupForm();
            $this->set('step', 'receipt');
            $data = $_SESSION[$this->bID . 'enrollment'];
            $lang = $this->bLanguage;
            $UserEmailTemplate = Config::get('mass_enrollment::custom.emails.' . $lang . '.UserEmailTemplate', Config::get('mass_enrollment::custom.emails.Default.UserEmailTemplate'));
            $AdminEmailTemp = Config::get('mass_enrollment::custom.emails.' . $lang . '.AdminEmailTemp', Config::get('mass_enrollment::custom.emails.Default.AdminEmailTemp'));
            $AdminEmail = Config::get('mass_enrollment::custom.emails.' . $lang . '.AdminEmail', Config::get('mass_enrollment::custom.emails.Default.AdminEmail'));
            $FromEmail = Config::get('mass_enrollment::custom.emails.' . $lang . '.FromEmail', Config::get('mass_enrollment::custom.emails.Default.FromEmail'));
            $FromName = Config::get('mass_enrollment::custom.emails.' . $lang . '.FromName', Config::get('mass_enrollment::custom.emails.Default.FromName'));
            $UserEmailSub = Config::get('mass_enrollment::custom.emails.' . $lang . '.UserEmailSub', Config::get('mass_enrollment::custom.emails.Default.UserEmailSub'));
            $AdminEmailSub = Config::get('mass_enrollment::custom.emails.' . $lang . '.AdminEmailSub', Config::get('mass_enrollment::custom.emails.Default.AdminEmailSub'));
            $AdminEmailTemp =  $this->ReplaceEmailValues($AdminEmailTemp, $data);
            $UserEmailTemplate =  $this->ReplaceEmailValues($UserEmailTemplate, $data);
            // Mail to admin
	    $AdminEmails = explode(",",$AdminEmail);
            foreach($AdminEmails as $email) {
 		if(!empty($email)) {
		    $mail = Loader::helper('mail');
            	    $mail->to($email);
		    $mail->addParameter('subject', $AdminEmailSub);
            	    $mail->from($FromEmail, $FromName);
            	    $mail->addParameter('html', $AdminEmailTemp);
            	    $mail->load('template', 'mass_enrollment');
		    $mail->sendMail();
		}
	    }
            // Send Email to user
            $mail = Loader::helper('mail');
            $mail->to($data['c_email']);
            $mail->addParameter('subject', $UserEmailSub);
            $mail->from($FromEmail, $FromName);
            $mail->addParameter('html', $UserEmailTemplate);
            $mail->load('template', 'mass_enrollment');
            $mail->sendMail();

            if($data['dChkSendNotification'] == 'checked' && $data['e_email']) {
                $lang_notify = $data['e_notification_language'];
	
		$FromEmail = Config::get('mass_enrollment::custom.emails.' . $lang_notify . '.FromEmail', Config::get('mass_enrollment::custom.emails.Default.FromEmail'));
            	$FromName = Config::get('mass_enrollment::custom.emails.' . $lang_notify . '.FromName', Config::get('mass_enrollment::custom.emails.Default.FromName'));
                $UserEmailSub = Config::get('mass_enrollment::custom.emails.' . $lang_notify . '.NotifyEmailSub', Config::get('mass_enrollment::custom.emails.Default.NotifyEmailSub'));
                $UserEmailTemplate = Config::get('mass_enrollment::custom.emails.' . $lang_notify . '.NotifyEmailTemp', Config::get('mass_enrollment::custom.emails.Default.NotifyEmailTemp'));
                $UserEmailTemplate =  $this->ReplaceEmailValues($UserEmailTemplate, $data,$lang_notify);	
                $mail = Loader::helper('mail');
                $mail->to($data['e_email']);
                $mail->addParameter('subject', $UserEmailSub);
                $mail->from($FromEmail, $FromName);
                $mail->addParameter('html', $UserEmailTemplate);
                $mail->load('template', 'mass_enrollment');
                $mail->sendMail();
            }

            $html = Config::get('mass_enrollment::receipts.' . $lang . '.Template', Config::get('mass_enrollment::receipts.Default.Template'));
            $recpt = $this->ReplaceRecieptValues($html, $data);
            $this->set('html', $recpt);
            unset($_SESSION[$this->bID . 'enrollment']);
        } else {
            $page = Page::getCurrentPage();
            $this->redirect($page->getCollectionPath() . '/payment/' . $this->bID);
        }
    }

    public function ReplaceRecieptValues($html, $data)
    {
        $languages = Config::get('mass_enrollment::cardlanguages');
        $data['e_language'] = translate($languages[$data['e_language']],$this->bLanguage, false); 
        $data['i_living_deceased'] = translate($data['i_living_deceased'].'label',$this->bLanguage, false);
	$not_applicable = translate(notApplicable, $this->bLanguage, false);
        $keys = [
            '{first_name}' => 'c_first_name',
            '{last_name}' => 'c_last_name',
            '{intention}' => 'e_intention',
            '{card_lang}' => 'e_language',
            '{living_or_deceased}' => 'i_living_deceased',
            '{requested_by}' => 'e_requested_by',
            '{notify_first_name}' => 'e_first_name',
            '{notify_last_name}' => 'e_last_name',
            '{gift_id}' => 'gift_id',
            '{email}' => 'c_email',
            '{transaction_id}' => 'transactionid'
        ];
        $billing = '';
        $address = array();
        $city = array();
        isset($data['p_address']) && !empty($data['p_address']) ? $address[] = $data['p_address'] : '';
        isset($data['p_address2']) && !empty($data['p_address2']) ? $address[] = $data['p_address2'] : '';
        isset($data['p_city']) && !empty($data['p_city']) ? $city[] = $data['p_city'] : '';
        isset($data['p_state']) && !empty($data['p_state']) ? $city[] = $data['p_state'] : '';
        isset($data['p_country']) && !empty($data['p_country']) ? $city[] = $data['p_country'] : '';
        isset($data['p_zip']) && !empty($data['p_zip']) ? $city[] = $data['p_zip'] : '';
        $address[] = implode(" ", $city);
        $billing = implode(",<br/>", $address);
        $undefkeys = [
            '{name_of_form}' => translate('Form'.$this->bFormType,$this->bLanguage, false),
            '{folder_color}' => ($data['folder'] ? $this->getFolderData($data['folder'], $this->bLanguage,true) : $not_applicable),
            '{folder_image}' => ($data['card'] ? $this->getImageData($data['card'], $this->bLanguage,true) : $not_applicable),
            '{international_shipping}' => ($this->calculateShippingPrice($data) ? translate('Yes',$this->bLanguage, false) : translate('No',$this->bLanguage, false)),
            '{pe_e_card}' => ($this->bFormType==2 ? BASE_URL . '/card_preview/' . uniqid($data['cID'].'-') : $not_applicable),
            '{amount}' => '$' . $data['i_donation_amount'],
            '{billing_address}' => $billing
        ];
        foreach($keys as $key => $val) {
            if ($data[$val]) {
                $html = str_replace($key, $data[$val], $html);
            } else {
                if($key == '{notify_last_name}'){
                    $html = str_replace($key, '', $html);    
                }
                $html = str_replace($key, $not_applicable, $html);
            }
        }
        foreach($undefkeys as $key => $val) {
            $html = str_replace($key, $val, $html);
        }
        return $html;
    }

    public function ReplaceEmailValues($html, $data,$translating_language = 0) 
    {
    	//Assign translating language with Current active language. 
    	if(empty($translating_language)) {
	     $translating_language = $this->bLanguage;     
	}
	
        $languages = Config::get('mass_enrollment::cardlanguages');
        $data['e_language'] = translate($languages[$data['e_language']],$translating_language, false);
	$not_applicable =  translate(notApplicable,$translating_language, false);
        $data['i_living_deceased'] = translate($data['i_living_deceased'].'label',$translating_language, false);
        $keys = [
            '{first_name}' => 'c_first_name',
            '{last_name}' => 'c_last_name',
            '{intention}' => 'e_intention',
            '{card_lang}' => 'e_language',
            '{living_or_deceased}' => 'i_living_deceased',
            '{requested_by}' => 'e_requested_by',
            '{notify_first_name}' => 'e_first_name',
            '{notify_last_name}' => 'e_last_name',
            '{gift_id}' => 'gift_id',
        ];
        $undefkeys = [
            '{name_of_form}' => translate('Form'.$this->bFormType,$translating_language, false),
            '{folder_color}' => ($data['folder'] ? $this->getFolderData($data['folder'], $translating_language,true) : $not_applicable),
            '{folder_image}' => ($data['card'] ? $this->getImageData($data['card'], $translating_language,true) : $not_applicable),
            '{international_shipping}' => ($this->calculateShippingPrice($data) ? translate('Yes',$translating_language, false) : translate('No',$translating_language, false)),
            '{amount}' => '$' . $data['i_donation_amount'],
            '{pe_e_card}' => ($this->bFormType==2 ? BASE_URL . '/card_preview/' . uniqid($data['cID'].'-') : $not_applicable)
        ];
        foreach($keys as $key => $val) {
            if ($data[$val]) {
                $html = str_replace($key, $data[$val], $html);
            } else {
                if($key == '{notify_last_name}'){
                    $html = str_replace($key, '', $html);    
                }
                $html = str_replace($key, $not_applicable, $html);
            }
        }
        foreach($undefkeys as $key => $val) {
            $html = str_replace($key, $val, $html);
        }
        return $html;
    }

    /**
     * Function to get the Model Object
     * 
     * @return type
     * @author SR 09/26/2019
     */
    public function getModel() {
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
    public function validate($args) {
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
	$ch = $this->bLanguage;
	switch ($ch) {
		case 'en':
		 return $db->GetAll("SELECT * FROM ctr_card_images C LEFT JOIN lnk_images_language L ON C.iID = L.image_id WHERE L.english = 1");
		 break;
		case 'pl':
		 return $db->GetAll("SELECT * FROM ctr_card_images C LEFT JOIN lnk_images_language L ON C.iID = L.image_id WHERE L.polish = 1");
		 break;
		case 'sp':
		 return $db->GetAll("SELECT * FROM ctr_card_images C LEFT JOIN lnk_images_language L ON C.iID = L.image_id WHERE L.spanish = 1");
		 break;
		case 'pt':
		 return $db->GetAll("SELECT * FROM ctr_card_images C LEFT JOIN lnk_images_language L ON C.iID = L.image_id WHERE L.portuguese = 1");
		 break;
		case 'vi':
		 return $db->GetAll("SELECT * FROM ctr_card_images C LEFT JOIN lnk_images_language L ON C.iID = L.image_id WHERE L.vietnamese = 1");
		 break;
		default:
		 return $db->GetAll("SELECT * FROM ctr_card_images");
	}
	if($this->bLanguage == 'en') {
		return $db->GetAll("SELECT * FROM ctr_card_images");
	} else 
        return $db->GetAll("SELECT * FROM ctr_card_images");
    }

    /**
     * Function to get the images.
     * 
     * @return files
     * @author SR 09/26/2019
     */
    public function getfolder() 
    {
        $folder_final = array();
        $db = Database::connection();
        $folder = $db->GetAll("SELECT * FROM ctr_folder");
        foreach ($folder as $fol) {
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
        $page = Page::getCurrentPage();
        if ($this->isPost()) {
            $data = $this->request->request->all();
            $error = $this->validate_enrollment($data);
            if (!$error->has()) {
                if (($data['e_country'] == "US" || $data['e_country'] == "CA")) {
                    $data['e_state'] = $data['e_stateDropdown'];
                } 
                unset($data['e_stateDropdown']);
                if (!isset($_SESSION[$this->bID . 'enrollment']['StepCompleted']) || empty($_SESSION[$this->bID . 'enrollment']['StepCompleted'])) {
                    $_SESSION[$this->bID . 'enrollment']['StepCompleted'] = 1;
                }
                $_SESSION[$this->bID . 'enrollment'] = array_merge($_SESSION[$this->bID . 'enrollment'], $data);
                if(!isset($data['dChkSendNotification'])){
                    unset($_SESSION[$this->bID . 'enrollment']['dChkSendNotification']);
                }
                $_SESSION['message'] = 'EnrollFormSaved';
                $this->redirect($page->getCollectionPath() . '/contact/' . $this->bID);
            } else {
                $this->set('error_form', true);
                $this->set('error_messages', $error->getList());
                $this->action_enrollment();
            }
        } else {
            $this->action_enrollment();
        }
    }

    /**
     * Function to store the fields in the contact detail page
     * 
     * @return type
     * @author SR 25/09/19
     */
    public function action_submit_contact() {
        $page = Page::getCurrentPage();
        if ($this->isPost()) {
            $data = $this->request->request->all();
            $error = $this->validate_contact($data);
            if (!$error->has()) {
                if (($data['c_country'] == "US" || $data['c_country'] == "CA")) {
                    $data['c_state'] = $data['c_stateDropdown'];
                } 
                unset($data['c_stateDropdown']);
                //unset($data['c_stateText']);
                if (!isset($_SESSION[$this->bID . 'enrollment']['StepCompleted']) || empty($_SESSION[$this->bID . 'enrollment']['StepCompleted']) || $_SESSION[$this->bID . 'enrollment']['StepCompleted'] < 2) {
                    $_SESSION[$this->bID . 'enrollment']['StepCompleted'] = 2;
                }
                $_SESSION['message'] = 'ContactFormSaved';
                $_SESSION[$this->bID . 'enrollment'] = array_merge($_SESSION[$this->bID . 'enrollment'], $data);
                $this->redirect($page->getCollectionPath() . '/payment/' . $this->bID);
            } else {
                $this->set('error_form', true);
                $this->set('error_messages', $error->getList());
                $this->action_contact();
            }
        } else {
            $this->action_contact();
        }
    }

    public function action_submit_payment() 
    {
        setTimeLimitsInfinite();
        $prev_data = $_SESSION[$this->bID . 'enrollment'];
        $donor = new DonorPerfect();
        $page = Page::getCurrentPage();
        $main_amount = $this->getCurrentPrice($prev_data);
        $shipping_amount = $this->calculateShippingPrice($prev_data);
        $amount =  $main_amount + $shipping_amount;
        $soft_gift_id = 0;

        if ($this->isPost()) {
            $data = $this->request->request->all();
	    //SR updates: 02/23 - issue with the card language
	    unset($data['e_language']);
            $error = $this->validate_payment($data);

            if (!$error->has()) {
                $order_id = uniqid(time()); // Set Unique order ID
                $r = $this->transaction($order_id, $prev_data, $data, $amount);
		//logic : that checks restricts duplicate transaction ID : 
                if ($r['response'] == 1) {
                    $data['authcode'] = $auth_code = $r['authcode'];
                    $data['transactionid'] = $transactionid = $r['transactionid'];
                    // Search Donor
                    $donor_id = $this->searchDonor($donor, $prev_data,$this->bLanguage,1);
		    //If donor exists save the address and altphone4 in address section
		    
                    if (isset($donor_id->donor_id)) {
                        $donor_id = $donor_id->donor_id;
			//Get Donor Details
			$donor_details = $donor->getDonorDetails($donor_id);
			
			//Set Additional Details of Donor
			$prev_data = $this->setDonorAdditonalDetails($prev_data,$donor_details);
						
                        //Get Donor Address - SR testing
                        $donorAddressExists = $this->getDonorAddress($donor_id,$donor);
			//Checks if the Address exists.
                        if(isset($donorAddressExists) && isset($donorAddressExists->address)) {
			    //check if title field added by user is empty - set title value in Existing donor data.
			    if(isset($prev_data['c_title']) && empty($prev_data['c_title'])) {
			    	$prev_data['c_title'] = $donorAddressExists->title;
			    }
			    //Get Contact of the Existing Donor;
                            $donorExistingContact = $this->getDonorContact($donor_id,$donor);
                            //Save Donor Address address section
                            $success_id = $this->saveDonorAddress($donor_id, $donor, $donorAddressExists,$donorExistingContact);
                            //If altphone 4 exists then get the Alt phone 4 and add it in address section
                            if(isset($donorExistingContact->altphone4)){
                                    $this->saveUDFValues($donor,$success_id,'ADDALTPHONE4',$donorExistingContact->altphone4);
                            }    
                        }			
                    } else {
                        $donor_id = 0;
                    }
                    // If Not Found - Create new Donor or Update the previous one
                    $donor_id_new = $this->saveDonor($donor_id, $prev_data, $donor);
                    if ($donor_id_new ==0 && $donor_id >0) {
                        $data['donor_id'] = $donor_id;
                    } else {
                        $data['donor_id'] = $donor_id = $donor_id_new;
                    }
		    
		    //save state in Text in address 3
		    if(isset($prev_data['c_stateText'])) {
		    	$this->saveUDFValues($donor,$donor_id,'ADDRESS3',$prev_data['c_stateText']);	
		    }
		    
                    // Update alt phone number
                    if(isset($prev_data['c_home_phone']) && !empty($prev_data['c_home_phone'])) {
                        $this->saveUDFValues($donor,$donor_id,'ALTPHONE4',$prev_data['c_home_phone']);
                    }
                    // Update Name Group
                    $this->updateNameGroup($donor_id, $donor, $prev_data);

                    // Update Source to Website
                    $this->updateSource($donor_id, $donor);
                    // Create new Gift
                    $data['gift_id'] = $gift_id = $this->saveGift($donor_id, $transactionid, $amount, $prev_data, $donor);
                    // If for other user - Soft Credit
                    $soft_donor_id = 0;
		    $FlagExists = 0;
                    if($prev_data['dChkSendNotification'] && $prev_data['e_email']!=$prev_data['c_email']) {
                        if (!empty($prev_data['e_email'])) {
                            $soft_donor_id = $this->searchDonor($donor, $prev_data, $prev_data['e_notification_language']);
			} else if (empty($prev_data['e_email'])) {
			    //Check when email field is empty in reciever's details.
                            $ExistingDonors = $this->searchDonor($donor, $prev_data, $prev_data['e_notification_language']);
			    if($ExistingDonors != NULL && !is_array($ExistingDonors)) {
                                $FlagExists = 1;					
                            }				
                        }
                        if (isset($soft_donor_id->donor_id)) {
                            $soft_donor_id = $soft_donor_id->donor_id;
			    
			    //Get Donor Details
			    $soft_donor_details = $donor->getDonorDetails($soft_donor_id);
			    
			    //Add additional details of the donor.
			    $prev_data = $this->setDonorAdditonalDetails($prev_data,$soft_donor_details);
				
                            $RecieverAddressExists = $this->getDonorAddress($soft_donor_id,$donor);
                            if(isset($RecieverAddressExists) && isset($RecieverAddressExists->address)) {			
				
				//Check if title of soft_donor added by the user exists otherwise save the existing one
				if(isset($prev_data['e_title']) && empty($prev_data['e_title'])) {
			    		$prev_data['e_title'] = $RecieverAddressExists->title;
			    	}
                                $success = $this->saveDonorAddress($soft_donor_id, $donor, $RecieverAddressExists,NULL,0);
                            }
                        } else if ($FlagExists == 1) {
                            $soft_donor_id = $ExistingDonors->donor_id;
			    
			    //Get Donor Details
			    $soft_donor_details = $donor->getDonorDetails($soft_donor_id);
			    
			    //Add additional details of the donor.
			    $prev_data = $this->setDonorAdditonalDetails($prev_data,$soft_donor_details);
			    
                            $RecieverAddressExists = $this->getDonorAddress($soft_donor_id,$donor);
                            if(isset($RecieverAddressExists) && isset($RecieverAddressExists->address)) {
			    	//Check if title of soft_donor added by the user exists otherwise save the existing one
				if(isset($prev_data['e_title']) && empty($prev_data['e_title'])) {
			    		$prev_data['e_title'] = $RecieverAddressExists->title;
			    	}
                                $success = $this->saveDonorAddress($soft_donor_id, $donor, $RecieverAddressExists,NULL,0);
				
				if(isset($RecieverAddressExists->altphone4)){
                                    $this->saveUDFValues($donor,$success,'ADDALTPHONE4',$RecieverAddressExists->altphone4);
                            	}
                            }
                        } else {
                        	$soft_donor_id = 0;
				$prev_data['d_middle_name'] = '';
				$prev_data['d_suffix'] = '';
				$prev_data['d_title'] = '';
				$prev_data['d_salutation'] = '';
				$prev_data['d_prof_title'] = '';
				$prev_data['d_opt_line'] = '';
				$prev_data['d_address_type'] = '';
				$prev_data['d_home_phone'] = '';
				$prev_data['d_business_phone'] = '';
				$prev_data['d_fax_phone'] = '';
				$prev_data['d_mobile_phone'] = '';
				$prev_data['d_org_rec'] = '';
				$prev_data['d_donor_type'] = '';
				$prev_data['d_nomail'] = '';
				$prev_data['d_narrative'] = '';
                    	}
                        $soft_donor_id_new = $this->saveDonor($soft_donor_id, [
                            'c_first_name' => $prev_data['e_first_name'],
                            'c_last_name' => $prev_data['e_last_name'],
                            'c_title' => $prev_data['e_title'],
                            'c_address' => $prev_data['e_address'],
                            'c_address2' => $prev_data['e_address2'],
			    'c_stateText' => $prev_data['e_stateText'],
                            'c_city' => $prev_data['e_city'],
                            'c_state' => $prev_data['e_state'],
                            'c_zip' => $prev_data['e_zip'],
                            'c_country' => $prev_data['e_country'],
                            'c_home_phone' => $prev_data['e_home_phone'],
                            'c_cell_phone' => $prev_data['e_cell_phone'],
                            'c_email' => (empty($prev_data['e_email']) ? NULL : $prev_data['e_email']),
			    'd_middle_name'=>	$prev_data['d_middle_name'],
			    'd_suffix'=> $prev_data['d_suffix'],
			    'd_title' => $prev_data['d_title'],
			    'd_salutation' => $prev_data['d_salutation'],
			    'd_prof_title' => $prev_data['d_prof_title'],
			    'd_opt_line' => $prev_data['d_opt_line'],
			    'd_address_type' => $prev_data['d_address_type'],
			    'd_home_phone' => $prev_data['d_home_phone'],
			    'd_business_phone' => $prev_data['d_business_phone'],
			    'd_fax_phone' => $prev_data['d_fax_phone'],
			    'd_mobile_phone' => $prev_data['d_mobile_phone'],
			    'd_org_rec' => $prev_data['d_org_rec'],
			    'd_donor_type' => $prev_data['d_donor_type'],
			    'd_nomail' => $prev_data['d_nomail'],
			    'd_narrative' => $prev_data['d_narrative']	    
                        ], $donor);
                        if ($soft_donor_id_new ==0 && $soft_donor_id >0) {
                            $soft_donor_id;
                        } else {
                            $soft_donor_id = $soft_donor_id_new;
                        }
                        $soft_gift_id = $this->saveSoftGift($donor_id, $soft_donor_id, $amount, $gift_id, $donor);
			//save state in Text in address 3
			if(isset($prev_data['e_stateText'])) {		
				$this->saveUDFValues($donor,$soft_donor_id,'ADDRESS3',$prev_data['e_stateText']);	
			}
			// Update Name Group
                    	$this->updateNotifieeNameGroup($soft_donor_id, $donor, $prev_data);
			
			// Update Source to Website
                    	$this->updateSource($soft_donor_id, $donor);
                    }
                    $data['soft_donor_id'] = $soft_donor_id;

                    // Set if Internation Shipping
                    if ($this->calculateShippingPrice($prev_data) > 0) {
                        $this->internationalShipping($gift_id, $donor, $soft_gift_id);
                    }

                    // Update Image for the MML Image - Config Value MMLImage
                    if($prev_data['card']){
                        $img_data = $this->getImageData($prev_data['card']);
                        $this->setGiftUDF($gift_id, $donor, 'MML_IMAGE', $img_data, 'C', $soft_gift_id);
                    }
                    // Update Cover
                    if($prev_data['folder']){
                        $img_data = $this->getFolderData($prev_data['folder']);
                        $this->setGiftUDF($gift_id, $donor, 'MML_COVER_COLOR', $img_data, 'C', $soft_gift_id);
                    }
                    //NOTIFYELSE
                    if($prev_data['dChkSendNotification']) {
                        $this->setGiftUDF($gift_id, $donor, 'NOTIFYELSE', 'Y', 'C', $soft_gift_id);
                        $this->setGiftUDF($gift_id, $donor, 'TRIBUTEORSC', $prev_data['e_first_name'].' '.$prev_data['e_last_name'], 'C', $soft_gift_id);
                    } else {
                        $this->setGiftUDF($gift_id, $donor, 'NOTIFYELSE', 'N', 'C', $soft_gift_id);
                    }
                    // Occasion
                    if ($prev_data['e_occasion'] || $prev_data['e_occasion'] == 0) {
                        $this->setGiftUDF($gift_id, $donor, 'OCCASION', Config::get('mass_enrollment::custom.MMLOccasions.' . $prev_data['e_occasion']), 'C', $soft_gift_id);
                    }
                    // Set MML Date
                    if (in_array($this->bFormType, [1,2,3,4])) {
                        $this->setMMLDate($gift_id, $donor, (strtotime($prev_data['e_date']) > 0 ? $prev_data['e_date'] : date("m/d/Y")), $soft_gift_id);
                    }
                    // UDF_INTENTION
                    if ($prev_data['e_enrollment_type'] == 'individual') {
                        $INTENTION = $prev_data['e_individual_name'];
                    } else if ($prev_data['e_enrollment_type'] == 'family') {
                        $INTENTION = $prev_data['e_family_name'];
                    } else if ($prev_data['e_intention']) {
                        $INTENTION = $prev_data['e_intention'];
                    }
		    //Form Name
		    $this->setGiftUDF($gift_id, $donor, 'FORM_NAME',translate('Form'.$this->bFormType,$this->bLanguage, false), 'C', $soft_gift_id);
                    //Intention
		    $this->setGiftUDF($gift_id, $donor, 'INTENTION', $INTENTION, 'C', $soft_gift_id);
                    // UDF_REQUESTEDBY
                    $this->setGiftUDF($gift_id, $donor, 'REQUESTEDBY', $prev_data['e_requested_by'], 'C', $soft_gift_id);
                    // UDF_CARDFORMAT
                    if ($this->bFormType == 8 || $this->bFormType == 12) {
                        if($prev_data['dCheckIfYouWantTheCard']) {
                            $this->setGiftUDF($gift_id, $donor, 'CARDFORMAT', Config::get('mass_enrollment::custom.MMLCardType.' . $this->bFormType), 'C', $soft_gift_id);
                        }
                    } else {
                        $this->setGiftUDF($gift_id, $donor, 'CARDFORMAT', Config::get('mass_enrollment::custom.MMLCardType.' . $this->bFormType), 'C', $soft_gift_id);
                    }
                    
                    if ($prev_data['e_language']) {
                        // Card Lang
                        if ($this->bFormType == 8 || $this->bFormType == 12) {
                            if($prev_data['dCheckIfYouWantTheCard']) {
                                $this->setGiftUDF($gift_id, $donor, 'CARDLANG', strtoupper(Config::get('mass_enrollment::custom.CardLang.' . $prev_data['e_language'])), 'C', $soft_gift_id);
                            }
                        } else {
                            $this->setGiftUDF($gift_id, $donor, 'CARDLANG', strtoupper(Config::get('mass_enrollment::custom.CardLang.' . $prev_data['e_language'])), 'C', $soft_gift_id);
                        }
                    }
                    if ($prev_data['e_special_instructions']) {
                        // UDF_ONLINE_INSTRUCTIONS
                        $this->setGiftUDF($gift_id, $donor, 'ONLINE_INSTRUCTIONS', $prev_data['e_special_instructions'], 'C', $soft_gift_id);
                    }
                    if ($prev_data['e_support_donation']) {
                        // DONORS_INTEREST
                        $this->setGiftUDF($gift_id, $donor, 'DONORS_INTEREST', $prev_data['e_support_donation'], 'C', $soft_gift_id);
                    }

                    // Living Deceased - intention_deceased intention_living
                    if ($prev_data['i_living_deceased']=='living') {
                        $this->setGiftUDF($gift_id, $donor, 'INTENTION_LIVING', 'Y', 'C', $soft_gift_id);
                        if ($this->bFormType==3) {
                            $this->setGiftUDF($gift_id, $donor, 'CARDFORMAT', 'MMLACKN', 'C', $soft_gift_id);
                        }
                    } else if ($prev_data['i_living_deceased']=='deceased') {
                        $this->setGiftUDF($gift_id, $donor, 'INTENTION_DECEASED', 'Y', 'C', $soft_gift_id);
                        if ($this->bFormType==3) {
                            $this->setGiftUDF($gift_id, $donor, 'CARDFORMAT', 'MMLACKN_2', 'C', $soft_gift_id);
                        }
                    }
                    // Number of Massess - NUMBER_OF_MASSES
                    if ($prev_data['i_number_masses']) {
                        $this->setGiftUDF($gift_id, $donor, 'NUMBER_OF_MASSES', $prev_data['i_number_masses'], 'C', $soft_gift_id);
                    }

                    if (($data['p_country'] == "US" || $data['p_country'] == "CA")) {
                        $data['p_state'] = $data['p_stateDropdown'];
                    }
                    unset($data['p_stateDropdown']);

                    // Adding Contact details form Contact tabs to Billing Details.
                    if (isset($data['chkSameAsContact'])) {
                        $contact = $_SESSION[$this->bID . 'enrollment'];
                        $data['p_address'] = $contact['c_address'];
                        $data['p_city'] = $contact['c_city'];
                        $data['p_country'] = $contact['c_country'];
                        $data['p_zip'] = $contact['c_zip'];
                    }
                    $data = array_merge($_SESSION[$this->bID . 'enrollment'], $data);
                    unset($data['p_card_holder_name']);
                    unset($data['p_card_type']);
                    unset($data['p_card_number']);
                    unset($data['p_exp_month']);
                    unset($data['p_exp_year']);
                    unset($data['p_cvv']);
                    $data['i_donation_amount'] = $amount;
                    $data['bID'] = $this->bID;
                    $data['cID'] = $this->getModel()->add($data);
                    $data['e_intention'] = $INTENTION;
                    $_SESSION[$this->bID . 'enrollment'] = $data;
                    if (!isset($_SESSION[$this->bID . 'enrollment']['StepCompleted']) || empty($_SESSION[$this->bID . 'enrollment']['StepCompleted']) || $_SESSION[$this->bID . 'enrollment']['StepCompleted'] < 3) {
                        $_SESSION[$this->bID . 'enrollment']['StepCompleted'] = 3;
                    }
                    $this->set('message_custom', translate('MessageSuccess', $this->bLanguage, false));
                    $this->redirect($page->getCollectionPath() . '/receipt/' . $this->bID);
                } else if ($r['response'] != 1 && !empty($r['responsetext'])) {
                    $error = Loader::helper("validation/error");
                    $error->add($r['responsetext']);
                    $this->set('error_form', true);
                    $this->set('error_messages', $error->getList());
                    $this->action_payment();
                } else {
                    $error = Loader::helper("validation/error");
                    $error->add('PaymentFailed');
                    $this->set('error_form', true);
                    $this->set('error_messages', $error->getList());
                    $this->action_payment();
                }
            } else {
                $this->set('error_form', true);
                $this->set('error_messages', $error->getList());
                $this->action_payment();
            }
        } else {
            $this->action_payment();
        }
    }
    
    public function getImageData($img_id, $translating_lang = 0 ,$flag_name = false) 
    {
        $db = Database::connection();
        $key = $db->GetOne('SELECT translation_key FROM ctr_card_images where iID=' . $img_id);
        if ($key) {
            if ($flag_name) {
	    	if(empty($translating_lang)) {
			$translating_lang = $this->bLanguage;
		}
                return translate($key, $translating_lang, false);
            } else {
                return Config::get('mass_enrollment::custom.MMLImage.' . $key);
            }
        } else {
            return '';
        }
    }

    public function getFolderData($img_id, $translating_lang = 0 ,$flag_name = false) 
    {
        $db = Database::connection();
        $key = $db->GetOne('SELECT translation_key FROM ctr_folder where fID=' . $img_id);
        if ($key) {
            if ($flag_name) {
	    	if(empty($translating_lang)) {
			$translating_lang = $this->bLanguage;
		}
                return translate($key, $translating_lang, false);
            } else {
                return Config::get('mass_enrollment::custom.MMLCoverColor.' . $key);
            }
        } else {
            return '';
        }
    }

    public function transaction($order_id, $prev_data, $data, $amount) 
    {
        $name = explode(" ", $data['p_card_holder_name']);
	$payment_token = $data['payment_token'];
        // Safe Save Payment Gateway
        $gw = new SafeSave;
        $gw->setLogin();
        if ($data['chkSameAsContact']) {
            $gw->setBilling($prev_data['c_first_name'], $prev_data['c_last_name'], "", $prev_data['c_address'], $prev_data['c_address2'], $prev_data['c_city'], $prev_data['c_state'], $prev_data['c_zip'], $prev_data['c_country'], "", "", $prev_data['c_email'], "");
        } else {
            $gw->setBilling($name[0], $name[1], "", $data['p_address'], $data['p_address2'], $data['p_city'], $data['p_stateText'], $data['p_zip'], $data['p_country'], "", "", $data['p_email'], "");
        }
        if ($prev_data['dChkSendNotification']) {
            $gw->setShipping($prev_data['e_first_name'], $prev_data['e_last_name'], "", $prev_data['e_address'], $prev_data['e_address2'], $prev_data['e_city'], $prev_data['e_state'], $prev_data['e_zip'], $prev_data['e_country'], $prev_data['e_email']);
        } else {
            $gw->setShipping($prev_data['c_first_name'], $prev_data['c_last_name'], "", $prev_data['c_address'], $prev_data['c_address2'], $prev_data['c_city'], $prev_data['c_state'], $prev_data['c_zip'], $prev_data['c_country'], $prev_data['c_email']);
        }
        $gw->setOrder($order_id, $prev_data['e_enrollment_type'], 0, 0, "", $_SERVER['REMOTE_ADDR']);
        return $gw->doTokenPayment($amount, $payment_token);
    }

    public function updateNameGroup($donor_id, $donor, $prev_data) 
    {
        $ng = '';
	$ng = $donor->checkNameGroup($donor_id);
        switch ($this->bLanguage) {
            case 'vi':
	    	$location = $this->updateLocation($donor, $donor_id, 'EPWORTH');
                if (stripos('EPWORTH', $location)===false || empty($ng)) {
                    $ng = 'C_VIETNAM';
                }
                break;
            case 'en':
	    	$location = $this->updateLocation($donor, $donor_id, 'TECHNY');
                if (stripos('TECHNY', $location)===false || empty($ng)) {
                    $ng = 'P_ENGLISH';
                }
                break;
            case 'sp':
	    	$location = $this->updateLocation($donor, $donor_id, 'TECHNY');
                if (stripos('TECHNY', $location)===false || empty($ng)) {
                    $ng = 'P_SPANISH';
                }
                break;
            case 'pl':
	    	$location = $this->updateLocation($donor, $donor_id, 'TECHNY');
                if (stripos('TECHNY', $location)===false || empty($ng)) {
                    $ng = 'P_POLISH';
                }
                break;
            case 'pt':
	    	$location = $this->updateLocation($donor, $donor_id, 'TECHNY');
                if (stripos('TECHNY', $location)===false || empty($ng)) {
                    $ng = 'P_PORTUG';
                }
                break;
        }
        if ($ng!='') {
            return $donor->saveUdfXml([
                    'matching_id' => $donor_id,
                    'field_name' => 'NAME_GROUP',
                    'data_type' => 'C',
                    'char_value' => $ng,
                    'date_value' => null,
                    'number_value' => null
        ]);
        } else {
            return $donor_id;
        }
    }
    
    public function updateNotifieeNameGroup($donor_id, $donor, $prev_data)
    {
    	$ng = '';
	$ng = $donor->checkNameGroup($donor_id);
        switch ($prev_data['e_notification_language']) {
            case 'vi':
	    	$location = $this->updateLocation($donor, $donor_id, 'EPWORTH'); 
                if (stripos('EPWORTH', $location)===false || empty($ng)) {
                    $ng = 'C_VIETNAM';
                }
                break;
            case 'en':
	    	if($this->bLanguage == 'vi') {
			$location = $this->updateLocation($donor, $donor_id, 'EPWORTH');
			if(stripos('EPWORTH', $location) === false || empty($ng)) {
				$ng = 'C_ENGLISH';
			}
		} else {
			$location = $this->updateLocation($donor, $donor_id, 'TECHNY');
			if(stripos('EPWORTH', $location) === false || empty($ng)) {
				$ng = 'P_ENGLISH';
			}
		}
                break;
            case 'sp':
	    	$location = $this->updateLocation($donor, $donor_id, 'TECHNY');
                if (stripos('TECHNY', $location)===false || empty($ng)) {
                    $ng = 'P_SPANISH';
                }
                break;
            case 'pl':
	    	$location = $this->updateLocation($donor, $donor_id, 'TECHNY');
                if (stripos('TECHNY', $location)===false || empty($ng)) {
                    $ng = 'P_POLISH';
                }
                break;
            case 'pt':
	    	$location = $this->updateLocation($donor, $donor_id, 'TECHNY');
                if (stripos('TECHNY', $location)===false || empty($ng)) {
                    $ng = 'P_PORTUG';
                }
                break;
        }
        if ($ng!='') {
            return $donor->saveUdfXml([
                    'matching_id' => $donor_id,
                    'field_name' => 'NAME_GROUP',
                    'data_type' => 'C',
                    'char_value' => $ng,
                    'date_value' => null,
                    'number_value' => null
        ]);
        } else {
            return $donor_id;
        }
    }
    
    public function updateLocation($donor, $donor_id, $location_code)
    {
    	$location = $donor->checkLocation($donor_id);
	if(empty($location)) {
		$donor_id = $donor->saveUdfXml([
                		'matching_id' => $donor_id,
                    		'field_name' => 'LOCATION',
                    		'data_type' => 'C',
                    		'char_value' => $location_code,
                    		'date_value' => null,
                    		'number_value' => null
				]);
		$location = $donor->checkLocation($donor_id);
	}
	return $location;
    }

    public function setGiftUDF($gift_id, $donor, $name, $val, $type, $soft_gift_id,$f=1) 
    {
    	if($f==2) {
		if ($soft_gift_id > 0) {
            $donor->saveUdfXml([
                'matching_id' => $soft_gift_id,
                'field_name' => $name,
                'data_type' => $type,
                'char_value' => ($type == 'C' ? $val : NULL),
                'date_value' => ($type == 'D' ? $val : NULL),
                'number_value' => ($type == 'N' ? $val : NULL)
            ]);
        }
        return $donor->saveUdfXml([
                    'matching_id' => $gift_id,
                    'field_name' => $name,
                    'data_type' => $type,
                    'char_value' => ($type == 'C' ? $val : NULL),
                    'date_value' => ($type == 'D' ? $val : NULL),
                    'number_value' => ($type == 'N' ? $val : NULL)
        ]);
	}
        if ($soft_gift_id > 0) {
            $donor->saveUdfXml([
                'matching_id' => $soft_gift_id,
                'field_name' => $name,
                'data_type' => $type,
                'char_value' => ($type == 'C' ? $val : NULL),
                'date_value' => ($type == 'D' ? $val : NULL),
                'number_value' => ($type == 'N' ? $val : NULL)
            ]);
        }
        return $donor->saveUdfXml([
                    'matching_id' => $gift_id,
                    'field_name' => $name,
                    'data_type' => $type,
                    'char_value' => ($type == 'C' ? $val : NULL),
                    'date_value' => ($type == 'D' ? $val : NULL),
                    'number_value' => ($type == 'N' ? $val : NULL)
        ]);
    }

    public function setMMLDate($gift_id, $donor, $date, $soft_gift_id) 
    {
        if ($soft_gift_id > 0) {
            $donor->saveUdfXml([
                'matching_id' => $soft_gift_id,
                'field_name' => 'MMLDATE',
                'data_type' => 'D',
                'char_value' => NULL,
                'date_value' => date("m/d/Y", strtotime($date)),
                'number_value' => NULL
            ]);
        }
        return $donor->saveUdfXml([
                    'matching_id' => $gift_id,
                    'field_name' => 'MMLDATE',
                    'data_type' => 'D',
                    'char_value' => NULL,
                    'date_value' => date("m/d/Y", strtotime($date)),
                    'number_value' => NULL
        ]);
    }

    public function saveGift($donor_id, $transactionid, $amount, $prev_data, $donor) 
    {
        return $donor->saveGift([
                    'donor_id' => $donor_id,
                    'record_type' => 'G',
                    'gift_date' => date("m/d/Y"),
                    'amount' => $amount,
                    'gl_code' => Config::get('mass_enrollment::custom.FormTypeGLCode.' . $this->bLanguage . '.' . $this->bFormType),
                    'reference' => $transactionid,
                    'glink' => NULL,
                    'plink' => NULL,
                    'nocalc' => 'N',
                    'receipt' => 'N',
                    'old_amount' => 0,
                    'gift_aid_date' => date("m/d/Y"),
                    'gift_aid_amt' => 0,
                    'gift_aid_eligible_g' => NULL,
                    'receipt_delivery_g' => 'N',
		    'gift_type' => 'ON'
        ]);
    }


    public function saveSoftGift($donor_id, $soft_donor_id, $amount, $gift_id, $donor) 
    {
        return $donor->saveGift([
                    'donor_id' => $soft_donor_id,
                    'record_type' => 'S',
                    'gift_date' => date("m/d/Y"),
                    'amount' => $amount,
                    'gl_code' => Config::get('mass_enrollment::custom.FormTypeGLCode.' . $this->bLanguage . '.' . $this->bFormType),
                    'reference' => NULL,
                    'glink' => $gift_id,
                    'plink' => NULL,
                    'nocalc' => 'N',
                    'receipt' => 'N',
                    'old_amount' => 0,
                    'gift_aid_date' => date("m/d/Y"),
                    'gift_aid_amt' => 0,
                    'gift_aid_eligible_g' => NULL,
                    'receipt_delivery_g' => 'N',
		    'gift_type' => 'SC'
        ]);
    }

    public function saveDonor($donor_id, $prev_data, $donor) 
    {
    	
        return $donor->saveDonor([
                    'donor_id' => $donor_id,
                    'first_name' => $prev_data['c_first_name'],
                    'last_name' => $prev_data['c_last_name'],
                    'middle_name' => $prev_data['d_middle_name'],
                    'suffix' => $prev_data['d_suffix'],
                    'title' => $prev_data['c_title'],
                    'salutation' => $prev_data['d_salutation'],
                    'prof_title' => $prev_data['d_prof_title'],
                    'opt_line' => $prev_data['d_opt_line'],
                    'address' => $prev_data['c_address'],
                    'address2' => $prev_data['c_address2'],
		    'address3' => $prev_data['c_stateText'],
                    'city' => $prev_data['c_city'],
                    'state' => $prev_data['c_state'],
                    'zip' => $prev_data['c_zip'],
                    'country' => $prev_data['c_country'],
                    'address_type' => $prev_data['d_opt_line'],
		    'home_phone' => $prev_data['d_home_phone'],
                    'business_phone' => $prev_data['d_business_phone'],
                    'fax_phone' => $prev_data['d_fax_phone'],
                    'mobile_phone' => $prev_data['d_mobile_phone'],
                    'email' => $prev_data['c_email'],
                    'org_rec' => $prev_data['d_org_rec'],
                    'donor_type' => 'IN',
                    'nomail' => 'N',
                    'nomail_reason' => $prev_data['d_nomail_reason'],
                    'narrative' => $prev_data['d_narrative'],
        ]);
    }
    
    
    //Get Donor Address 
    public function getDonorAddress($donor_id, $donor)
    {
    	return $donor->donorSearch([
			'donor_id' => $donor_id,
			'last_name' => NULL,
			'first_name' => NULL,
			'opt_line' => NULL,
			'address' => NULL,
			'city' => NULL,
			'state' => NULL,
			'zip' => NULL,
			'country' => NULL,
			'filter_id' => NULL,
			'user_id' => NULL,
	]);
    }
    
    public function getDonorContact($donor_id,$donor)
    {
    	return $donor->getDonorContact($donor_id);
    }
    
    //Check Address exists
    public function CheckDonorWithDetails($donor,$donor_id = NULL)
    {
    	return $donor->donorSearch([
			'donor_id' => $donor_id,
			'last_name' => $_SESSION[$this->bID.'enrollment']['e_last_name'],
			'first_name' => $_SESSION[$this->bID.'enrollment']['e_first_name'],
			'opt_line' => NULL,
			'address' => NULL,
			'city' => $_SESSION[$this->bID.'enrollment']['e_city'],
			'state' => $_SESSION[$this->bID.'enrollment']['e_state'],
			'zip' => $_SESSION[$this->bID.'enrollment']['e_zip'],
			'country' => NULL,
			'filter_id' => NULL,
			'user_id' => NULL,
	]);
    }
    
    //Save Donor's Multiple Address
    public function saveDonorAddress($donor_id, $donor, $donorExists,$donorExistingContact = NULL,$isDonor = 1)
    {
	$home_phone = NULL;
	$business_phone = NULL;
	$mobile_phone = NULL;
	$address_obj = $donor->getExistingDonorAddress3($donor_id);
	$donorExists->address3 = $address_obj->address3;
    	if($isDonor) {
		$home_phone = $_SESSION[$this->bID.'enrollment']['c_home_phone'];
		$business_phone = $donorExistingContact->business_phone;
		$mobile_phone = $donorExistingContact->mobile_phone;
	}
    	return $donor->saveDonorAddress([
			'address_id' => '0',
			'donor_id' => $donor_id,
			'opt_line' => NULL,
			'address' => $donorExists->address,
			'address2' => $donorExists->address2,
			'city' =>$donorExists->city,
			'state' => $donorExists->state,
			'zip' => $donorExists->zip,
			'address_type' => 'OLDADD',			
			'getmail' => 'N',
			'user_id' => 'API',
			'title' => $donorExists->title,
			'first_name' => $donorExists->first_name,
			'middle_name' => NULL,
			'last_name' =>$donorExists->last_name,
			'suffix' => $donorExists->suffix,
			'prof_title' => NULL,
			'salutation' => NULL,
			'seasonal_from_date' => NULL,
			'seasonal_to_date' => NULL,
			'email' => NULL,
			'home_phone' => NULL,
			'business_phone' => NULL,
			'fax_phone' => NULL,
			'mobile_phone' => NULL,
			'address3' => $donorExists->address3,
			'address4' => NULL,
			'ukcountry' => NULL,
			'org_rec' => NULL
	]);
    }
    
    //Save Additional Field values
    public function saveUDFValues($donor, $donor_id, $field_name,$value)
    {
	return $donor->saveUdfXml([
		'matching_id' => $donor_id,
		'field_name' => $field_name,
		'data_type' => 'C',
		'char_value' => $value,
		'date_value' => NULL,
		'number_value' => NULL,
		'user_id' => NULL
		]);
	
    }
    
    public function updateSource($donor_id, $donor) 
    {
        return $donor->saveUdfXml([
                    'matching_id' => $donor_id,
                    'field_name' => 'SOURCE',
                    'data_type' => 'C',
                    'char_value' => 'WEBSITE',
                    'date_value' => NULL,
                    'number_value' => NULL
        ]);
    }

    public function internationalShipping($gift_id, $donor, $soft_gift_id) {
        if ($soft_gift_id > 0) {
            $donor->saveUdfXml([
                'matching_id' => $soft_gift_id,
                'field_name' => 'INTERNATIONAL_SHIPP',
                'data_type' => 'C',
                'char_value' => 'Y',
                'date_value' => NULL,
                'number_value' => NULL
    ]);
        }

        return $donor->saveUdfXml([
                    'matching_id' => $gift_id,
                    'field_name' => 'INTERNATIONAL_SHIPP',
                    'data_type' => 'C',
                    'char_value' => 'Y',
                    'date_value' => NULL,
                    'number_value' => NULL
        ]);
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
        $required = $this->getRulesOnFormType($data);
        // Add required Fields
        foreach ($required as $key => $value) {
            $val->addRequired($key, $value);
        }
        $val->setData($data);
        $val->test();
        $e = $val->getError();

        //validate when send notification checkbox is checked.
        // && !filter_var($data['e_email'], FILTER_VALIDATE_EMAIL)
        if ($data['dChkSendNotification'] == 'checked') {
	    if($this->bFormType == 2 && (!isset($data['e_email']) || empty($data['e_email']))) {
	    	$e->add('EmailAddressRequrired');
	    }
            if (isset($data['e_email']) && !empty($data['e_email']) && !$this->email_validation($data['e_email'])) {    
                $e->add('EmailAddressInvalid');
            }
            //adding validation for dropdown and textbox depending the country chosen (USA)
            if (isset($data['e_country']) && isset($data['e_stateDropdown']) && $data['e_stateDropdown'] == "" && ($data['e_country'] == "US" ||  $data['e_country'] == "CA")) {
                $e->add('AtleastoneStateProvinceshouldbeselected');
            }
        }

        //adding validation for dropdown and textbox depending the country chosen (USA)
        if (isset($data['e_support_donation']) && $data['e_support_donation'] == '') {
            //$e->add('SupportWithDonationRequired');
        }
        return $e;
    }

    /**
     * Valdiating to email 
     * 
     * @return type
     * @author GJ 20/11/2019
     */
    public function email_validation($str) { 
        //Allow unicode characters - ^[]+(\.)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^
	return (!preg_match( 
    "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $str)) 
            ? FALSE : TRUE; 
    }

    public function getRulesOnFormType($data)
    {
        return getRules($this->bFormType, $data);
    }

    /**
     * Function to validate the contact form
     * 
     * @return type
     * @author SR 24/09/2019
     */
    protected function validate_contact($data = array()) {

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
        if (isset($data['c_country']) && ($data['c_country'] == "US" || $data['c_country'] == "CA")) {
            $val->addRequired('c_stateDropdown', 'AtleastoneStateProvinceshouldbeselected');
        } 
        $val->setData($data);
        $val->test();
        $e = $val->getError();
        //validate email
        if (!isset($data['chkSameAsContact']) && isset($data['c_email']) && !empty($data['c_email']) && 
            !$this->email_validation($data['c_email'])) {    
            $e->add('EmailAddressInvalid');
        }
        // Donor and receiver email should be different 	
        if (isset($_SESSION[$this->bID . 'enrollment']['e_email']) && ($_SESSION[$this->bID . 'enrollment']['e_email'] == $data['c_email'])) {
           $e->add('MatchDonorReceiverEmailID');   
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
        ];
        //if Same as contact checkbox is checked then add the validation for Billing Details
        if (!isset($data['chkSameAsContact'])) {
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
        if (!isset($data['chkSameAsContact']) && isset($data['p_country']) && ($data['p_country'] == "US" || $data['p_country'] == "CA")) {
            $val->addRequired('p_stateDropdown', 'AtleastoneStateProvinceshouldbeselected');
        } 
        //adding integer validation to CVV
        if (isset($data['p_cvv']) && !empty($data['p_cvv'])) {
            $val->addInteger('p_cvv', 'CVVInteger');
        }

        //adding integer validation to zip
        if (isset($data['p_zip']) && !empty($data['p_zip'])) {
            $val->addInteger('p_zip', 'ZipInteger');
        }

        $val->setData($data);
        $val->test();
        $e = $val->getError();

        //validate email
        if (!isset($data['chkSameAsContact']) && isset($data['p_email']) && !empty($data['p_email']) && 
            !$this->email_validation($data['p_email'])) {    
            $e->add('EmailAddressInvalid');
        }

        if (isset($data['p_card_number']) && !empty($data['p_card_number']) && !is_numeric($data['p_card_number'])) {
            $e->add('Cardnumbermustcontainonlydigits');
        }
	
	if (!empty($data['p_exp_month']) && !empty($data['p_exp_year']) && strtotime($data['p_exp_month']."/01/".$data['p_exp_year']) < time()) {
            $e->add('Cardhasexpired');
        }

        return $e;
    }

    public function ifsessionExists($session) {
        // check if session exists?
        if (!empty($session)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Function called when the block is added
     * 
     * @author SR 09/26/2019
     */
    public function add() {
        $this->setupFormBlock();
    }

    /**
     * Function called when the block is edited
     * 
     * @author SR 09/26/2019
     */
    public function edit() {
        $this->setupFormBlock();
    }

    function getTransDD($array) {
        foreach ($array as $key => $lang) {
            $array[$key] = translate(str_replace(' ', '', $lang), $this->bLanguage, false);
        }
        return $array;
    }

    function getCurrentPrice($prev_data) {
        $amount = 0;
        if ($prev_data['e_enrollment_type'] == 'individual') {
            $type = 'I';
        } else {
            $type = 'F';
        }
        switch ($this->bFormType) {
            case 1:
                $amount = Config::get('mass_enrollment::custom.Prices.' . $type . $this->bFormType);
                break;
            case 2:
                $amount = Config::get('mass_enrollment::custom.Prices.' . $type . $this->bFormType);
                break;
            case 3:
                $amount = Config::get('mass_enrollment::custom.Prices.' . $type . $this->bFormType);
                break;
            case 4:
                $amount = Config::get('mass_enrollment::custom.Prices.' . $type . $this->bFormType);
                break;
            case 5:
                $amount = $prev_data['i_number_masses'] * Config::get('mass_enrollment::custom.Prices.' . $this->bFormType);
                break;
            case 6:
                $amount = Config::get('mass_enrollment::custom.Prices.' . $this->bFormType);
                break;
	    case 13:
                $amount = $prev_data['i_number_masses'] * Config::get('mass_enrollment::custom.Prices.' . $this->bFormType);
                break;				
            default:
                $amount = $prev_data['i_donation_amount'];
                break;
        }
        return $amount;
    }

    /**
     * Function to calculate shipping price
     */
    function calculateShippingPrice($prev_data) {
        $international_Shipp = false;
        if (!in_array($this->bFormType, [1])) {
            return 0;
        } else {
            if ($prev_data['dChkSendNotification']) {
                if ($prev_data['e_country'] != 'US') {
                    $international_Shipp = true;
                }
            } else {
                if ($prev_data['c_country'] != 'US') {
                    $international_Shipp = true;
                }
            }
        }
        if ($international_Shipp) {
            return 10;
        } else {
            return 0;
        }
    }


    function action_cancel()
    {
        $_SESSION[$this->bID . 'enrollment'] = array();
        $page = Page::getCurrentPage();
        $this->redirect($page->getCollectionPath());
    }
    
    //Function to search donor with a logic
    function searchDonor($donor, $prev_data,$language,$isDonor = 0)
    {
    	$donor_id = 0;
	//check if its for searching donor or soft credit.
	if($isDonor == 1) {
		$donor_id = $donor->searchDonor($prev_data['c_email']);
	} else {
		if(!empty($prev_data['e_email'])) {
			$donor_id = $donor->searchDonor($prev_data['e_email']);
		}
	}
	//check if donor_id exists for emails.
	if(empty($donor_id)) {
		$donor_id =  $donor->donorSearch([
			'donor_id' => NULL,
			'last_name' => $isDonor ? $prev_data['c_last_name'] : $prev_data['e_last_name'],
			'first_name' => $isDonor ? $prev_data['c_first_name'] : $prev_data['e_first_name'],
			'opt_line' => NULL,
			'address' => $isDonor ? $prev_data['c_address'] : $prev_data['e_address'],
			'city' => $isDonor ? $prev_data['c_city'] : $prev_data['e_city'],
			'state' => $isDonor ? (!empty($prev_data['c_stateText']) ? $prev_data['c_stateText'] : $prev_data['c_state'])  : (!empty($prev_data['e_stateText']) ? $prev_data['e_stateText'] : $prev_data['e_state']),
			'zip' => $isDonor ? $prev_data['c_zip'] : $prev_data['e_zip'],
			'country' => $isDonor ? $prev_data['c_country'] : $prev_data['e_country'],
			'filter_id' => NULL,
			'user_id' => NULL,
		]);
		//checks if donor value is an object otherwise for no donors and for multiple donors an array is returned.
		if(!is_object($donor_id) && !empty($donor_id)) {
			$json_encode = json_encode($donor_id);
			$new_array = json_decode($json_encode, true);
			$unique_array = array_unique(array_column($new_array,donor_id));
			if(sizeof($unique_array) > 1) {
			   $donor_id = 0;
			} else {
			   foreach($donor_id as $key => $value) {
			   	if($value->donor_id == $unique_array[0]) {
				   $donor_id = $donor_id[$key];
				   break;
				}
			   }
			   $donor_id = $this->checkLocationForDonor($donor,$donor_id,$language);
			   
			}

		} else if(is_object($donor_id)) {
			$donor_id = $this->checkLocationForDonor($donor,$donor_id,$language);
		} else {
			$donor_id = 0;
		}
	} else if (!is_object($donor_id) && !empty($donor_id)){
		$donor_id = $donor_id[0];
		$donor_id = $this->checkLocationForDonor($donor,$donor_id,$language);
	}
	else {
		$donor_id = $this->checkLocationForDonor($donor,$donor_id,$language);
		
	}
	return $donor_id;
    }
    
    function checkLocationForDonor($donor,$donor_id,$language)
	{
		$isSameLocation = 0;
		//the returned value of donor is an object.
		$location = $donor->checkLocation($donor_id->donor_id);
		switch ($language) {
		case 'vi':
	        	if (stripos('EPWORTH', $location)=== 0) {
	           	    $isSameLocation = 1;
	        	}
	        	break;
		case 'en':
	        	if (stripos('TECHNY', $location)=== 0) {
	            	    $isSameLocation = 1;
	        	}
	       		break;
		case 'sp':
	        	if (stripos('TECHNY', $location)=== 0) {
	            	    $isSameLocation = 1;
	        	}
	        break;
		case 'pl':
	        	if (stripos('TECHNY', $location)=== 0) {
	            		$isSameLocation = 1;
	        	}
	        	break;
		case 'pt':
	        	if (stripos('TECHNY', $location)=== 0) {
	            		$isSameLocation = 1;
	        	}
	        	break;
		}
		if($isSameLocation == 0) {
			$donor_id = 0;
		}
		
		return $donor_id;
	}
	
    //Function to set the Additional details collected from Donor prefect Data array
    public function setDonorAdditonalDetails($prev_data, $donor_details)
    {
    	$prev_data['d_middle_name'] = $donor_details->middle_name;
	$prev_data['d_suffix'] = $donor_details->suffix;
	$prev_data['d_title'] = $donor_details->title;
	$prev_data['d_salutation'] = $donor_details->salutation;
	$prev_data['d_prof_title'] = $donor_details->prof_title;
	$prev_data['d_opt_line'] = $donor_details->opt_line;
	$prev_data['d_address_type'] = $donor_details->address_type;
	$prev_data['d_home_phone'] = $donor_details->home_phone;
	$prev_data['d_business_phone'] = $donor_details->business_phone;
	$prev_data['d_fax_phone'] = $donor_details->fax_phone;
	$prev_data['d_mobile_phone'] = $donor_details->mobile_phone;
	$prev_data['d_org_rec'] = $donor_details->org_rec;
	$prev_data['d_donor_type'] = $donor_details->donor_type;
	$prev_data['d_nomail'] = $donor_details->nomail;
	$prev_data['d_narrative'] = $donor_details->narrative;
	
	return $prev_data;
    }
}

