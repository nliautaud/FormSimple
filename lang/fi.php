<?php
/**
 * Finnish language file
 * @author Kristian Salonen
 * @package FormSimple
 */
$FormSimple_lang = array(
// fields
'name'    => 'Nimi',
'email'   => 'Sähköposti',
'address' => 'Katuosoite',
'phone'   => 'Puhelinnumero',
'website' => 'Kotisivu',
'subject' => 'Aihe',
'message' => 'Viesti',
'file'    => 'Liite',
'captcha' => 'Varmennus',
'reload'  => 'Päivitä sivu',
'fieldcaptcha' => '<b>Älä</b> täytä seuraavaa kenttää :',
'askcopy' => 'Lähetä minulle kopio sähköpostiin',
'send'    => 'Lähetä',

// email words
'askedcopy'=> 'Kopio sähköpostista on pyydetty',
'nofrom'   => 'Nimetön',
'nosubject'=> '(Ei aihetta)',
'fromsite' => 'Sähköposti on lähetetty :',
'sentfrom' => 'Tämä sähköposti on lähetetty sivulta',

// status messages
'sent'    => 'Sähköposti lähetetty.',
'error'   => 'Virhe: viestiä ei lähetetty.',
'disable' => 'Lähetyslomakkeet ovat pois päältä.',
'target'  => 'Tässä lomakkeessa ei ole laitettu vastaanottajaa.',
'token'   => 'Viesti on jo aiemmin lähetetty.',

// fields errors
'field_required'=> 'Tämä kenttä on täytettävä',
'field_email'   => 'Käytä oikeaa sähköpostiosoitetta',
'field_phone'   => 'Käytä oikeaa puhelinnumeroa',
'field_website' => 'Kirjoita oikea kotisivu-osoite',
'field_message' => 'Viesti on liian lyhyt, kirjoita pidempi viesti',
'field_captcha' => 'Kirjoita kuvassa oleva teksti',
'field_fieldcaptcha' => 'Älä täytä tätä kenttää',
'field_password'=> 'Väärä salasana',

// configuration panel

'config_title' => 'FormSimple asetukset',
'default' => 'Oletusarvo',
'save' => 'Tallenna',

// messages
'config_updated' => 'Sinun muutokset ovat onnistuneesti tallennettu.',

'config_error_open' =>
'<b>Ei pysty avaamaan config-tiedostoa.</b>
Katso että tiedosto on olemassa ja sillä on tarvittavat luvat:',

'config_error_modify' =>
'<b>Ei pysty muokkaamaan config-tiedostoa.</b>
Tarkista tiedostoluvat:',

// New release alert
'new_release' => 'Uusi versio on ilmestynyt!',
'download' => 'Lataa uusi versio',

// Links
'doc' => 'Dokumentaatio',
'forum' => 'Foorumi',

// Parameters
'enable'     => 'Kytke päälle',
'enable_sub' =>
'Kytke tai poista päältä sähköposti-lähetys (tämä ei piiloita lomaketta)',

'default_email'     => 'Oletus-sähköposti',
'default_email_sub' => 'Jätä tyhjäksi pitääksesi samana',

'langs'     => 'Kieli',
'langs_sub' => 'Oletuskieli on',

'default_params'     => 'Oletusparametrit',
'default_params_sub' =>
'Oletus tagi lomakerakenne. Käytä syntaksia joka on kerrottu dokumentaatiossa.',

'message_len'     => 'Viestin minimipituus',
'message_len_sub' => 'Minimimäärä merkkejä lomakekentissä',

'checklists'     => 'Kenttien tarkistuslistat',
'blacklist'      => 'Musta lista',
'whitelist'      => 'Sallitut lista',
'checklists_sub' =>
'Musta lista: sanat, jotka eivät saa olla sähköpostia lähettäessä.<br />
Sallitut lista : mahdolliset sanat jotka tarvitaan että sähköposti lähetetään.<br />
Erota pilkulla.',

'general_fields' => 'Yleiset kentät',
'special_fields' => 'Erikoiskentät',

'debug'     => 'Korjaustila',
'debug_sub' =>
'Poistaa käytöstä sähköpostilähetyksen, näyttää FormSimplein datarakenteen, data lähetetään POST-syntaksin kautta ja näyttää sähköpostin minkä olisi lähetetty.',
'debug_warn'=> 'Älä aktivoi tätä tuotettavassa sivussa!'
);

