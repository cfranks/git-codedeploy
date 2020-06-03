<?php
namespace Concrete\Package\MassEnrollment\Controller\SinglePage\Dashboard\MassEnrollment;

use Config;
use Loader;
use Page;
use User;
use Package;
use Concrete\Package\MassEnrollment\Src\EnrollmentModel;
use \Concrete\Core\Page\Controller\DashboardPageController;

defined('C5_EXECUTE') or die("Access Denied.");

/**
 * Class for the custom calendar
 */
class EmailSettings extends DashboardPageController
{
	public function view()
    {
        $selected_languages = Config::get('mass_enrollment::languages');
        $this->set('languages', $selected_languages);
        $post = $this->request->request->all();
        if (isset($post['language'])) {
            $lang = $post['language'];
            $this->set('language', $post['language']);
        } else {
            $lang = 'Default';
            $this->set('language', 'Default');
        }
        $main_key = 'mass_enrollment::custom.emails.'.$lang;
        if (Config::get($main_key)) {
            foreach (Config::get($main_key) as $key => $val) {
                $this->set($key, $val);
            }
        }
    }

    public function set_language()
    {	
        $this->view();
    }

    public function save_email()
    {	
        $data = $this->request->request->all();
        if (!empty($data['language'])) {
            $main_key = 'mass_enrollment::custom.emails.'.$data['language'];
            foreach ($data as $key => $value) {
                if ($key!='language') {
                    Config::save($main_key. '.'.$key, $value);
                }
            }
            $this->flash('message', 'Saved successfully.');
            $this->redirect('/dashboard/mass_enrollment/email_Settings');
        } 
	}

	protected function validate($data = array())
    {
        if (empty($data)) {
            $data = $this->request->request->all();
        }
        $val = Loader::helper('validation/form');
        $vals = Loader::helper('validation/strings');
        $required = [
            'email' => 'The "Email" field is required.'
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