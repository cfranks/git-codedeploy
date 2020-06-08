<?php  
namespace Concrete\Package\Problog\MenuItem\Problog;

use Concrete\Core\Application\UserInterface\Menu\Item\Controller as MenuItemController;
use User;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends MenuItemController
{

    public function displayItem()
    {
        $u = new User();

        if ($u->isLoggedIn()) {
            return true;
        }

        return false;
    }
}
