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
    protected $pkgVersion = '0.0.5';

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
    
    }

    public function on_start()
    {
        require $this->getPackagePath() . '/libraries/helper.php';
    }

}
