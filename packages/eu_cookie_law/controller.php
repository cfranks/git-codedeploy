<?php

/**
 * Project: EU Cookie Law Add-On
 *
 * @copyright 2017 Fabian Bitter
 * @author Fabian Bitter (fabian@bitter.de)
 * @version 1.1.1
 */

namespace Concrete\Package\EuCookieLaw;

use Concrete\Core\Http\Request;
use Concrete\Core\Http\Response;
use Concrete\Core\Localization\Localization;
use \Concrete\Core\Package\Package;
use \Concrete\Core\Page\Single;
use \Concrete\Core\Asset\AssetList;
use \Concrete\Package\EuCookieLaw\Src\CookieDisclosure;
use \Concrete\Core\Support\Facade\Events;
use \Concrete\Core\Support\Facade\Route;
use \Concrete\Core\Block\BlockType\BlockType;
use \Concrete\Core\Support\Facade\Application;

class Controller extends Package
{
    protected $pkgHandle = 'eu_cookie_law';
    protected $pkgVersion = '1.1.1';
    protected $appVersionRequired = '5.7.0.4';

    public function getPackageDescription()
    {
        return t('EU Cookie Law add-on for concrete5.');
    }

    public function getPackageName()
    {
        return t('EU Cookie Law');
    }

    private function addReminderRoute()
    {
        Route::register("/bitter/" . $this->pkgHandle . "/reminder/hide", function () {
            $this->getConfig()->save('reminder.hide', true);
            $app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
            /** @var $responseFactory \Concrete\Core\Http\ResponseFactory */
            $responseFactory = $app->make(\Concrete\Core\Http\ResponseFactory::class);
            $responseFactory->create("", \Concrete\Core\Http\Response::HTTP_OK)->send();
            $app->shutdown();
        });

        Route::register("/bitter/" . $this->pkgHandle . "/did_you_know/hide", function () {
            $this->getConfig()->save('did_you_know.hide', true);
            $app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
            /** @var $responseFactory \Concrete\Core\Http\ResponseFactory */
            $responseFactory = $app->make(\Concrete\Core\Http\ResponseFactory::class);
            $responseFactory->create("", \Concrete\Core\Http\Response::HTTP_OK)->send();
            $app->shutdown();
        });

        Route::register("/bitter/" . $this->pkgHandle . "/license_check/hide", function () {
            $this->getConfig()->save('license_check.hide', true);
            $app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
            /** @var $responseFactory \Concrete\Core\Http\ResponseFactory */
            $responseFactory = $app->make(\Concrete\Core\Http\ResponseFactory::class);
            $responseFactory->create("", \Concrete\Core\Http\Response::HTTP_OK)->send();
            $app->shutdown();
        });

        Route::register("/eu_cookie_law.js", function () {
            $app = Application::getFacadeApplication();

            /** @var $request Request */
            $request = $app->make(Request::class);

            if ($request->query->has("locale")) {
                Localization::changeLocale($request->query->get("locale"));
            }
	    if (!$_SESSION['eulaw']) {
	    $_SESSION['eulaw'] = CookieDisclosure::getInstance()->generateJavaScriptFileCode();
	    }
            return new Response(
                $_SESSION['eulaw'],
                200,
                array(
                    "Content-Type" => "application/javascript",
                    "Cache-Control" => "public, max-age=31536000",
                    "Pragma" => "no-cache"
                )
            );
        });
    }

    public function on_start()
    {
        $this->initComponents();
    }

    public function initComponents()
    {
        $this->loadComposerDependencies();
        $this->registerAssets();
        $this->injectCookieCode();
        $this->addReminderRoute();
    }

    private function loadComposerDependencies()
    {
        // load composer packages
        if (file_exists($this->getPackagePath() . '/vendor/autoload.php')) {
            require $this->getPackagePath() . '/vendor/autoload.php';
        }
    }

    private function registerAssets()
    {
        AssetList::getInstance()->register('javascript', 'cookie-disclosure', "js/cookie-disclosure.js", array(), $this->pkgHandle);
        AssetList::getInstance()->register('css', 'cookie-disclosure', "css/cookie-disclosure.css", array(), $this->pkgHandle);

        // register bower assets
        AssetList::getInstance()->register('javascript', 'js-cookie', "bower_components/js-cookie/src/js.cookie.js", array(), $this->pkgHandle);

        AssetList::getInstance()->registerGroup("cookie-disclosure", array(
                array("javascript", "jquery"),
                array("javascript", "js-cookie"),
                array("javascript", "cookie-disclosure"),
                array("css", "cookie-disclosure"),
            )
        );
    }

    private function injectCookieCode()
    {
        Events::addListener("on_before_render", function () {
            CookieDisclosure::getInstance()->injectCode();
        });
    }

    /**
     *
     * @param type $pathToCheck
     * @return boolean
     *
     */
    private function pageExists($pathToCheck)
    {
        $pkg = Package::getByHandle($this->pkgHandle);

        $pages = Single::getListByPackage($pkg);

        foreach ($pages as $page) {
            if ($page->getCollectionPath() === $pathToCheck) {
                return true;
            }
        }

        return false;
    }

    private function addPageIfNotExists($path, $name, $excludeNav = false, $addGlobal = false)
    {
        $pkg = Package::getByHandle($this->pkgHandle);

        if ($this->pageExists($path) === false) {
            if ($addGlobal) {
                if (version_compare(APP_VERSION, "8.0", ">=")) {
                    /*
                     * addGlobal is available since version 8
                     *
                     * https://documentation.concrete5.org/api/8.0/Concrete/Core/Page/Single.html
                     */
                    $singlePage = Single::addGlobal($path, $pkg);
                } else {
                    $singlePage = Single::add($path, $pkg);
                }
            } else {
                $singlePage = Single::add($path, $pkg);
            }

            if ($singlePage) {
                $singlePage->update(
                    array(
                        'cName' => $name
                    )
                );

                if ($excludeNav) {
                    $singlePage->setAttribute('exclude_nav', 1);
                }
            }
        }
    }

    private function installOrUpdateBlockType($blockTypeName)
    {
        $pkg = Package::getByHandle($this->pkgHandle);

        if (!is_object(BlockType::getByHandle($blockTypeName))) {
            BlockType::installBlockType($blockTypeName, $pkg);
        }
    }

    private function installBlockTypes()
    {
        $this->installOrUpdateBlockType("cookie_control");
    }

    private function installOrUpdatesPages()
    {
        $this->addPageIfNotExists("/dashboard/system/eu_cookie_law", t("EU Cookie Law"));

        /*
         * Add this page globally to avoid conflicts with multilingual pages and multi instances
         */
        $this->addPageIfNotExists("/eu_cookie_law", t("EU Cookie Law"), true, true);
    }

    private function installOrUpdate()
    {
        $this->installOrUpdatesPages();
        $this->installBlockTypes();
    }

    public function upgrade()
    {
        $this->installOrUpdate();

        parent::upgrade();
    }

    public function install()
    {
        parent::install();

        $this->installOrUpdate();
    }
}
