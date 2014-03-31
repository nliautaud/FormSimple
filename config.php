<?php
/**
 * FormSimple configuration file.
 *
 * Internally managed by configuration
 * panel, use $FormSimple->panel().
 *
 * @author Nicolas Liautaud
 * @package FormSimple
 */
$FormSimple_settings = array(
    'visible' => True,
    'enable' => True,
    'token' => True,
    'style' => True,
    'debug' => False,

    'langs' => array(
        'selected' => '',
        'values' => array(
            '' => '--Default--',
            'bg' => 'български език',
            'de' => 'Deutsch',
            'en' => 'English',
            'es' => 'Español',
            'fi' => 'Finnish',
            'fr' => 'Français',
            'he' => 'Hebrew',
            'it' => 'Italiano',
            'lt' => 'Lietuvių kalba',
            'nl' => 'Nederlands',
            'pl' => 'Język polski',
            'pt' => 'Português',
            'ru' => 'русский язык',
            'sv' => '~Svenska'
        )
    ),

    'text_field_filter' => '',
    'text_field_filter_type' => array(
        'selected' => 'b',
        'values' => array(
            'w' => 'Whitelist',
            'b' => 'Blacklist'
        )
    ),

    'name_field_filter' => '',
    'name_field_filter_type' => array(
        'selected' => 'b',
        'values' => array(
            'w' => 'Whitelist',
            'b' => 'Blacklist'
        )
    ),

    'email_field_filter' => 'anonymailer.net,anonymails.com,anonymouse.org,anonymousspeech.com,brefmail.com,hidemyass.com,mail4trash.com,mailfreeonline.com,mailinator.com,mail-temporaire.fr,makemetheking.com,mytrashmail.com,sendanonymousemail.com,sendanonymousemail.net,send-email.org,sharpmail.co.uk,tempinbox.com,theanonymousemail.com,trashmail.net,trash-mail.com,jetable.org,yopmail.com',
    'email_field_filter_type' => array(
        'selected' => 'b',
        'values' => array(
            'w' => 'Whitelist',
            'b' => 'Blacklist'
        )
    ),

    'phone_field_filter' => '',
    'phone_field_filter_type' => array(
        'selected' => 'b',
        'values' => array(
            'w' => 'Whitelist',
            'b' => 'Blacklist'
        )
    ),

    'url_field_filter' => '',
    'url_field_filter_type' => array(
        'selected' => 'b',
        'values' => array(
            'w' => 'Whitelist',
            'b' => 'Blacklist'
        )
    ),

    'subject_field_filter' => '',
    'subject_field_filter_type' => array(
        'selected' => 'b',
        'values' => array(
            'w' => 'Whitelist',
            'b' => 'Blacklist'
        )
    )
);?>