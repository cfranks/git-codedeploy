<?php 
namespace Concrete\Package\InformaticsOpenGraph;

use Page;
use Events;
use Config;
use Package;
use SinglePage;
use CollectionAttributeKey;
use \Concrete\Core\Attribute\Type as AttributeType;
use InformaticsOpenGraphSrc\AddOpenGraph;

class Controller extends \Concrete\Core\Package\Package
{
    protected $pkgHandle = 'informatics_open_graph';
    protected $appVersionRequired = '8.3.2';
    protected $pkgVersion = '0.0.1';
    protected $pkgAutoloaderRegistries = ['Src' => 'InformaticsOpenGraphSrc'];

    public function getPackageDescription()
    {
        return t("Provides a simple and easy way to make your site social media friendly.");
    }

    public function getPackageName()
    {
        return t("Informatics Social Media Tags");
    }

    public function install()
    {
        $pkg = parent::install();

        $sp = SinglePage::add('/dashboard/system/environment/open_graph', $pkg);
        if (is_object($sp)) {
            $sp->update(array('cName' => t('Social Media Meta Tags'), 'cDescription' => t('Manage your Social Media Meta Tags settings')));
        }

        $sp = SinglePage::add('/dashboard/system/environment/open_graph/settings', $pkg);
        if (is_object($sp)) {
            $sp->update(array('cName' => t('Social Media Meta Tags Settings'), 'cDescription' => t('Manage your Social Media Meta Tags settings')));
        }

        //Add og:image attribute
        $cak = CollectionAttributeKey::getByHandle('og_image');
        if (!is_object($cak)) {
            $at = AttributeType::getByHandle('image_file');
            CollectionAttributeKey::add($at, array('akHandle' => 'og_image', 'akName' => t('og:image')));
        }

        //Add og:title attribute
        $cak = CollectionAttributeKey::getByHandle('og_title');
        if (!is_object($cak)) {
            $at = AttributeType::getByHandle('text');
            CollectionAttributeKey::add($at, array('akHandle' => 'og_title', 'akName' => t('og:title')));
        }

        //Add og:description attribute
        $cak = CollectionAttributeKey::getByHandle('og_description');
        if (!is_object($cak)) {
            $at = AttributeType::getByHandle('text');
            CollectionAttributeKey::add($at, array('akHandle' => 'og_description', 'akName' => t('og:description')));
        }

        //Add og:title attribute
        $cak = CollectionAttributeKey::getByHandle('no_auto_og');
        if (!is_object($cak)) {
            $at = AttributeType::getByHandle('boolean');
            CollectionAttributeKey::add($at, array('akHandle' => 'no_auto_og', 'akName' => t('No Auto OG Flag')));
        }

    }



    public function on_start()
    {
        $ogp = new AddOpenGraph();
        Events::addListener('on_start', array($ogp, 'addopengraph'));
    }

}