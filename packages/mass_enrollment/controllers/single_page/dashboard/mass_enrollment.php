<?php
namespace Concrete\Package\MassEnrollment\Controller\SinglePage\Dashboard;

use Package;
use \Concrete\Core\Page\Controller\DashboardPageController;

defined('C5_EXECUTE') or die(_("Access Denied."));

/**
 * Class for the provider portal
 */
class MassEnrollment extends DashboardPageController
{

    /**
     * Function to set the variables for view.
     * 
     * @param void
     * @author AN 05/16/2016
     */
    public function view()
    {
        $this->redirect('/dashboard/mass_enrollment/language');
    }
    
    public function setUpView()
    {
        $pkg = Package::getByHandle('mass_enrollment');
        $this->addFooterItem('<script src="' . $pkg->getRelativePath() . '/assets/js/custom.js"></script>');
        
    }
}
