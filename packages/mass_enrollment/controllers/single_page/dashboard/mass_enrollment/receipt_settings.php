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
class ReceiptSettings extends DashboardPageController
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
        $main_key = 'mass_enrollment::receipts.'.$lang;
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
            $main_key = 'mass_enrollment::receipts.'.$data['language'];
            foreach ($data as $key => $value) {
                if ($key!='language') {
                    Config::save($main_key. '.'.$key, $value);
                }
            }
            $this->flash('message', 'Saved successfully.');
            $this->redirect('/dashboard/mass_enrollment/receipt_Settings');
        } 
	}
}