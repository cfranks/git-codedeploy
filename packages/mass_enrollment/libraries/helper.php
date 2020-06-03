<?php

function translate($key, $language, $echo = true, $default='')
{
    $val = Config::get('mass_enrollment::'. $language. '.'. $key);
    if (empty($val)) {
        $val = Config::get('mass_enrollment::langkeys.'. $key);
    }
    if(empty($val) && !empty($default)) {
        $val = $default;
    }
    if ($echo) {
        echo $val;
    } else {
        return $val;
    }
}

function translatecard($key, $language,$echo = true)
{
    $val = Config::get('mass_enrollment::card.'. $language. '.'. $key);
    if (empty($val)) {
        $val = Config::get('mass_enrollment::cardlangkeys.'. $key);
    }
    if ($echo) {
        echo $val;
    } else {
        return $val;
    }
}

function setTimeLimitsInfinite()
{
    ignore_user_abort(TRUE);
    ignore_user_abort(TRUE);
    ini_set('max_execution_time', 100000);
    ini_set('memory_limit', '2048M');
    set_time_limit(10000);
    ini_set('default_socket_timeout', 10000);
    ini_set('mysql.connect_timeout', 10000);
    ini_set('mysql.max_allowed_packet', '24MB');
}

function getRules($formtype, $data)
{
    $rules = array();
    switch ($formtype) {
        case 1:
            $rules = array(
                'e_requested_by' => 'RequestedByRequired',
                'e_individual_name' => 'IndividualNameRequired',
                'e_family_name' => 'FamilyNameRequired',
                'e_notification_language' => 'NotificationLanguageRequired',
                'e_first_name' => 'FirstNameRequrired',
                'e_last_name' => 'LastNameRequrired',
                'e_address' => 'AddressRequired',
                'e_city' => 'CityRequired',
                'e_country' => 'CountryRequired',
                'e_zip' => 'ZipRequired'
            );
            if (!isset($data['dChkSendNotification']) || $data['dChkSendNotification'] != 'checked') {
                unset(
                    $rules['e_notification_language'], 
                    $rules['e_first_name'], 
                    $rules['e_last_name'],
                    $rules['e_address'],
                    $rules['e_city'],
                    $rules['e_country'], 
                    $rules['e_zip']
                );
            }
            if ($data['e_enrollment_type'] == 'individual') {
                unset($rules['e_family_name']);
            } else if ($data['e_enrollment_type'] == 'family') {
                unset($rules['e_individual_name']);
            }
            break;
        case 2:
            $rules = array(
                'e_requested_by' => 'RequestedByRequired',
                'e_individual_name' => 'IndividualNameRequired',
                'e_family_name' => 'FamilyNameRequired',
                'e_notification_language' => 'NotificationLanguageRequired',
                'e_first_name' => 'FirstNameRequrired',
                'e_last_name' => 'LastNameRequrired',
                'e_address' => 'AddressRequired',
                'e_city' => 'CityRequired',
                'e_country' => 'CountryRequired',
                'e_zip' => 'ZipRequired'
            );
            if (!isset($data['dChkSendNotification']) || $data['dChkSendNotification'] != 'checked') {
                unset(
                    $rules['e_notification_language'], 
                    $rules['e_first_name'], 
                    $rules['e_last_name'],
                    $rules['e_address'],
                    $rules['e_city'],
                    $rules['e_country'], 
                    $rules['e_zip']
                );
            }
            if ($data['e_enrollment_type'] == 'individual') {
                unset($rules['e_family_name']);
            } else if ($data['e_enrollment_type'] == 'family') {
                unset($rules['e_individual_name']);
            }
            break;
        case 3:
            $rules = array(
                'e_requested_by' => 'RequestedByRequired',
                'e_individual_name' => 'IndividualNameRequired',
                'e_family_name' => 'FamilyNameRequired',
                'e_notification_language' => 'NotificationLanguageRequired',
                'e_first_name' => 'FirstNameRequrired',
                'e_last_name' => 'LastNameRequrired',
                'e_address' => 'AddressRequired',
                'e_city' => 'CityRequired',
                'e_country' => 'CountryRequired',
                'e_zip' => 'ZipRequired',
                'i_living_deceased' => 'LivDecRequiredError'
            );
            if (!isset($data['dChkSendNotification']) || $data['dChkSendNotification'] != 'checked') {
                unset(
                    $rules['e_notification_language'], 
                    $rules['e_first_name'], 
                    $rules['e_last_name'],
                    $rules['e_address'],
                    $rules['e_city'],
                    $rules['e_country'], 
                    $rules['e_zip']
                );
            }
            if ($data['e_enrollment_type'] == 'individual') {
                unset($rules['e_family_name']);
            } else if ($data['e_enrollment_type'] == 'family') {
                unset($rules['e_individual_name']);
            }
            break;
        case 4:
            $rules = array(
                'e_occasion' => 'OccasionRequiredError',
                'e_requested_by' => 'RequestedByRequired',
                'e_individual_name' => 'IndividualNameRequired',
                'e_family_name' => 'FamilyNameRequired',
                'e_notification_language' => 'NotificationLanguageRequired',
                'e_first_name' => 'FirstNameRequrired',
                'e_last_name' => 'LastNameRequrired',
                'e_address' => 'AddressRequired',
                'e_city' => 'CityRequired',
                'e_country' => 'CountryRequired',
                'e_zip' => 'ZipRequired'
            );
            if (!isset($data['dChkSendNotification']) || $data['dChkSendNotification'] != 'checked') {
                unset(
                    $rules['e_notification_language'], 
                    $rules['e_first_name'], 
                    $rules['e_last_name'],
                    $rules['e_address'],
                    $rules['e_city'],
                    $rules['e_country'], 
                    $rules['e_zip']
                );
            }
            if ($data['e_enrollment_type'] == 'individual') {
                unset($rules['e_family_name']);
            } else if ($data['e_enrollment_type'] == 'family') {
                unset($rules['e_individual_name']);
            }
            break;
        case 5:
            $rules = array(
                'e_occasion' => 'OccasionRequiredError',
                'i_number_masses' => 'NmassRequiredError',
                'e_intention' => 'IntentionRequiredError',
                'e_requested_by' => 'WhoReqRequiredError',
                'i_living_deceased' => 'LivDecRequiredError',
                'e_notification_language' => 'NotificationLanguageRequired',
                'e_first_name' => 'FirstNameRequrired',
                'e_last_name' => 'LastNameRequrired',
                'e_address' => 'AddressRequired',
                'e_city' => 'CityRequired',
                'e_country' => 'CountryRequired',
                'e_zip' => 'ZipRequired'
            );
            if (!isset($data['dChkSendNotification']) || $data['dChkSendNotification'] != 'checked') {
                unset(
                    $rules['e_notification_language'], 
                    $rules['e_first_name'], 
                    $rules['e_last_name'],
                    $rules['e_address'],
                    $rules['e_city'],
                    $rules['e_country'], 
                    $rules['e_zip']
                );
            }
            break;
        case 7:
            $rules = array(
                'e_intention' => 'IntentionRequiredError',
                'e_requested_by' => 'WhoReqRequiredError',
                'e_notification_language' => 'NotificationLanguageRequired',
                'e_first_name' => 'FirstNameRequrired',
                'e_last_name' => 'LastNameRequrired',
                'e_address' => 'AddressRequired',
                'e_city' => 'CityRequired',
                'e_country' => 'CountryRequired',
                'e_zip' => 'ZipRequired',
                'i_donation_amount' => 'DonationRequired'
            );
            if (!isset($data['dChkSendNotification']) || $data['dChkSendNotification'] != 'checked') {
                unset(
                    $rules['e_notification_language'], 
                    $rules['e_first_name'], 
                    $rules['e_last_name'],
                    $rules['e_address'],
                    $rules['e_city'],
                    $rules['e_country'], 
                    $rules['e_zip']
                );
            }
            break;
        case 8:
            $rules = array(
                'e_intention' => 'IntentionRequiredError',
                'e_requested_by' => 'WhoReqRequiredError',
                'e_notification_language' => 'NotificationLanguageRequired',
                'e_first_name' => 'FirstNameRequrired',
                'e_last_name' => 'LastNameRequrired',
                'e_address' => 'AddressRequired',
                'e_city' => 'CityRequired',
                'e_country' => 'CountryRequired',
                'e_zip' => 'ZipRequired',
                'i_donation_amount' => 'DonationRequired'
            );
            if (!isset($data['dChkSendNotification']) || $data['dChkSendNotification'] != 'checked') {
                unset(
                    $rules['e_notification_language'], 
                    $rules['e_first_name'], 
                    $rules['e_last_name'],
                    $rules['e_address'],
                    $rules['e_city'],
                    $rules['e_country'], 
                    $rules['e_zip']
                );
            }
            break;
        case 9:
            $rules = array(
                'e_intention' => 'IntentionRequiredError',
                'e_requested_by' => 'WhoReqRequiredError',
                'e_notification_language' => 'NotificationLanguageRequired',
                'e_first_name' => 'FirstNameRequrired',
                'e_last_name' => 'LastNameRequrired',
                'e_address' => 'AddressRequired',
                'e_city' => 'CityRequired',
                'e_country' => 'CountryRequired',
                'e_zip' => 'ZipRequired',
                'i_donation_amount' => 'DonationRequired'
            );
            if (!isset($data['dChkSendNotification']) || $data['dChkSendNotification'] != 'checked') {
                unset(
                    $rules['e_notification_language'], 
                    $rules['e_first_name'], 
                    $rules['e_last_name'],
                    $rules['e_address'],
                    $rules['e_city'],
                    $rules['e_country'], 
                    $rules['e_zip']
                );
            }
            break;
        case 10:
            $rules = array(
                'e_intention' => 'IntentionRequiredError',
                'e_requested_by' => 'WhoReqRequiredError',
                'e_notification_language' => 'NotificationLanguageRequired',
                'e_first_name' => 'FirstNameRequrired',
                'e_last_name' => 'LastNameRequrired',
                'e_address' => 'AddressRequired',
                'e_city' => 'CityRequired',
                'e_country' => 'CountryRequired',
                'e_zip' => 'ZipRequired',
                'i_donation_amount' => 'DonationRequired'
            );
            if (!isset($data['dChkSendNotification']) || $data['dChkSendNotification'] != 'checked') {
                unset(
                    $rules['e_notification_language'], 
                    $rules['e_first_name'], 
                    $rules['e_last_name'],
                    $rules['e_address'],
                    $rules['e_city'],
                    $rules['e_country'], 
                    $rules['e_zip']
                );
            }
            break;
        case 6:
            $rules = array(
                'e_intention' => 'IntentionRequiredError',
                'e_requested_by' => 'WhoReqRequiredError',
                'e_notification_language' => 'NotificationLanguageRequired',
                'e_first_name' => 'FirstNameRequrired',
                'e_last_name' => 'LastNameRequrired',
                'e_address' => 'AddressRequired',
                'e_city' => 'CityRequired',
                'e_country' => 'CountryRequired',
                'e_zip' => 'ZipRequired'
            );
            if (!isset($data['dChkSendNotification']) || $data['dChkSendNotification'] != 'checked') {
                unset(
                    $rules['e_notification_language'], 
                    $rules['e_first_name'], 
                    $rules['e_last_name'],
                    $rules['e_address'],
                    $rules['e_city'],
                    $rules['e_country'], 
                    $rules['e_zip']
                );
            }
            break;
    }
    return $rules;
}

function showDonationDetail($bFormType, $bLanguage, $data)
{
    $html = '';
    $arry = [
        'e_language' => 'LanguageLabel',
        'e_occasion' => 'OcassionLabel',
        'e_date' => 'EnrollmentDateLabel',
        'e_enrollment_type' => 'EnrollmentTypeTitle',
        'e_individual_name' => 'IndividualTextTitle',
        'e_family_name' => 'FamilyEnrollNameTitle',
        'e_requested_by' => 'RequestedByLabel',
        'e_special_instructions' => 'SpecialInstructionsLabel',
        'e_living_deceased' => 'Living OR Deceased',
        'e_notification_language' => 'NotificationLanguangeLabel',
        'e_title' => 'TitleLabel',
        'e_first_name' => 'FirstNameLabel',
        'e_last_name' => 'LastNameLabel',
        'e_address' => 'AddressLabel',
        'e_intention' => 'YourIntentionsLabel',
        'e_address2' => 'Address2Label',
        'e_city' => 'CityLabel',
        'e_state' => 'StateProvinceLabel',
        'e_country' => 'CountryLabel',
        'e_zip' => 'ZipPostalLabel',
        'e_email' => 'EmailLabel',
        'e_support_donation' => 'SupportWithDonationLabel',
    ];

    foreach($arry as $key => $val) {
        if ($key=='e_occasion') {
            $data[$key] = translate(Config::get('mass_enrollment::custom.occasion.'.$data[$key]), $bLanguage, false);
        }
        if ($key=='e_support_donation') {
            $data[$key] = translate(Config::get('mass_enrollment::custom.SupportDonation.'.$data[$key]), $bLanguage, false);
        }
        if ($key=='e_notification_language' || $key=='e_language') {
            $data[$key] = translate(Config::get('mass_enrollment::languages.'.$data[$key]), $bLanguage, false);
        }
        if ($key=='e_country') {
            $country = Core::make('helper/lists/countries');
            $countries = $country->getCountries();
            if (isset($countries[$data[$key]])) {
                $data[$key] = t($countries[$data[$key]]);
            }
        } 
        if ($key=='e_state' && $data['e_country']=='US') {
            $state = Core::make('helper/lists/states_provinces');
            $states = $state->getStateProvinceArray('US');
            if (isset($states[$data[$key]])) {
                $data[$key] = t($states[$data[$key]]);
            }
        }
        if ($data[$key]) {
            
            $html .= '<div class="row">'.
                        '<div class="col-sm-6">' . 
                            '<strong>' . translate($val, $bLanguage, false) . '</strong>' .
                        '</div>' .
                        '<div class="col-sm-6">' .
                            $data[$key] .
                        '</div>' .
                    '</div>';
        }
    }

    return $html;
}