<?php
/**
 * Bulgarian language file
 * @author Panayot Kuyvliev
 * @package FormSimple
 */
$FormSimple_lang = array(
// fields
'name'    => 'Име',
'email'   => 'Email',
'address' => 'Адрес',
'phone'   => 'Телефон',
'website' => 'Уебсайт',
'subject' => 'Относно',
'message' => 'Съобщение',
'file'    => 'Прикачи',
'captcha' => 'Captcha',
'reload'  => 'Презареди',
'fieldcaptcha' => 'Грешен код',
'askcopy' => 'Прати ми копие на това съобщение',
'send'    => 'Прати',

// email words
'askedcopy'=> 'Поискано е копие на това съобщение',
'nofrom'   => 'Анонимен',
'nosubject'=> '(Няма тема)',
'fromsite' => 'Съобщението е изпратено от',
'sentfrom' => 'Този имейл е изпратен от страница',
        
// status messages
'sent'    => 'Съобщението е изпратено.',
'error'   => 'Грешка : съобщението не е изпратено.',
'disable' => 'Контактната форма е недостъпна.',
'target'  => 'Тази контактна форма няма получател.',
'token'   => 'Съобщението вече е изпратено.',

// fields errors
'field_required'=> 'Това поле е задължително',
'field_email'   => 'Моля използвайте валиден имейл адрес',
'field_phone'   => 'Моля използвайте валиден телефонен номер',
'field_website' => 'Моля напишете валиден уеб адрес',
'field_message' => 'Моля напишете по дълго съобщение',
'field_captcha' => 'Моля копирайте следния текст',
'field_fieldcaptcha' => 'Моля не попълвайте това поле',
'field_password'=> 'Грешна парола',

// configuration panel

'config_title' => 'FormSimple Настройки',
'default' => 'По подразбиране',
'save' => 'Запази',

// messages
'config_updated' => 'Твоите промени са запазени успешно.',

'config_error_open' =>
'<b>Конфигуриращият файл не може да бъде отворен.</b> 
Проверете дали файла съществува и неговата достъпност :',

'config_error_modify' => 
'<b>Unable to modify config file.</b> 
Check the file permissions :',

// New release alert
'new_release' => 'Има нова версия!',
'download' => 'Свали последната версия',

// Links
'doc' => 'Документация',
'forum' => 'Форум',

// Parameters
'enable'     => 'Пусни',
'enable_sub' =>
'Пускане и спиране изпращането на съобщения (без да се скрива контактната форма).',

'default_email'     => 'Емейл по подразбиране',
'default_email_sub' => 'Оставете празно, за да го настроите да',

'langs'     => 'Език',
'langs_sub' => 'Езикът по подразбиране е',

'default_params'     => 'Параметри по подразбиране',
'default_params_sub' =>
'Default tag form structrure. Use syntax described in documentation.',

'message_len'     => 'Минимална големина на съобщението',
'message_len_sub' => 'Minimum number of characters for message fields.',

'checklists'     => 'Fields checklists',
'blacklist'      => 'Черен списък',
'whitelist'      => 'Бял списък',
'checklists_sub' =>
'Черен списък: стойности, които не трябва да присъстват в областта за изпращане на имейл <br />.
Бял списък: Възможните стойности от значение за областта, за да изпратите имейл <br />.
Разделени със запетаи.',

'general_fields' => 'Общи полета',
'special_fields' => 'Специални полета',

'debug'     => 'Debug mode',
'debug_sub' =>
'Изключване на изпращането на съобщения, display FormSimple data structure, data sent by POST and 
the email that would have been sent.',
'debug_warn'=> 'Don\'t active that on production website!'
);
?>
