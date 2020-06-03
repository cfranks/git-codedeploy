<?php
namespace Concrete\Package\PrayerCorner;

use Concrete\Core\Package\Package;
use BlockType;
use SinglePage;

defined('C5_EXECUTE') or die('ACCESS DENIED');

/**
 * Main controller of the package
 */
class Controller extends Package
{

    /**
     * Protected variables
     *
     * @var string
     */
    protected $pkgHandle = 'prayer_corner';
    protected $appVersionRequired = '8.5.0';
    protected $pkgVersion = '0.0.3';
    protected $pkgAutoloaderRegistries = ['src' => 'PrayerCorner'];

    /**
     * Function to get the description of the package
     *
     * @return string
     * @author SR 09/25/2017
     */
    public function getPackageDescription()
    {
        return t("Prayer Corner package build by Informatics.");
    }
    
    /**
     * Function get package name
     *
     * @return string
     * @author JS 09/25/2019
     */
    public function getPackageName()
    {
        return t("Prayer Corner Package");
    }

    /**
     * Function to overwrite the core install function of the package
     *
     * @author JS 09/25/2019
     */
    public function install()
    {
        //Install the Package
        $pkg = parent::install();
        
        $this->install_blocks($pkg);
        $this->install_single_pages($pkg);
    }

    /**
     * Function to install all the single pages for the package
     *
     * @param object $pkg
     * @author JS 09/25/2019
     */
    public function install_single_pages($pkg)
    {
        $page = SinglePage::add('/dashboard/prayer_corner', $pkg);
        $page->update(array('cName' => t('Prayer Corner'), 'cDescription' => t('Prayer Corner')));
        $page = SinglePage::add('/dashboard/prayer_corner/manage', $pkg);
        $page->update(array('cName' => t('Manage Prayers'), 'cDescription' => t('Manage Prayers')));
        $page = SinglePage::add('/dashboard/prayer_corner/language', $pkg);
        $page->update(array('cName' => t('Language Settings'), 'cDescription' => t('Language Settings')));
    }   

    /**
     * Function to install all the single pages for the package
     *
     * @param object $pkg
     * @author JS 09/25/2019
     */
    public function install_blocks($pkg)
    {
        BlockType::installBlockTypeFromPackage('prayer_list', $pkg);
        BlockType::installBlockTypeFromPackage('prayer_form', $pkg);
    }

    public function on_start()
    {
        require $this->getPackagePath() . '/libraries/helper.php';
    }
}
