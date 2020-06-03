<?php
namespace Concrete\Package\MassEnrollment;

use Concrete\Core\Package\Package;
use BlockType;
use SinglePage;

defined('C5_EXECUTE') or die(_('ACCESS DENIED'));

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
    protected $pkgHandle = 'mass_enrollment';
    protected $appVersionRequired = '5.7.0.4';
    protected $pkgVersion = '0.0.10';

    /**
     * Function to get the description of the package
     *
     * @return string
     * @author JS 05/16/2017
     */
    public function getPackageDescription()
    {
        return t("Mass Enrollment package build by Informatics.");
    }
    
    /**
     * Function get package name
     *
     * @return string
     * @author JS 05/16/2017
     */
    public function getPackageName()
    {
        return t("Mass Enrollment Package");
    }

    /**
     * Function to overwrite the core install function of the package
     *
     * @author JS 05/16/2017
     */
    public function install()
    {
        //Install the Package
        $pkg = parent::install();
        BlockType::installBlockTypeFromPackage('mass_enrollment_block', $pkg);
        $this->install_single_pages($pkg);
    }

    /**
     * Function to upgrade the package
     *
     * @param object $pkg
     * @author JS 05/16/2017
     */
    public function upgrade()
    {
        parent::upgrade();
	$pkg = Package::getByHandle($this->pkgHandle);
        $this->new_install_single_pages($pkg);
    }

    /**
     * Function to install all the single pages for the package
     *
     * @param object $pkg
     * @author JS 05/16/2017
     */
    function install_single_pages($pkg)
    {
        // Install the user single page
        $page = SinglePage::add('/dashboard/mass_enrollment/language', $pkg);
        $page->update(array('cName' => t('Language Settings'), 'cDescription' => t('Language Settings')));

        // Install the user single page
        $page = SinglePage::add('/dashboard/mass_enrollment/card_Settings', $pkg);
        $page->update(array('cName' => t('Card Settings'), 'cDescription' => t('Card Settings')));

        // Install the user single page
        $page = SinglePage::add('/dashboard/mass_enrollment/email_Settings', $pkg);
        $page->update(array('cName' => t('Email Settings'), 'cDescription' => t('Email Settings')));
    
        // Install the user single page
        $page = SinglePage::add('/dashboard/mass_enrollment/integration_Settings', $pkg);
        $page->update(array('cName' => t('Integration Settings'), 'cDescription' => t('Integration Settings')));
    }

    public function new_install_single_pages($pkg)
    {
        // Install the user single page
        $page = SinglePage::add('/card_preview', $pkg);
        $page->update(array('cName' => t('CardPreview'), 'cDescription' => t('Card Preview')));

        // Install the user single page
        $page = SinglePage::add('/dashboard/mass_enrollment/receipt_settings', $pkg);
        $page->update(array('cName' => t('Receipt Settings'), 'cDescription' => t('Receipt Settings')));
    }

    public function on_start()
    {
        require $this->getPackagePath() . '/libraries/helper.php';
    }

}
