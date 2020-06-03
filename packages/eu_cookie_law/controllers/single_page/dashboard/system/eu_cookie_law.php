<?php

/**
 * Project: EU Cookie Law Add-On
 *
 * @copyright 2017 Fabian Bitter
 * @author Fabian Bitter (fabian@bitter.de)
 * @version 1.1.1
 */

namespace Concrete\Package\EuCookieLaw\Controller\SinglePage\Dashboard\System;

use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Package\EuCookieLaw\Src\CookieDisclosure;
use \Concrete\Core\Support\Facade\Application;
use \Concrete\Core\Http\Request;

class EuCookieLaw extends DashboardPageController
{
    public function reset()
    {
        CookieDisclosure::getInstance()->getSettings()->reset();

        $this->redirect("/dashboard/system/eu_cookie_law");
    }

    public function view()
    {
        $app = Application::getFacadeApplication();
        /** @var $request Request */
        $request = $app->make(Request::class);

        if ($request->isPost()) {
            CookieDisclosure::getInstance()->getSettings()->apply($request->request->all());
        }

        $this->set("settings", CookieDisclosure::getInstance()->getSettings());
    }
}
