<?php
/**
 * Lithuanian language file
 * @author Vaidotas Kazla
 * @package FormSimple
 */
$FormSimple_lang = array(
// fields
'name'    => 'Vardas',
'email'   => 'El. paštas',
'address' => 'Pašto adresas',
'phone'   => 'Telefonas',
'website' => 'Interneto svetainė',
'subject' => 'Tema',
'message' => 'Žinutė',
'file'    => 'Prisegtas failas',
'captcha' => 'Apsaugos kodas',
'reload'  => 'Atnaujinti',
'fieldcaptcha' => 'Prašome <b>nepildyti</b> šio lauko :',
'askcopy' => 'Atsiųsti laiško kopiją man',
'send'    => 'Siųsti',

// email words
'askedcopy'=> 'Buvo prašymas gauti šios žinutės kopiją',
'nofrom'   => 'Anonimas',
'nosubject'=> '(be temos)',
'fromsite' => 'Išsiųsta iš',
'sentfrom' => 'Ši žinutė buvo išsiųsta iš puslapio',
        
// status messages
'sent'    => 'Žinutė išsiųsta sėkmingai.',
'error'   => 'Klaida: nepavyko išsiųsti žinutės.',
'disable' => 'Susisiekimo formos yra išjungtos.',
'target'  => 'Ši susisiekimo forma neturi nurodyto gavėjo.',
'token'   => 'Ši žinutė jau buvo išsiųsta.',

// fields errors
'field_required'=> 'Būtina užpildyti šį lauką',
'field_email'   => 'Prašome įvesti teisingą el. pašto adresą',
'field_phone'   => 'Prašome įvesti teisingą telefono numerį',
'field_website' => 'Prašome įvesti teisingą interneto svetainės adresą',
'field_message' => 'Žinutė per trumpa',
'field_captcha' => 'Prašome įvesti šį apsaugos kodą',
'field_fieldcaptcha' => 'Prašome nepildyti šio lauko',
'field_password'=> 'Neteisingas slaptažodis',

// configuration panel

'config_title' => 'FormSimple konfigūracija',
'default' => 'Numatytasis',
'save' => 'Prisiminti',

// messages
'config_updated' => 'Jūsų pakeitimai išsaugoti sėkmingai.',

'config_error_open' =>
'<b>Nepavyko atverti konfigūracijos failo.</b> 
Prašome patikrinti kad failas egzistuoja ir turi teisingus leidimus:',

'config_error_modify' => 
'<b>Nepavyko pakeisti konfigūracijos failo.</b> 
Prašome patikrinti failo leidimus :',

// New release alert
'new_release' => 'Yra nauja versija!',
'download' => 'Parsiųsti naują versiją',

// Links
'doc' => 'Dokumentacija',
'forum' => 'Forumas',

// Parameters
'enable'     => 'Įjungti',
'enable_sub' =>
'Įjungti arba išjungti pašto siuntimą (nepaslepia formų).',

'default_email'     => 'Numatytasis el. pašto adresas',
'default_email_sub' => 'Jei paliksite tuščią, laiškai bus siunčiami į',

'langs'     => 'Kalba',
'langs_sub' => 'Numatytoji kalba nustatyta kaip',

'default_params'     => 'Numatytieji parametrai',
'default_params_sub' =>
'Numatytoji žymų formos struktūra. Naudokite sintaksę aprašytą dokumentacijoje.',

'message_len'     => 'Minimalus žinutės ilgis.',
'message_len_sub' => 'Minimalus leidžiamas simbolių skaičius žinutės laukams.',

'checklists'     => 'Laukų kontrolinis sąrašas',
'blacklist'      => 'Juodasis sąrašas',
'whitelist'      => 'Baltasis sąrašas',
'checklists_sub' =>
'Juodasis sąrašas : žodžiai kuriuos radus formos laukuose nebus siunčiamas el. laiškas.<br />
Baltasis sąrašas : žodžiai, kurie privalo būti formos laukuose, kad būtų siunčiamas el. laiškas.<br />
Reikšmės atskiriamos kableliais.',

'general_fields' => 'Pagrindiniai laukai',
'special_fields' => 'Specialūs laukai',

'debug'     => 'Derinimo režimas',
'debug_sub' =>
'Išjungiamas laiško siuntimas, vietoje to parodoma FormSimple duomenų struktūra, siunčiami POST duomenys ir 
siunčiamo laiško vaizdas.',
'debug_warn'=> 'Prašome nenaudoti veikiančioje svetainėje!'
);
?>
