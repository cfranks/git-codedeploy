<?php  
namespace Concrete\Package\Problog\Controller\SinglePage\Dashboard;

use \Concrete\Core\Page\Controller\DashboardPageController;

class problog extends DashboardPageController
{

    public function view()
    {
        $this->redirect('/dashboard/problog/blog_list/');
    }

}
