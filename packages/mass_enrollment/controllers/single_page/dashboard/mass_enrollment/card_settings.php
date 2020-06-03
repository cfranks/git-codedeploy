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
class CardSettings extends DashboardPageController
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
        $selected_languages = Config::get('mass_enrollment::cardlanguages');
        $langkeys = Config::get('mass_enrollment::cardlangkeys');
        $this->set('selected_languages', $selected_languages);
        $this->set('langkeys', $langkeys);
    }

    public function add()
    {
        $selected_languages = Config::get('mass_enrollment::cardlanguages');
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
            Config::save('mass_enrollment::cardlanguages.'.$post['language'], $languages[$post['language']]);
            Config::save('mass_enrollment::langkeys.'.$languages[$post['language']], $languages[$post['language']]);
        }
        $this->flash('message', 'Language saved successfully.');
        $this->redirect('/dashboard/mass_enrollment/card_settings');
    }

    public function edit($language)
    {
        $langkeys = Config::get('mass_enrollment::cardlangkeys');
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
                Config::save('mass_enrollment::card.'.str_replace("_", ".", $key), $value);
            }
        }
        $this->flash('message', 'Transalation saved successfully.');
        $this->redirect('/dashboard/mass_enrollment/card_settings');
    }
    
    public function add_item()
    {
        $this->set('mode', 'add_item');
    }

    public function save_item()
    {
        $post = $this->request->request->all();
        Config::save('mass_enrollment::cardlangkeys.'. $post['key'], $post['default']);
        $this->flash('message', 'Item saved successfully.');
        $this->redirect('/dashboard/mass_enrollment/card_settings/add_item');
    }

    public function save_dates()
    {
        $post = $this->request->request->all();
        foreach ($post as $key=>$value) {
            if (!empty($value)) {
                Config::save('mass_enrollment::carddateformat.'. $key, $value);
            }
        }
        $this->flash('message', 'Item saved successfully.');
        $this->redirect('/dashboard/mass_enrollment/card_settings');
    }
}