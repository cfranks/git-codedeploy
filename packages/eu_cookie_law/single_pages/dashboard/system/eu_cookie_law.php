<?php

/**
* Project: EU Cookie Law Add-On
*
* @copyright 2017 Fabian Bitter
* @author Fabian Bitter (fabian@bitter.de)
* @version 1.1.1
*/

defined('C5_EXECUTE') or die('Access denied');

View::element('/dashboard/Help', null, 'eu_cookie_law');
View::element('/dashboard/Reminder', array("packageHandle" => "eu_cookie_law", "rateUrl" => "https://www.concrete5.org/marketplace/addons/eu-cookie-law/reviews"), 'eu_cookie_law');

$defaults = array();

$defaults['value'] = $value;
$defaults['className'] = 'ccm-widget-colorpicker';
$defaults['showInitial'] = true;
$defaults['showInput'] = true;
$defaults['primaryEmpty'] = true;
$defaults['cancelText'] = t('Cancel');
$defaults['chooseText'] = t('Choose');
$defaults['preferredFormat'] = 'hex';
$defaults['clearText'] = t('Clear Color Selection');

$app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();

/** @var $settings \Concrete\Package\EuCookieLaw\Src\CookieSettings */
/** @var $form \Concrete\Core\Form\Service\Form */
/** @var $colorWidget \Concrete\Core\Form\Service\Widget\Color */
$colorWidget = $app->make('helper/form/color');
/** @var $pageSelector \Concrete\Core\Form\Service\Widget\PageSelector */
$pageSelector = $app->make('helper/form/page_selector');

?>
<?php \Concrete\Core\View\View::element('/dashboard/license_check', array("packageHandle" => "eu_cookie_law"), 'eu_cookie_law'); ?>

<style type="text/css">

    .ccm-widget-colorpicker, .control-label, .ccm-page-selector {
        display: block;
        float: left;
        clear: both;
    }

    .ccm-page-selector {
        width: 100%;
        margin-bottom: 15px;
    }

    .ccm-widget-colorpicker {
        margin-bottom: 15px;
    }
</style>

<div class="row">
    <div class="col-xs-12">
        <form action="#" method="post">
            <fieldset>
                <legend>
                    <?php echo t("General"); ?>
                </legend>

                <div class="form-group">
                    <?php echo $form->label("method", t("Method")); ?>
                    <?php echo $form->select("method", $settings->getMethods(), $settings->getMethod()); ?>

                    <p class="text-muted">
                        <?php echo t("Please use Opt-In to get compliant with the GDPR."); ?>
                    </p>
                </div>

                <div class="form-group">
                    <?php echo $form->label("language", t("Language")); ?>
                    <?php echo $form->select("language", $settings->getLanguages(), $settings->getLanguage()); ?>
                </div>

                <div class="checkbox">
                    <label>
                        <?php echo $form->checkbox("enablePageReload", 1, $settings->getEnablePageReload()); ?>

                        <?php echo t("Enable page reload"); ?>
                    </label>
                </div>
            </fieldset>

            <fieldset>
                <legend>
                    <?php echo t("Custom Texts"); ?>
                </legend>

                <div class="checkbox">
                    <label>
                        <?php echo $form->checkbox("customTextsEnabled", 1, $settings->getCustomTextsEnabled()); ?>

                        <?php echo t("Enabled custom texts"); ?>
                    </label>
                </div>

                <div class="custom-texts <?php echo($settings->getCustomTextsEnabled() ? "" : " hidden"); ?>">

                    <div class="form-group">
                        <?php echo $form->label("customText", t("Message Text")); ?>
                        <?php echo $form->textarea("customText", $settings->getCustomText()); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->label("customLinkText", t("Read More Text")); ?>
                        <?php echo $form->text("customLinkText", $settings->getCustomLinkText()); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->label("customAllowText", t("Allow Button Text")); ?>
                        <?php echo $form->text("customAllowText", $settings->getCustomAllowText()); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->label("customDeclineText", t("Decline Button Text")); ?>
                        <?php echo $form->text("customDeclineText", $settings->getCustomDeclineText()); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->label("customGotItText", t("Got It Button Text")); ?>
                        <?php echo $form->text("customGotItText", $settings->getCustomGotItText()); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->label("customDenyText", t("Deny Button Text")); ?>
                        <?php echo $form->text("customDenyText", $settings->getCustomDenyText()); ?>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>
                    <?php echo t("Position"); ?>
                </legend>

                <div class="form-group">
                    <?php echo $form->label("position", t("Position")); ?>
                    <?php echo $form->select("position", $settings->getPositions(), $settings->getPosition()); ?>
                </div>
            </fieldset>

            <fieldset>
                <legend>
                    <?php echo t("Colors"); ?>
                </legend>

                <div class="form-group">
                    <?php echo $form->label("popupBackgroundColor", t("Popup Background Color")); ?>
                    <?php echo $colorWidget->output("popupBackgroundColor", $settings->getPopupBackgroundColor(), $defaults); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->label("popupTextColor", t("Popup Text Color")); ?>
                    <?php echo $colorWidget->output("popupTextColor", $settings->getPopupTextColor(), $defaults); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->label("primaryButtonBackgroundColor", t("Primary Button Background Color")); ?>
                    <?php echo $colorWidget->output("primaryButtonBackgroundColor", $settings->getPrimaryButtonBackgroundColor(), $defaults); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->label("primaryButtonBorderColor", t("Primary Button Border Color")); ?>
                    <?php echo $colorWidget->output("primaryButtonBorderColor", $settings->getPrimaryButtonBorderColor(), $defaults); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->label("primaryButtonTextColor", t("Primary Button Text Color")); ?>
                    <?php echo $colorWidget->output("primaryButtonTextColor", $settings->getPrimaryButtonTextColor(), $defaults); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->label("secondaryButtonBackgroundColor", t("Secondary Button Background Color")); ?>
                    <?php echo $colorWidget->output("secondaryButtonBackgroundColor", $settings->getSecondaryButtonBackgroundColor(), $defaults); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->label("secondaryButtonBorderColor", t("Secondary Button Border Color")); ?>
                    <?php echo $colorWidget->output("secondaryButtonBorderColor", $settings->getSecondaryButtonBorderColor(), $defaults); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->label("secondaryButtonTextColor", t("Secondary Button Text Color")); ?>
                    <?php echo $colorWidget->output("secondaryButtonTextColor", $settings->getSecondaryButtonTextColor(), $defaults); ?>
                </div>
            </fieldset>

            <fieldset>
                <legend>
                    <?php echo t("Privacy Page"); ?>
                </legend>

                <div class="form-group">
                    <?php echo $pageSelector->selectPage("privacyPageId", $settings->getPrivacyPageId()); ?>
                </div>
            </fieldset>

            <fieldset>
                <legend>
                    <?php echo t("Language-specific privacy pages"); ?>
                </legend>

                <p>
                    <?php echo t("If no language specific privacy page is setted the general privacy page will be used."); ?>
                </p>

                <div class="checkbox">
                    <label>
                        <?php echo $form->checkbox("languageSpecificPrivacyPagesEnabled", 1, $settings->getLanguageSpecificPrivacyPagesEnabled()); ?>

                        <?php echo t("Enabled language-specific privacy pages"); ?>
                    </label>
                </div>

                <div class="language-specific-privacy-pages <?php echo($settings->getLanguageSpecificPrivacyPagesEnabled() ? "" : " hidden"); ?>">
                    <?php foreach ($settings->getLanguages(false) as $languageCode => $language): ?>
                        <div class="form-group">
                            <label>
                                <?php echo $language; ?>
                            </label>

                            <?php echo $pageSelector->selectPage("privacyPageIdFor[" . $languageCode . "]", $settings->getPrivacyPageIdFor($languageCode)); ?>
                        </div>
                    <?php endforeach; ?>
                </div>

            </fieldset>

            <?php \Concrete\Core\View\View::element('/dashboard/did_you_know', array("packageHandle" => "eu_cookie_law"), 'eu_cookie_law'); ?>

            <div class="ccm-dashboard-form-actions-wrapper">
                <div class="ccm-dashboard-form-actions">

                    <div class="pull-right">

                        <a href="<?php echo $this->action("reset"); ?>" class="btn btn-default" onclick="return confirm('<?php echo t("Are you sure?"); ?>');">
                            <?php echo t("Reset"); ?>
                        </a>

                        <button type="submit" class="btn btn-primary" style="margin-left: 15px;">
                            <i class="fa fa-save" aria-hidden="true"></i> <?php echo t("Save"); ?>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    (function ($) {
        $(function () {
            $("#customTextsEnabled").bind("change", function() {
                if ($(this).is(":checked")) {
                    $(".custom-texts").removeClass("hidden");
                } else {
                    $(".custom-texts").addClass("hidden");
                }
            });

            $("#languageSpecificPrivacyPagesEnabled").bind("change", function() {
                if ($(this).is(":checked")) {
                    $(".language-specific-privacy-pages").removeClass("hidden");
                } else {
                    $(".language-specific-privacy-pages").addClass("hidden");
                }
            });
        });
    })(jQuery);
</script>
