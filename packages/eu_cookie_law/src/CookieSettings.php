<?php

/**
 * Project: EU Cookie Law Add-On
 *
 * @copyright 2017 Fabian Bitter
 * @author Fabian Bitter (fabian@bitter.de)
 * @version 1.1.1
 */

namespace Concrete\Package\EuCookieLaw\Src;

use \Concrete\Core\Support\Facade\Application;
use \Concrete\Package\EuCookieLaw\Src\Helpers;
use \Concrete\Package\EuCookieLaw\Src\Enumerations\Methods;
use \Concrete\Package\EuCookieLaw\Src\Enumerations\Languages;
use \Concrete\Core\Package\Package;
use \Concrete\Core\Page\Page;

class CookieSettings
{

    /** @var \Concrete\Core\Application\Application */
    protected $app;
    protected $package;

    public function __construct()
    {
        $this->app = Application::getFacadeApplication();
        $this->package = Package::getByHandle('eu_cookie_law');
    }

    public function getPositions()
    {
        return array(
            "bottom" => t("Bottom"),
            "top" => t("Top")
        );
    }

    private function getPositionValues()
    {
        return Helpers::getKeys($this->getPositions());
    }

    private function getSetting($keyName, $defaultValue)
    {
        return $this->package->getConfig()->get($keyName, $defaultValue);
    }

    private function setSetting($keyName, $value)
    {
        return $this->package->getConfig()->save($keyName, $value);
    }

    public function getPosition()
    {
        return $this->getSetting("settings.position", "bottom");
    }

    public function setPosition($position)
    {
        if (in_array($position, $this->getPositionValues())) {
            return $this->setSetting("settings.position", $position);
        } else {
            return false;
        }
    }

    public function getPopupBackgroundColor()
    {
        return $this->getSetting("settings.popup_background_color", "#75ca2a");
    }

    public function setPopupBackgroundColor($color)
    {
        if (Helpers::isValidColor($color)) {
            return $this->setSetting("settings.popup_background_color", $color);
        } else {
            return false;
        }
    }

    public function getPopupTextColor()
    {
        return $this->getSetting("settings.popup_text_color", "#ffffff");
    }

    public function setPopupTextColor($color)
    {
        if (Helpers::isValidColor($color)) {
            return $this->setSetting("settings.popup_text_color", $color);
        } else {
            return false;
        }
    }

    public function getPrimaryButtonTextColor()
    {
        return $this->getSetting("settings.primary_button_text_color", "#75ca2a");
    }

    public function setPrimaryButtonTextColor($color)
    {
        if (Helpers::isValidColor($color)) {
            return $this->setSetting("settings.primary_button_text_color", $color);
        } else {
            return false;
        }
    }

    public function getPrimaryButtonBackgroundColor()
    {
        return $this->getSetting("settings.primary_button_background_color", "#ffffff");
    }

    public function setPrimaryButtonBackgroundColor($color)
    {
        if (Helpers::isValidColor($color)) {
            return $this->setSetting("settings.primary_button_background_color", $color);
        } else {
            return false;
        }
    }

    public function getPrimaryButtonBorderColor()
    {
        return $this->getSetting("settings.primary_button_border_color", "#ffffff");
    }

    public function setPrimaryButtonBorderColor($color)
    {
        if (Helpers::isValidColor($color)) {
            return $this->setSetting("settings.primary_button_border_color", $color);
        } else {
            return false;
        }
    }

    public function getSecondaryButtonTextColor()
    {
        return $this->getSetting("settings.secondary_button_text_color", "#ffffff");
    }

    public function setSecondaryButtonTextColor($color)
    {
        if (Helpers::isValidColor($color)) {
            return $this->setSetting("settings.secondary_button_text_color", $color);
        } else {
            return false;
        }
    }

    public function getSecondaryButtonBackgroundColor()
    {
        return $this->getSetting("settings.secondary_button_background_color", "#fb1515");
    }

    public function setSecondaryButtonBackgroundColor($color)
    {
        if (Helpers::isValidColor($color)) {
            return $this->setSetting("settings.secondary_button_background_color", $color);
        } else {
            return false;
        }
    }

    public function getSecondaryButtonBorderColor()
    {
        return $this->getSetting("settings.secondary_button_border_color", "#fb1515");
    }

    public function setSecondaryButtonBorderColor($color)
    {
        if (Helpers::isValidColor($color)) {
            return $this->setSetting("settings.secondary_button_border_color", $color);
        } else {
            return false;
        }
    }

    /**
     * @return integer
     */
    public function getPrivacyPageId()
    {
        return $this->getSetting("settings.general_privacy_page_id", null);
    }

    /**
     * @param string $language
     * @return integer
     */
    public function getPrivacyPageIdFor($language)
    {
        return $this->getSetting("settings.privacy_page_id." . $language, null);
    }

    /**
     * @param integer $pageId
     *
     * @return boolean
     */
    public function setPrivacyPageId($pageId)
    {
        if (Helpers::isValidPageId($pageId)) {
            return $this->setSetting("settings.general_privacy_page_id", $pageId);
        } else {
            return false;
        }
    }

    /**
     * @param string $language
     * @param integer $pageId
     *
     * @return boolean
     */
    public function setPrivacyPageIdFor($language, $pageId)
    {
        if (Helpers::isValidPageId($pageId)) {
            return $this->setSetting("settings.privacy_page_id." . $language, $pageId);
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getPrivacyPageUrl()
    {
        /** @var $navHelper \Concrete\Core\Html\Service\Navigation */
        $navHelper = $this->app->make('helper/navigation');

        if ($this->getLanguageSpecificPrivacyPagesEnabled() && Helpers::isValidPageId($this->getLanguageSpecificPrivacyPageId())) {
            $page = Page::getById($this->getLanguageSpecificPrivacyPageId());
        } else {
            $page = Page::getById($this->getPrivacyPageId());
        }

        $path = Helpers::translatePath($page->getCollectionPath());

        $page = Page::getByPath($path);

        return $navHelper::getLinkToCollection($page);
    }

    /**
     * @return int
     */
    public function getLanguageSpecificPrivacyPageId()
    {
        $locale = CookieTranslation::getInstance()->getLocale();

        $languageSpecificPageId = $this->getPrivacyPageIdFor($locale);

        if (!is_null($languageSpecificPageId) && Helpers::isValidPageId($languageSpecificPageId)) {
            return $languageSpecificPageId;
        } else {
            return $this->getPrivacyPageId();
        }
    }

    public function getEnablePageReload()
    {
        return intval($this->getSetting("settings.enable_page_reload", 1)) === 1;
    }

    public function setEnablePageReload($isEnabled)
    {
        return $this->setSetting("settings.enable_page_reload", intval($isEnabled));
    }

    /**
     * @param array $arrSettings
     *
     * @return boolean
     */
    public function apply($arrSettings)
    {
        if (is_array($arrSettings)) {
            if (isset($arrSettings["customTextsEnabled"])) {
                $this->setCustomTextsEnabled(1);
            } else {
                $this->setCustomTextsEnabled(0);
            }

            if (isset($arrSettings["enablePageReload"])) {
                $this->setEnablePageReload(1);
            } else {
                $this->setEnablePageReload(0);
            }

            if (isset($arrSettings["languageSpecificPrivacyPagesEnabled"])) {
                $this->setLanguageSpecificPrivacyPagesEnabled(1);
            } else {
                $this->setLanguageSpecificPrivacyPagesEnabled(0);
            }

            if (isset($arrSettings["primaryButtonBackgroundColor"])) {
                $this->setPrimaryButtonBackgroundColor($arrSettings["primaryButtonBackgroundColor"]);
            }

            if (isset($arrSettings["primaryButtonBorderColor"])) {
                $this->setPrimaryButtonBorderColor($arrSettings["primaryButtonBorderColor"]);
            }

            if (isset($arrSettings["primaryButtonTextColor"])) {
                $this->setPrimaryButtonTextColor($arrSettings["primaryButtonTextColor"]);
            }

            if (isset($arrSettings["secondaryButtonBackgroundColor"])) {
                $this->setSecondaryButtonBackgroundColor($arrSettings["secondaryButtonBackgroundColor"]);
            }

            if (isset($arrSettings["secondaryButtonBorderColor"])) {
                $this->setSecondaryButtonBorderColor($arrSettings["secondaryButtonBorderColor"]);
            }

            if (isset($arrSettings["secondaryButtonTextColor"])) {
                $this->setSecondaryButtonTextColor($arrSettings["secondaryButtonTextColor"]);
            }

            if (isset($arrSettings["privacyPageId"])) {
                $this->setPrivacyPageId($arrSettings["privacyPageId"]);
            }

            if (isset($arrSettings["privacyPageIdFor"]) && is_array($arrSettings["privacyPageIdFor"])) {
                foreach ($arrSettings["privacyPageIdFor"] as $language => $pageId) {
                    $this->setPrivacyPageIdFor($language, $pageId);
                }
            }

            if (isset($arrSettings["popupBackgroundColor"])) {
                $this->setPopupBackgroundColor($arrSettings["popupBackgroundColor"]);
            }

            if (isset($arrSettings["popupTextColor"])) {
                $this->setPopupTextColor($arrSettings["popupTextColor"]);
            }

            if (isset($arrSettings["position"])) {
                $this->setPosition($arrSettings["position"]);
            }

            if (isset($arrSettings["method"])) {
                $this->setMethod($arrSettings["method"]);
            }

            if (isset($arrSettings["language"])) {
                $this->setLanguage($arrSettings["language"]);
            }

            if (isset($arrSettings["customText"])) {
                $this->setCustomText($arrSettings["customText"]);
            }

            if (isset($arrSettings["customLinkText"])) {
                $this->setCustomLinkText($arrSettings["customLinkText"]);
            }

            if (isset($arrSettings["customGotItText"])) {
                $this->setCustomGotItText($arrSettings["customGotItText"]);
            }

            if (isset($arrSettings["customDenyText"])) {
                $this->setCustomDenyText($arrSettings["customDenyText"]);
            }

            if (isset($arrSettings["customAllowText"])) {
                $this->setCustomAllowText($arrSettings["customAllowText"]);
            }

            if (isset($arrSettings["customDeclineText"])) {
                $this->setCustomDeclineText($arrSettings["customDeclineText"]);
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return boolean
     */
    public function reset()
    {
        $this->setPrimaryButtonBackgroundColor("#ffffff");
        $this->setPrimaryButtonBorderColor("#ffffff");
        $this->setPrimaryButtonTextColor("#75ca2a");
        $this->setSecondaryButtonBackgroundColor("#fb1515");
        $this->setSecondaryButtonBorderColor("#fb1515");
        $this->setSecondaryButtonTextColor("#ffffff");
        $this->setPrivacyPageId(0);
        $this->setPopupBackgroundColor("#75ca2a");
        $this->setPopupTextColor("#ffffff");
        $this->setPosition("bottom");

        return true;
    }

    public function getLanguage()
    {
        return $this->getSetting("settings.language", Languages::AUTO);
    }

    /**
     * @param bool $withAuto
     * @return array
     */
    public function getLanguages($withAuto = true)
    {
        $languages = array();

        if ($withAuto) {
            $languages[Languages::AUTO] = t("Auto detect (by browser language settings)");
            $languages[Languages::GEO] = t("Auto detect (by IP address)");
            $languages[Languages::LANGUAGE_SETTING] = t("Auto detect (by language settings)");
        }

        $languages[Languages::AR_SA] = t("Arabic (Saudi Arabia)");
        $languages[Languages::CS_CZ] = t("Czech");
        $languages[Languages::DA_DK] = t("Danish");
        $languages[Languages::DE_DE] = t("German");
        $languages[Languages::EL_GR] = t("Greek");
        $languages[Languages::EN_IE] = t("English (Irland)");
        $languages[Languages::EN_US] = t("English (United States)");
        $languages[Languages::ES_ES] = t("Spanish");
        $languages[Languages::ET_EE] = t("Estonian");
        $languages[Languages::FI_FL] = t("Finnish");
        $languages[Languages::FR_FR] = t("French");
        $languages[Languages::HR_HR] = t("Croatian");
        $languages[Languages::HU_HR] = t("Hungarian");
        $languages[Languages::IT_IT] = t("Italian");
        $languages[Languages::LV_LV] = t("Latvian");
        $languages[Languages::MT_MT] = t("Maltese");
        $languages[Languages::NL_NL] = t("Dutch");
        $languages[Languages::PL_PL] = t("Polish");
        $languages[Languages::PT_PT] = t("Portuguese");
        $languages[Languages::RO_RO] = t("Romanian");
        $languages[Languages::SK_SK] = t("Slovak");
        $languages[Languages::SI_SI] = t("Slovenian");
        $languages[Languages::SV_FI] = t("Swedish");

        return $languages;
    }

    public function setLanguage($language)
    {
        return $this->setSetting("settings.language", $language);
    }

    public function getMethod()
    {
        return $this->getSetting("settings.method", Methods::OPTIN);
    }

    public function setMethod($method)
    {
        return $this->setSetting("settings.method", $method);
    }

    public function getMethods()
    {
        $methods = array();

        $methods[Methods::AUTO] = t("Auto");
        $methods[Methods::OPTIN] = t("Opt In");
        $methods[Methods::OPTOUT] = t("Opt Out");
        $methods[Methods::NOTICE] = t("Notice");

        return $methods;
    }

    public function getLanguageSpecificPrivacyPagesEnabled()
    {
        return intval($this->getSetting("settings.language_specific_privacy_pages_enabled", 0)) === 1;
    }

    public function setLanguageSpecificPrivacyPagesEnabled($isEnabled)
    {
        return $this->setSetting("settings.language_specific_privacy_pages_enabled", intval($isEnabled));
    }

    public function getCustomTextsEnabled()
    {
        return intval($this->getSetting("settings.custom_texts_enabled", 0)) === 1;
    }

    public function setCustomTextsEnabled($isEnabled)
    {
        return $this->setSetting("settings.custom_texts_enabled", intval($isEnabled));
    }


    public function getCustomText()
    {
        return $this->getSetting("settings.custom_text", "");
    }

    public function setCustomText($customText)
    {
        return $this->setSetting("settings.custom_text", $customText);
    }

    public function getCustomLinkText()
    {
        return $this->getSetting("settings.custom_link_text", "");
    }

    public function setCustomLinkText($customText)
    {
        return $this->setSetting("settings.custom_link_text", $customText);
    }

    public function getCustomGotItText()
    {
        return $this->getSetting("settings.custom_got_it__text", "");
    }

    public function setCustomGotItText($customText)
    {
        return $this->setSetting("settings.custom_got_it_text", $customText);
    }

    public function getCustomDenyText()
    {
        return $this->getSetting("settings.custom_deny_text", "");
    }

    public function setCustomDenyText($customText)
    {
        return $this->setSetting("settings.custom_deny_text", $customText);
    }

    public function getCustomAllowText()
    {
        return $this->getSetting("settings.custom_allow_text", "");
    }

    public function setCustomAllowText($customText)
    {
        return $this->setSetting("settings.custom_allow_text", $customText);
    }

    public function getCustomDeclineText()
    {
        return $this->getSetting("settings.custom_decline_text", "");
    }

    public function setCustomDeclineText($customText)
    {
        return $this->setSetting("settings.custom_decline_text", $customText);
    }
}
