<?php

/**
 * Project: EU Cookie Law Add-On
 *
 * @copyright 2017 Fabian Bitter
 * @author Fabian Bitter (fabian@bitter.de)
 * @version 1.1.1
 */

namespace Concrete\Package\EuCookieLaw\Src;

use Concrete\Core\Localization\Localization;
use \Concrete\Core\Session\Session;
use \Concrete\Core\Support\Facade\Application;
use \Concrete\Package\EuCookieLaw\Src\CookieSettings;
use \Concrete\Package\EuCookieLaw\Src\CookieTranslation;
use \Concrete\Package\EuCookieLaw\Src\Enumerations\Methods;
use \Concrete\Core\Page\Page;
use \Concrete\Core\View\View;
use \Concrete\Core\Http\Request;
use Config;

class CookieDisclosure
{

    /** @var \Concrete\Core\Application\Application */
    protected $app;
    protected $settings;
    /** @var Session */
    private $session;

    private static $instance = null;

    /**
     * @return CookieDisclosure
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->app = Application::getFacadeApplication();
        $this->settings = new CookieSettings();
        $this->session = $this->app->make('app')->make('session');
    }

    /**
     *
     * @return CookieSettings
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @return string
     */
    private function getCurrentLanguage()
    {
        return strtoupper(substr(CookieTranslation::getInstance()->getLocale(), 0, 2));
    }

    /**
     *
     * @param string $language
     *
     * @return boolean
     */
    private function isRTL($language = null)
    {
        if (is_null($language)) {
            $language = $this->getCurrentLanguage();
        }

        return in_array($language, array('AR'));
    }

    /**
     * @return array
     */
    private function getJavaScriptSettingsArray()
    {
        $cookieMessage = CookieTranslation::getInstance()->getTranslation("This website uses cookies to ensure you get the best experience on our website.");

        $link = CookieTranslation::getInstance()->getTranslation("Learn more");
        $gotIt = CookieTranslation::getInstance()->getTranslation("Got it!");
        $deny = CookieTranslation::getInstance()->getTranslation("Deny cookies");
        $allow = CookieTranslation::getInstance()->getTranslation("Allow cookies");
        $decline = CookieTranslation::getInstance()->getTranslation("Decline cookies");

        if ($this->getSettings()->getCustomTextsEnabled()) {
            if ($this->getSettings()->getCustomText() != "") {
                $cookieMessage = t($this->getSettings()->getCustomText());
            }

            if ($this->getSettings()->getCustomLinkText() != "") {
                $link = t($this->getSettings()->getCustomLinkText());
            }

            if ($this->getSettings()->getCustomGotItText() != "") {
                $gotIt = t($this->getSettings()->getCustomGotItText());
            }

            if ($this->getSettings()->getCustomDenyText() != "") {
                $deny = t($this->getSettings()->getCustomDenyText());
            }

            if ($this->getSettings()->getCustomAllowText() != "") {
                $allow = t($this->getSettings()->getCustomAllowText());
            }

            if ($this->getSettings()->getCustomDeclineText() != "") {
                $decline = t($this->getSettings()->getCustomDeclineText());
            }
        }

        return array(
            "position" => $this->getSettings()->getPosition(),
            "type" => $this->getType(),
            "serviceUrl" => $this->getServiceUrl(),
            "enablePageReload" => $this->getSettings()->getEnablePageReload(),
            "isRTL" => $this->isRTL(),
            "colors" => array(
                "popup" => array(
                    "background" => $this->getSettings()->getPopupBackgroundColor(),
                    "text" => $this->getSettings()->getPopupTextColor()
                ),
                "button" => array(
                    "primary" => array(
                        "background" => $this->getSettings()->getPrimaryButtonBackgroundColor(),
                        "border" => $this->getSettings()->getPrimaryButtonBorderColor(),
                        "text" => $this->getSettings()->getPrimaryButtonTextColor()
                    ),

                    "secondary" => array(
                        "background" => $this->getSettings()->getSecondaryButtonBackgroundColor(),
                        "border" => $this->getSettings()->getSecondaryButtonBorderColor(),
                        "text" => $this->getSettings()->getSecondaryButtonTextColor()
                    )
                )
            ),
            "content" => array(
                "href" => $this->getSettings()->getPrivacyPageUrl(),
                "message" => $cookieMessage,
                "link" => $link,
                "dismiss" => $gotIt,
                "deny" => $deny,
                "allow" => $allow,
                "decline" => $decline
            )
        );
    }

    /**
     * @return string
     */
    private function getServiceUrl()
    {
        /** @var $navHelper \Concrete\Core\Html\Service\Navigation */
        $navHelper = $this->app->make('helper/navigation');

        return $navHelper::getLinkToCollection(Page::getByPath("/eu_cookie_law"));
    }

    /**
     * @deprecated
     * @return string
     */
    private function generateJavaScriptCode()
    {
        return sprintf(
            "<script type=\"text/javascript\" src=\"%s\"></script>",
            View::url("/eu_cookie_law.js?locale=" . Localization::activeLocale())
        );
    }

    /**
     * @return string
     */
    public function generateJavaScriptFileCode()
    {
        return sprintf(
            "if (typeof jQuery !== 'undefined') {" .
            "    $(document).ready(function(){\n" .
            "        if (typeof CookieDisclosure !== 'undefined') {\n" .
            "            CookieDisclosure.init(\n" .
            "                %s\n" .
            "            );\n" .
            "        }" .
            "    });\n" .
            "}",

            json_encode($this->getJavaScriptSettingsArray())
        );
    }

    public function injectCode()
    {
        // Check if the users country need to show the Cookie Warning...
        if ($this->getHasLaw()) {
            /** @var $request Request */
            $request = $this->app->make(Request::class);

            // Check if a frontend page is requested...
            if (is_object(Page::getByPath($request->getRequestUri())) &&
                strpos($request->getRequestUri(), "/dashboard") === false &&
                strpos($request->getRequestUri(), "/tools") === false) {

                // Inject the code
                View::getInstance()->requireAsset("cookie-disclosure");

                View::getInstance()->addFooterItem($this->generateJavaScriptCode());

                // remove tracking code if cookies not allowed
                if ($this->areCookiesAllowed() === false) {
                    Config::set('concrete.seo.tracking.code', '');

                    try {
                        // Remove Tracking Codes in v8
                        $app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
                        $site = $app->make('site')->getSite();
                        $config = $site->getConfigRepository();

                        $config->set('seo.tracking.code.header', '');
                        $config->set('seo.tracking.code.footer', '');
                    } catch (\Exception $error) {
                        // Skip
                    }
                }
            }
        }
    }

    /**
     * @param string $countryCode
     *
     * @return boolean
     */
    private function getIsOptIn($countryCode = null)
    {
        if ($this->settings->getMethod() == Methods::OPTIN) {
            return true;
        }
        if ($this->settings->getMethod() == Methods::AUTO) {
            if (is_null($countryCode)) {
                $countryCode = Helpers::getCountryCodeFromIpAddress();
            }

            return in_array($countryCode, array('IT', 'ES'));
        } else {
            return false;
        }
    }

    /**
     * @param string $countryCode
     *
     * @return boolean
     */
    private function getIsOptOut($countryCode = null)
    {
        if ($this->settings->getMethod() == Methods::OPTOUT) {
            return true;
        } elseif ($this->settings->getMethod() == Methods::AUTO) {
            if (is_null($countryCode)) {
                $countryCode = Helpers::getCountryCodeFromIpAddress();
            }

            return in_array($countryCode, array('HR', 'CY', 'DK', 'EE', 'FR', 'DE', 'LV', 'LT', 'NL', 'PT'));
        } else {
            return false;
        }
    }

    /**
     * @param string $countryCode
     *
     * @return boolean
     */
    private function getHasLaw($countryCode = null)
    {
        return true;

        if (is_null($countryCode)) {
            $countryCode = Helpers::getCountryCodeFromIpAddress();
        }

        return in_array($countryCode, array('AT', 'BE', 'BG', 'HR', 'CZ', 'CY', 'DK', 'EE', 'FI', 'FR', 'DE', 'EL', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'SK', 'SI', 'ES', 'SE', 'GB', 'UK'));
    }

    /**
     *
     * @return string
     */
    private function getType()
    {
        if ($this->getIsOptIn()) {
            return "opt-in";
        } elseif ($this->getIsOptOut()) {
            return "opt-out";
        } else {
            return "";
        }
    }

    /**
     *
     * @return boolean
     */
    public function areCookiesAllowed()
    {
        if ($this->getIsOptOut()) {
            return $this->session->get("allowCookies", true) === true;
        } elseif ($this->getIsOptIn()) {
            return $this->session->get("allowCookies", false) === true;
        } else {
            return $this->session->get("allowCookies", true) === true;
        }
    }

    /**
     * @return boolean
     */
    public function optOut()
    {
        $this->session->set("allowCookies", false);
    }

    /**
     * @return boolean
     */
    public function optIn()
    {
        $this->session->set("allowCookies", true);
    }
}
