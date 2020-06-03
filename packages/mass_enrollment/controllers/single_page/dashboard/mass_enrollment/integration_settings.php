<?php
namespace Concrete\Package\MassEnrollment\Controller\SinglePage\Dashboard\MassEnrollment;

use Config;
use Loader;
use User;
use Package;
use Concrete\Package\MassEnrollment\Src\EnrollmentModel;
use \Concrete\Core\Page\Controller\DashboardPageController;

defined('C5_EXECUTE') or die("Access Denied.");

/**
 * Class for the custom calendar
 */
class IntegrationSettings extends DashboardPageController
{
    public function view()
    {
      if(Config::get('mass_enrollment::integration.flagTestMode')) {
      	$this->set('message_test_mode',1);
      }
      $data = Config::get('mass_enrollment::integration');
      $this->set('data',$data);
    }

  public function save()
  {
      if ($this->isPost()) {
        $data = $this->request->request->all();
        $error = $this->validate($data);
        if (!$error->has()) {
            // Save Update
            unset($data['ccm-submit-save']);
	    if(isset($data['donorPerfectLogin'])) {
	    	$data['donorPerfectLogin'] = 1;
	    } else {
	    	$data['donorPerfectLogin'] = 0;
	    }
            foreach($data as $key => $value){
              Config::save('mass_enrollment::integration.'.$key, $value);
            }
            $this->flash('message', 'Integration settings saved successfully.');
            $this->redirect('/dashboard/integration_settings');
        } else {
            $this->set('error_form', true);
            $this->set('error_messages', $error->getList());
        }
      }
  }


  public function validate($data = array())
  {
      if (empty($data)) {
        $data = $this->request->request->all();
    }

    $val = Loader::helper('validation/form');
    $vals = Loader::helper('validation/strings');
    $required = [
        'safeUsername' => 'The "Safe Save Username" field is required.',
        'safePassword' => 'The "Safe Save Password" field is required.',
        'donorPerfectUsername' => 'The "Donor Perfect Username" field is required.',
        'donorPerfectPassword' => 'The "Donor Perfect Password" field is required.'
    ];

    // Add required Fields
    foreach ($required as $key => $value) {
        $val->addRequired($key, $value);
    }
    $val->setData($data);
    $val->test();
    $e = $val->getError();
    return $e;
  }
  
}