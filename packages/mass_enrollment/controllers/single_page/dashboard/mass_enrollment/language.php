<?php
namespace Concrete\Package\MassEnrollment\Controller\SinglePage\Dashboard\MassEnrollment;

use Config;
use Loader;
use User;
use Package;
use Concrete\Package\MassEnrollment\Src\EnrollmentModel;
use \Concrete\Core\Page\Controller\DashboardPageController;

defined('C5_EXECUTE') or die(_("Access Denied."));

/**
 * Class for the custom calendar
 */
class Language extends DashboardPageController
{

    protected $model;
    protected $paginate = 10;

    public function __construct(\Concrete\Core\Page\Page $c)
    {
        parent::__construct($c);
        $this->model = new EnrollmentModel();
    }

    public function view()
    {
        $selected_languages = Config::get('mass_enrollment::languages');
        $langkeys = Config::get('mass_enrollment::langkeys');
        $this->set('selected_languages', $selected_languages);
        $this->set('langkeys', $langkeys);
    }

    public function add()
    {
        $selected_languages = Config::get('mass_enrollment::languages');
        $ll = $this->app->make('localization/languages');
        $languages = $ll->getLanguageList();
        $languages = array_diff($languages, $selected_languages);
        $this->set('languages', $languages);
        $this->set('mode', 'add');
    }

    public function save_language()
    {
        $ll = $this->app->make('localization/languages');
        $languages = $ll->getLanguageList();
        $post = $this->request->request->all();
        if (!empty($post['language'])) {
            Config::save('mass_enrollment::languages.'.$post['language'], $languages[$post['language']]);
        }
        $this->flash('message', 'Language saved successfully.');
        $this->redirect('/dashboard/mass_enrollment/language');
    }

    public function edit($language)
    {
        $langkeys = Config::get('mass_enrollment::langkeys');
        $package = Package::getByHandle('mass_enrollment');
        $path = $package->getRelativePath();
        $this->addHeaderItem('<link href="'.$path.'/assets/plugins/footable/css/footable.bootstrap.min.css" rel="stylesheet">');
        $this->addFooterItem('<script src="'.$path.'/assets/plugins/footable/js/footable.min.js"></script>');
        $this->addFooterItem('<script src="'.$path.'/assets/js/admin/custom.js"></script>');
        $this->set('mode', 'edit');        
        $this->set('langkeys', $langkeys);
        $this->set('language', $language);
    }

    public function save_translation()
    {
        $post = $this->request->request->all();
        unset($post['ccm-submit-directory-form']);
        foreach ($post as $key=>$value) {
            if (!empty($value)) {
                Config::save('mass_enrollment::'.str_replace("_", ".", $key), $value);
            }
        }
        $this->flash('message', 'Transalation saved successfully.');
        $this->redirect('/dashboard/mass_enrollment/language');
    }
    
    public function add_item()
    {
        $this->set('mode', 'add_item');
    }

    public function save_item()
    {
        $post = $this->request->request->all();
        Config::save('mass_enrollment::langkeys.'. $post['key'], $post['default']);
        $this->flash('message', 'Item saved successfully.');
        $this->redirect('/dashboard/mass_enrollment/language/add_item');
    }
}