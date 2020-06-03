<?php 
namespace Concrete\Package\InformaticsOpenGraph\Controller\SinglePage\Dashboard\System\Environment\OpenGraph;

use File;
use Package;

class Settings extends \Concrete\Core\Page\Controller\DashboardPageController
{

    public function updated()
    {
        $this->set('message', t("Save settings success."));
        $this->view();
    }

    public function save_settings()
    {
        if ($this->token->validate("save_settings")) {
            if ($this->isPost()) {
                $fb_admin = $this->post('fb_admin');
                $fb_app_id = $this->post('fb_app_id');
                $og_thumbnail_id = $this->post('og_thumbnail_id');
                $default_title = $this->post('default_title');
                $default_description = $this->post('default_description');
                $seo_select = $this->post('seo_select');
                $default_format = $this->post('default_format');

                $pkg = Package::getByHandle('informatics_open_graph');
                $pkg->getConfig()->save('concrete.ogp.fb_admin_id', $fb_admin);
                $pkg->getConfig()->save('concrete.ogp.fb_app_id', $fb_app_id);
                $pkg->getConfig()->save('concrete.ogp.og_thumbnail_id', $og_thumbnail_id);
                $pkg->getConfig()->save('concrete.ogp.default_title', $default_title);
                $pkg->getConfig()->save('concrete.ogp.default_description', $default_description);
                $pkg->getConfig()->save('concrete.ogp.seo_select', $seo_select);
                $pkg->getConfig()->save('concrete.ogp.default_format', $default_format);
                $this->redirect('/dashboard/system/environment/open_graph/settings', 'updated');
            }
        }
        else {
            $this->set('error', array($this->token->getErrorMessage()));
        }
    }

    public function view()
    {
        $pkg = Package::getByHandle('informatics_open_graph');
        $fb_admin = $pkg->getConfig()->get('concrete.ogp.fb_admin_id');
        $fb_app_id = $pkg->getConfig()->get('concrete.ogp.fb_app_id');
        $thumbnailID = $pkg->getConfig()->get('concrete.ogp.og_thumbnail_id');
        $default_title = $pkg->getConfig()->get('concrete.ogp.default_title');
        $default_description = $pkg->getConfig()->get('concrete.ogp.default_description');
        $seo_select = $pkg->getConfig()->get('concrete.ogp.seo_select');
        $default_format = $pkg->getConfig()->get('concrete.ogp.default_format');

        $this->set('fb_admin', $fb_admin);
        $this->set('fb_app_id', $fb_app_id);
        $this->set('thumbnailID', $thumbnailID);
        $this->set('default_title', $default_title);
        $this->set('default_description', $default_description);
        $this->set('seo_select', $seo_select);
        $this->set('default_format', $default_format);

        $imageObject = false;
        if (!empty($thumbnailID)) {
            $imageObject = File::getByID($thumbnailID);
            if (is_object($imageObject) && $imageObject->isError()) {
                unset($imageObject);
            }
        }
        $this->set('imageObject', $imageObject);
    }
}