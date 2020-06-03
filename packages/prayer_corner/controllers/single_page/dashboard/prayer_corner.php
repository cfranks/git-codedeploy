<?php
namespace Concrete\Package\PrayerCorner\Controller\SinglePage\Dashboard;

use Package;
use \Concrete\Core\Page\Controller\DashboardPageController;

defined('C5_EXECUTE') or die(_("Access Denied."));

/**
 * Class for the provider portal
 */
class PrayerCorner extends DashboardPageController
{

    /**
     * Function to set the variables for view.
     * 
     * @param void
     * @author SR 09/25/2019
     */
    public function view()
    {
        $this->redirect('/dashboard/prayer_corner/manage');
    }
}
