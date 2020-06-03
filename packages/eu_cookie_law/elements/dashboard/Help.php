<?php

/**
 * @project:   EU Cookie Law
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2017 Fabian Bitter
 * @version    1.1.1
 */
defined('C5_EXECUTE') or die('Access denied');

$app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();

$app->make('help')->display(t("If you need support please click <a href=\"%s\">here</a>.", "https://bitbucket.org/fabianbitter/eu_cookie_law/issues/new"));
