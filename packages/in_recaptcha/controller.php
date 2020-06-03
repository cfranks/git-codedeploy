<?php  

namespace Concrete\Package\InRecaptcha;

use Concrete\Core\Logging\Logger;
use Package;
use Concrete\Core\Captcha\Library as CaptchaLibrary;

/**
 * reCAPTCHA package for Concrete5
 * @author Chris Hougard <chris@exchangecore.com>
 * @package Concrete\Package\EcRecaptcha
 */
class Controller extends Package
{
    protected $pkgHandle = 'in_recaptcha';
    protected $appVersionRequired = '5.7.0.4';
    protected $pkgVersion = '0.1';

    public function getPackageName()
    {
        return t('Google reCAPTCHA');
    }

    public function getPackageDescription()
    {
        return t('Provides option to choose between different versions of reCaptcha.');
    }

    public function install()
    {
        $pkg = parent::install();
        CaptchaLibrary::add('recaptcha', t('Google reCAPTCHA'), $pkg);
        return $pkg;
    }
}