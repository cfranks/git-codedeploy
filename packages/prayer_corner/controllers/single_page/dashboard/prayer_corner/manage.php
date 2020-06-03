<?php
namespace Concrete\Package\PrayerCorner\Controller\SinglePage\Dashboard\PrayerCorner;

use Config;
use Loader;
use User;
use Package;
use Core;
use PrayerCorner\PrayersModel;
use \Concrete\Core\Page\Controller\DashboardPageController;

defined('C5_EXECUTE') or die(_("Access Denied."));

/**
 * Class for the custom calendar
 */
class Manage extends DashboardPageController
{

    protected $model;
    protected $paginate = 25;

    public function __construct(\Concrete\Core\Page\Page $c)
    {
        parent::__construct($c);
        $this->model = new PrayersModel();
    }

    public function view()
    {
        $model = $this->model;
        $this->applyFilter($model);
        $model->sortBy('date_created', 'desc');
        $model->setItemsPerPage($this->paginate);
        $prayers = $model->getPage();
        $this->set('prayers', $prayers);
        $this->set('model', $model);
        $this->addFooterItem("<script>
        $(document).ready(function() {
        $('.datepicker').datepicker();
        });
        </script>");
    }

    public function detail($id)
    {
        $country = Core::make('helper/lists/countries');
        $countries = $country->getCountries();
        $this->set('countries', $countries);
        $model = $this->model;
        $this->set('data', $model->find($id));
        $this->set('mode', 'edit');
    }

    public function applyFilter($model)
    {
        $query = $this->request->query->all();
        if (trim($query['Keyword'])) {
            $model->filter(false, '(first_name like "%'.addslashes(trim($query['Keyword'])).'%" or last_name like "%'.addslashes(trim($query['Keyword'])).'%")');
        }
        if ($query['Status']!="") {
            $model->filter('status', $query['Status']);
        }
        if($query['Language']!="") {
            $model->filter('language', $query['Language']);
        }
        if (strtotime($query['From']) > 0) {
            $model->filter('date_created', date("Y-m-d 00:00:00", strtotime($query['From'])), ">=");
        }
        if (strtotime($query['To']) > 0) {
            $model->filter('date_created', date("Y-m-d 23:59:59", strtotime($query['To'])), "<=");
        }
    }

    public function add()
    {
        $selected_languages = Config::get('prayer_corner::languages');
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
            Config::save('prayer_corner::languages.'.$post['language'], $languages[$post['language']]);
        }
        $this->flash('message', 'Language saved successfully.');
        $this->redirect('/dashboard/prayer_corner/language');
    }

    public function edit($language)
    {
        $langkeys = Config::get('prayer_corner::langkeys');
        $package = Package::getByHandle('prayer_corner');
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
            Config::save('prayer_corner::'.str_replace("_", ".", $key), $value);
        }
        $this->flash('message', 'Transalation saved successfully.');
        $this->redirect('/dashboard/prayer_corner/language');
    }
    
    public function add_item()
    {
        $this->set('mode', 'add_item');
    }

    public function save_item()
    {
        $post = $this->request->request->all();
        Config::save('prayer_corner::langkeys.'. $post['key'], $post['default']);
        $this->flash('message', 'Item saved successfully.');
        $this->redirect('/dashboard/prayer_corner/language/add_item');
    }

    public function reject($id)
    {
        $this->model->update([
            'status' => 2
        ], $id);
        $this->flash('message', 'Submission marked as rejected.');
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function approve($id)
    {
        $this->model->update([
            'status' => 1
        ], $id);
        $this->flash('message', 'Submission marked as approved.');
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function delete($id)
    {
        $this->model->delete($id);
        $this->flash('message', 'Submission has been deleted successfully.');
        $this->redirect('/dashboard/prayer_corner/manage');
    }
}