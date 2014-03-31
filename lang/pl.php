<?php
/**
 * Polish language file
 * @author barlew
 * @package FormSimple
 */
$FormSimple_lang = array(
// fields
'name'    => 'Imię i nazwisko',
'email'   => 'Email',
'address' => 'Adres',
'phone'   => 'Telefon',
'website' => 'Strona www',
'subject' => 'Temat',
'message' => 'Wiadomość',
'file'    => 'Załącznik',
'captcha' => 'Captcha',
'reload'  => 'Załaduj ponownie',
'fieldcaptcha' => 'Proszę <b>nie wypełniać</b> tych pól:',
'askcopy' => 'Wyślij kopię wiadomości do mnie',
'send'    => 'Wyślij',

// email words
'askedcopy'=> 'A copy of this email have been requested',
'nofrom'   => 'Anonymous',
'nosubject'=> '(bez tematu)',
'fromsite' => 'Wiadomość wysłana z',
'sentfrom' => 'Wiadomość została wysłana ze strony',

// status messages
'sent'    => 'Wiadomość została wysłana!',
'error'   => 'Błąd: wiadomość nie została wysłana.',
'disable' => 'Formularz kontaktowy jest wyłączony.',
'target'  => 'Nie określono odbiorcy dla tego formularza.',
'token'   => 'The message have already been sent.',

// fields errors
'field_required'=> 'Wypełnienie tego pola jest wymagane',
'field_email'   => 'Wprowadź prawidłowy adres email',
'field_phone'   => 'Wprowadź prawidłowy numer telefonu',
'field_website' => 'Wprowadź prawidłowy adres www',
'field_message' => 'Tekst wiadomości jest zbyt krótki',
'field_captcha' => 'Skopiuj następujący tekst',
'field_fieldcaptcha' => 'Proszę nie wypełniać tego pola',
'field_password'=> 'Hasło nieprawidłowe',

// configuration panel

'config_title' => 'Konfiguracja FormSimple',
'default' => 'Domyślnie',
'save' => 'Zapisz',

// messages
'config_updated' => 'Twoje ustawienia zostały pomyślnie zapisane.',

'config_error_open' =>
'<b>Nie można otworzyć pliku konfiguracyjnego.</b>
Sprawdź czy plik istnieje oraz ustawienia do jego zapisu:',

'config_error_modify' =>
'<b>Nie można zmodyfikować pliku konfiguracyjnego.</b>
Sprwdź ustawienia uprawnień tego pliku:',

// New release alert
'new_release' => 'Nowa wersja jest dostępna!',
'download' => 'Pobierz najnowszą wersję.',

// Links
'doc' => 'Dokumentacja',
'forum' => 'Forum',

// Parameters
'enable'     => 'Włączony',
'enable_sub' =>
'Włącza lub wyłącza wysyłanie maili (nie ukrywa formularza kontaktowego).',

'default_email'     => 'Domyślny adres email',
'default_email_sub' => 'Pozostaw to pole puste, aby wysyłać maile do',

'langs'     => 'Język',
'langs_sub' => 'Język domyślny ustawiony jako',

'default_params'     => 'Ustawienia domyślne',
'default_params_sub' =>
'Domyślna struktura formularza. W celu modyfikacji proszę zapoznać się z dokumentacją.',

'message_len'     => 'Minimalna długość tekstu wiadomości',
'message_len_sub' => 'Określa minimalną liczbę znaków dla tekstu wiadomości.',

'checklists'     => 'Sprawdzanie zawartości pól formularza',
'blacklist'      => 'Zabronione',
'whitelist'      => 'Dozwolone',
'checklists_sub' =>
'Zabronione: słowa, które nie mogą występować w polach formularza.<br />
Dozwolone: słowa, które mogą występować w polach formularza.<br />
Oddzielone przecinkami.',

'general_fields' => 'Pola ogólne',
'special_fields' => 'Pola specjalne',

'debug'     => 'Tryb debug',
'debug_sub' =>
'Wyłącza wysyłanie maili, wyświetla strukturę danych FormSimple oraz mail, który byłyby wysłany przez funkcję POST.',
'debug_warn'=> 'Nie włączać na stronie produkcyjnej!'
);
?>
