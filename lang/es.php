<?php
/**
 * Spanish language file
 * @author Carlos Navarro, Daniel A. Rodriguez
 * @package FormSimple
 */
$FormSimple_lang = array(
// fields
'name'    => 'Nombre',
'email'   => 'Correo electrónico',
'address' => 'Dirección postal',
'phone'   => 'Teléfono',
'website' => 'Sitio web',
'subject' => 'Asunto',
'message' => 'Mensaje',
'file'    => 'Archivo adjunto',
'captcha' => 'Captcha',
'reload'  => 'Recargar',
'askcopy' => 'Enviar copia por Correo electrónico',
'send'    => 'Enviar',

// email words
'askedcopy'=> 'Se ha solicitado una copia de este mensaje',
'nofrom'   => 'Anónimo',
'nosubject'=> '(Sin asunto)',
'fromsite' => 'Correo electrónico enviado desde',
'sentfrom' => 'Este correo electrónico ha sido enviado desde la página',

// status messages
'sent'    => 'Correo electrónico enviado.',
'error'   => 'Error: mensaje no enviado.',
'disable' => 'Plugin de Contacto desactivado.',
'target'  => 'Este formulario de contacto no tiene destinatario.',
'token'   => 'El mensaje ya ha sido enviado.',

// fields errors
'field_required'=> 'Campo obligatorio',
'field_email'   => 'Es necesaria una dirección de correo electrónico válida',
'field_phone'   => 'Es necesario un número de teléfono válido',
'field_website' => 'Es necesaria una dirección web válida',
'field_message' => 'El mensaje ha de ser más largo',
'field_captcha' => 'Copiar el siguiente texto',
'field_fieldcaptcha' => 'Por favor, no complete este campo',
'field_password'=> 'Clave incorrecta',

// configuration panel

'config_title' => 'Configuración de FormSimple',

// messages
'config_updated' => 'Sus cambios han sido guardados.',

'config_error_open' =>
'<b>No se puede abrir el archivo de configuración.</b>
Verifique que el archivo exista y sus permisos:',

'config_error_modify' =>
'<b>No se puede modificar el archivo de configuración.</b>
Verifique los permisos:',

// New release alert
'new_release' => 'Nueva versión disponible!',
'download' => 'Descargue la última versión',

// Links
'doc' => 'Documentación',
'forum' => 'Foro',

// Parameters
'enable'     => 'Habilitar',
'enable_sub' =>
'Habilitar o desabilitar el envío de correo (no oculta el formulario).',

'default_email'     => 'Remitente predeterminado',
'default_email_sub' => 'Dejar en blanco para autocompletado',

'langs'     => 'Idioma',
'langs_sub' => 'El idioma predeterminado se ha establecido a ',

'default_params'     => 'Parámetros predeterminados',
'default_params_sub' =>
'Etiqueta predeterminado para estructura de formulario. Use la sintáxis descrita en la documentación.',

'message_len'     => 'Longitud mínima del mensaje',
'message_len_sub' => 'Cantidad mínima de caracteres para los campos del mensaje.',

'checklists'     => 'Checklists',
'blacklist'      => 'Lista negra',
'whitelist'      => 'Lista blanca',
'checklists_sub' =>
'Lista negra : valores que no deben estar presentes en el campo para envío de mensajes.<br />
Lista blanca : posibles valores requeridos para el campo de envío de mensajes.<br />
Separados por comas.',

'general_fields' => 'Campos generales',
'special_fields' => 'Campos escpeciales',

'debug'     => 'Modo debug',
'debug_sub' =>
'Deshabilitar envío de mensajes, mostrar la estructura de datos de display FormSimple, datos enviados por POST y
el mensaje que debería haber sido enviado.',
'debug_warn'=> 'No activar en sitios en producción!'
);

