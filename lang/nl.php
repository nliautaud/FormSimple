<?php
/**
 * Dutch language file
 * @author Thomas Stolwijk
 * @package FormSimple
 */
$FormSimple_lang = array(
// fields
'name'    => 'Naam',
'email'   => 'Email',
'address' => 'Postadres',
'phone'   => 'Telefoonnummer',
'website' => 'Website',
'subject' => 'Onderwerp',
'message' => 'Bericht',
'file'    => 'Attachment',
'captcha' => 'Captcha',
'reload'  => 'Herlaad',
'fieldcaptcha' => 'Vul alstublieft <b>niet</b> het volgende veld in :',
'askcopy' => 'Stuur mij een kopie van dit bericht',
'send'    => 'Verstuur',

// email words
'askedcopy'=> 'Een kopie van deze mail is aangevraagd',
'nofrom'   => 'Anoniem',
'nosubject'=> '(Geen onderwerp)',
'fromsite' => 'Mail verstuurd vanaf',
'sentfrom' => 'Deze mail is verstuurd vanaf pagina',

// status messages
'sent'    => 'Bericht verzonden.',
'error'   => 'Fout : het bericht is niet verzonden.',
'disable' => 'De contactplugin is uitgeschakeld.',
'target'  => 'Dit contactformulier heeft geen ontvanger.',
'token'   => 'Dit bericht is al verstuurd.',

// fields errors
'field_required'=> 'Dit veld is verplicht',
'field_email'   => 'Voer alstublieft een geldig email-adres in',
'field_phone'   => 'Voer alstublieft een geldig telefoonnummer in',
'field_website' => 'Voer alstublieft een geldig webadres in',
'field_message' => 'Schrijf alstublieft een langer bericht',
'field_captcha' => 'Kopieer alstublieft de volgende tekst',
'field_fieldcaptcha' => 'Vult u alstublieft dit veld niet in',
'field_password'=> 'Verkeerd wachtwoord',

// configuration panel

'config_title' => 'FormSimple configuratie',
'default' => 'Standaard',
'save' => 'Opslaan',

// messages
'config_updated' => 'Uw wijzigingen zijn opgeslagen.',

'config_error_open' =>
'<b>Kan het configuratiebestand niet openen.</b> 
Controleer of het bestand bestaat en de toegangsrechten erop :',

'config_error_modify' => 
'<b>Kan het configuratiebestand niet wijzigen.</b> 
Controleer de toegangsrechten op het bestand :',

// New release alert
'new_release' => 'Er is een nieuwe versie uitgebracht!',
'download' => 'Download de nieuwste versie',

// Links
'doc' => 'Documentatie',
'forum' => 'Forum',

// Parameters
'enable'     => 'Inschakelen',
'enable_sub' =>
'Schakelt sturen van mail aan of uit (het formulier blijft zichtbaar).',

'default_email'     => 'Standaardinstelling emailadres',
'default_email_sub' => 'Laat leeg om de standaardinstelling te gebruiken',

'langs'     => 'Taal',
'langs_sub' => 'Taal standaard ingesteld op',

'default_params'     => 'Standaardinstelling parameters',
'default_params_sub' =>
'Standaard formulierstructuur. Gebruik de syntax zoals bescheven in de documentatie.',

'message_len'     => 'Minimum berichtlengte',
'message_len_sub' => 'Minimum aantal karakters voor berichtvelden.',

'checklists'     => 'Veldchecklist',
'blacklist'      => 'Blacklist',
'whitelist'      => 'Whitelist',
'checklists_sub' =>
'Blacklist : waarden die niet in het veld mogen voorkomen om het berichte te kunnen versturen.<br />
Whitelist : waarden waarvan er een in het veld moet voorkomen om het bericht te kunnen versturen.<br />
Gescheiden door komma\'s.',

'general_fields' => 'Algemene fields',
'special_fields' => 'Speciale fields',

'debug'     => 'Debugmodus',
'debug_sub' =>
'Schakel mail sturen uit, toon datastructuur van FormSimple, data verstuurd met het POST-request en de mail die verstuurd zou zijn.',
'debug_warn'=> 'Schakel dit niet in op een website die in bedrijf is!'
);
?>

