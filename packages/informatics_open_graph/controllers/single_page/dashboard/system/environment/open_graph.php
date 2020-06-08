<?php 
namespace Concrete\Package\InformaticsOpenGraph\Controller\SinglePage\Dashboard\System\Environment;

use \Concrete\Core\Page\Controller\DashboardPageController;

class OpenGraph extends DashboardPageController
{

    public function view()
    {
        $this->redirect('/dashboard/system/environment/open_graph/settings');
    }

}