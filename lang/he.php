<?php
/**
 * Hebrew language file
 * @author Iggy (www.web-design.co.il)
 * @package FormSimple
 */
$FormSimple_lang = array(
// fields
'name'    => 'שם',
'email'   => 'דא"ל',
'address' => 'כתובת',
'phone'   => 'טלפון',
'website' => 'אתר',
'subject' => 'נושא',
'message' => 'הודעה',
'file'    => 'קובץ מצורף',
'captcha' => 'קוד בטחון',
'reload'  => 'טעינה מחדש',
'fieldcaptcha' => 'נא <b>לא</b> למלא את השדה:',
'askcopy' => 'לשלוח העתק של המכתב',
'send'    => 'לשלוח',

// email words
'askedcopy'=> 'נדרש העתק של ההודעה',
'nofrom'   => 'Anonymous',
'nosubject'=> '(No subject)',
'fromsite' => 'נשלח מאתר',
'sentfrom' => 'נשלח מעמוד',

// status messages
'sent'    => 'הודעה נשלחה.',
'error'   => 'טעות : ההודעה לא נשלחה.',
'disable' => 'טופס צור קשר נוטרל.',
'target'  => 'אין נמען למכתב.',
'token'   => 'ההודעה כבר נשלחה.',

// fields errors
'field_required'=> 'שדה חובה',
'field_email'   => 'כתובת אינו חוקית',
'field_phone'   => 'מספר אינו חוקי',
'field_website' => 'כתובת אינו חוקית',
'field_message' => 'נא לכתוב הודעה יותר מפורטת',
'field_captcha' => 'נא להעתיק את הקוד',
'field_fieldcaptcha' => 'נא <b>לא</b> למלא את השדה',
'field_password'=> 'ססמה לא נכונה',

// configuration panel

'config_title' => 'FormSimple הגדרות',
'default' => 'ברירת המחדל',
'save' => 'שמור',

// messages
'config_updated' => 'השינוים נשמרו.',

'config_error_open' =>
'<b>לא יכול לפתוח קובץ.</b>
נא לבדוק אם הקובץ קיים ואת ההרשאות שלו',

'config_error_modify' =>
'<b>לא יכול לשנות קובץ הגדרות.</b>
נא לבדוק הרשאות',

// New release alert
'new_release' => 'גרסה חדשה מוכנה',
'download' => 'נא להוריד את הגרסה האחרונה',

// Links
'doc' => 'Documentation',
'forum' => 'Forum',

// Parameters
'enable'     => 'הפעלה',
'enable_sub' =>
'נטרל או הפעל טפסי צור קשר (לא מסתיר את הטופס).',

'default_email'     => 'דא"ל ברירת מחדל',
'default_email_sub' => 'השאר רייק לכתובת מנהל אתר',

'langs'     => 'שפה',
'langs_sub' => 'שפת ברירת מחדל עכשיו היא',

'default_params'     => 'פרמטרים של ברירת מחדל',
'default_params_sub' =>
'ברירות מחדל. נא לבדוק מסמכי הפלאג אין.',

'message_len'     => 'אורך מינימלי של הודעה',
'message_len_sub' => 'כמות אותיות בשדות של הטופס.',

'checklists'     => 'בדיקות שדות',
'blacklist'      => 'Blacklist',
'whitelist'      => 'Whitelist',
'checklists_sub' =>
'Blacklist : ערכים שלא צריכים להיות בתוך המכתב.<br />
Whitelist : ערכים נדרשים לשדה בכדי לשלוח מכתב.<br />
הפרדה בפסיקים.',

'general_fields' => 'שדות כלליים',
'special_fields' => 'שדות מיוחדים',

'debug'     => 'Debug mode',
'debug_sub' =>
'Disable mail sending, display FormSimple data structure, data sent by POST and
the email that would have been sent.',
'debug_warn'=> 'Don\'t active that on production website!'
);

