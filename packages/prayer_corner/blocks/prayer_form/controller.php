<?php

/** Include Block Namespaces * */

namespace Concrete\Package\PrayerCorner\Block\PrayerForm;

/** Use Blocks and Package * */
use Concrete\Core\Block\BlockController;
use Config;
use Package;
use Loader;
use Core;
use PrayerCorner\PrayersModel;

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
    protected $btTable = 'btPrayer';
    protected $btInterfaceWidth = "375";
    protected $btInterfaceHeight = "450";

    /**
     * Function to return Block Description
     * 
     * @return string
     */
    public function getBlockTypeDescription()
    {
        return t("Prayer Corner Form Block");
    }

    /**
     * Function to return Block Type Name
     * 
     * @return string
     */
    public function getBlockTypeName()
    {
        return t("Prayer Corner Form");
    }

    /**
     * Function to set up variables
     * 
     * @return type
     * @author SR 09/25/2019
     */
    public function setupForm()
    {
        $country = Core::make('helper/lists/countries');
        $countries = $country->getCountries();
        $this->set('countries', $countries);
        $pheader = translatepc('PolicyTitle', $this->bLanguage, false);
        $p1 = translatepc('PolicyPoint1', $this->bLanguage, false);
        $p2 = translatepc('PolicyPoint2', $this->bLanguage, false);
        $p3 = translatepc('PolicyPoint3', $this->bLanguage, false);
        $p4 = translatepc('PolicyPoint4', $this->bLanguage, false);
        $p5 = translatepc('PolicyPoint5', $this->bLanguage, false);
        $this->set('languages', Config::get('prayer_corner::languages'));
        $this->addFooterItem('<div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                ' .(!empty($pheader) ?  '<p>'. $pheader. '</p>' : '') . '
                ' .(!empty($p1) || !empty($p2) || !empty($p3) || !empty($p4) || !empty($p5) ?  '<ul>' : '') . '
                ' .(!empty($p1) ?  '<li>'.$p1.'</li>' : '') . '
                ' .(!empty($p2) ?  '<li>'.$p2.'</li>' : '') . '
                ' .(!empty($p3) ?  '<li>'.$p3.'</li>' : '') . '
                ' .(!empty($p4) ?  '<li>'.$p4.'</li>' : '') . '
                ' .(!empty($p5) ?  '<li>'.$p5.'</li>' : '') . '
                ' .(!empty($p1) || !empty($p2) || !empty($p3) || !empty($p4) || !empty($p5) ?  '</ul>' : '') . '
              </div>
            </div>
          </div>
        </div>');
    }

    /**
     * Function to process the view and display the view
     * 
     * @return View Resposne
     * @author JS 09/24/2019
     */
    public function view()
    {
        //Setup form
        $this->setupForm();
        $this->set('format','prayerForm');
    }

    public function add()
    {   
        $this->setupForm();
    }

    public function edit()
    {
        $this->setupForm();
    }

    /**
     * Function to get the Model Object
     * 
     * @return type
     * @author JS 05/23/2017
     */
    public function getModel()
    {
        if (!is_object($this->model)) {
            $this->model = new PrayersModel();
        }
        return $this->model;
    }

    /**
     * Function to get the Model Object
     * 
     * @return type
     * @author JS 05/23/2017
     */
    public function action_submit()
    {
        $data = $this->request->request->all();
        $e = $this->validate_request($data);
        if (!$e->has()) {
            if(isset($data['post_public'])){
                $data['status'] = 0;
            } else {
                $data['status'] = 3;
            }    
            $this->getModel()->add($data);
            $this->sendMail($data);
            $this->set('message_custom', translatepc ('MessageSuccess', $this->bLanguage, false));
            $this->view();
        } else {
            $this->set('error_messages', $e->getList());
            $this->view();
        }
        
    }

    /**
     * Send mail to admin
     */
    public function sendMail($data)
    {
        // Mail to admin
        $AdminEmailTemp = Config::get('prayer_corner::custom.AdminEmail.Template');
        $FromEmail = Config::get('prayer_corner::custom.AdminEmail.FromEmail');
        $AdminEmailSub = Config::get('prayer_corner::custom.AdminEmail.Subject.'.$data['language']);
        $AdminEmailTemp =  $this->ReplaceEmailValues($AdminEmailTemp, $data);    
    	$AdminEmail = Config::get('prayer_corner::custom.AdminEmailLanguage.'.$data['language']);
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
        //$mail = Loader::helper('mail');
        //$mail->to($AdminEmail);
        //$mail->addParameter('subject', $AdminEmailSub);
        //$mail->from($FromEmail, $FromName);
        //$mail->addParameter('html', $AdminEmailTemp);
        //$mail->load('template', 'mass_enrollment');
        //$mail->sendMail();
    }

    /**
     * Replace values of template.
    */
    public function ReplaceEmailValues($html, $data)
    {
        $country = Core::make('helper/lists/countries');
        $countries = $country->getCountries();
        $data['country'] = $countries[$data['country']];

        $keys = [
            '{first_name}' => 'first_name',
            '{last_name}' => 'last_name',
            '{email}' => 'email',
            '{city}' => 'city',
            '{country}' => 'country',
            '{prayer_request}' => 'prayer_request',
            '{post_public}' => 'post_public',
            '{email_consent}' => 'email_consent'
        ];
        foreach($keys as $key => $val) {
            if ($data[$val]) {
                if($key == '{post_public}' || $key == '{email_consent}'){
                    $html = str_replace($key, 'Yes', $html);
                } else {
                    $html = str_replace($key, $data[$val], $html);
                }
            } else {
                if($key == '{post_public}' || $key == '{email_consent}'){
                    $html = str_replace($key, 'No', $html);
                } else {
                    $html = str_replace($key, 'N/A', $html);
                }
            }
        }
        return $html;
    }

    public function validate_request($data = array())
    {
        // validate input fields
        $captcha = Loader::helper('validation/captcha');
        if (empty($data)) {
            $data = $this->request->request->all();
        }

        $val = Loader::helper('validation/form');
        $vals = Loader::helper('validation/strings');
        $required = [
            'first_name' => 'FirstNameRequrired',
            'last_name' => 'LastNameRequrired',
            'email' => 'EmailAddressRequrired',
            'prayer_request' => 'PrayerRequestRequrired'
        ];

        // Add required Fields
        foreach ($required as $key => $value) {
            $val->addRequired($key, $value);
        }

        $val->setData($data);
        $val->test();
        $e = $val->getError();
        
        if ($data['email'] && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $e->add('EmailAddressInvalid');
        }

        if (!$captcha->check()) {
            $e->add('InvalidCaptcha');
        }

        return $e;
    }
}
