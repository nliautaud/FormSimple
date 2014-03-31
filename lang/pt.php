<?php
/**
 * Portuguese Brazilian language file
 * @author Evandro Xavier Azevedo
 * @package FormSimple
 */
$FormSimple_lang = array(
// fields
'name'    => 'Nome',
'email'   => 'Email',
'address' => 'Endereço de email',
'phone'   => 'Número de Telefone',
'website' => 'Website',
'subject' => 'Assunto',
'message' => 'Mensagem',
'file'    => 'Anexo',
'captcha' => 'Captcha',
'reload'  => 'Atualizar',
'fieldcaptcha' => 'Por favor <b>don\'t</b> preencha o campo seguinte :',
'askcopy' => 'Envie-me uma cópia deste email',
'send'    => 'Enviar',

// email words
'askedcopy'=> 'Uma cópia deste email foi requerido',
'nofrom'   => 'Anônimo',
'nosubject'=> '(Sem Assunto)',
'fromsite' => 'Email enviado por',
'sentfrom' => 'Este email foi enviado a partir do site',

// status messages
'sent'    => 'Email enviado.',
'error'   => 'Erro : Nenhuma mensagem foi enviada.',
'disable' => 'As formas de contato estão desativadas.',
'target'  => 'Este formulário de contato não tem destinatário.',
'token'   => 'A mensagem já foi enviada.',

// fields errors
'field_required'=> 'Este campo é obrigatório',
'field_email'   => 'Por favor use um endereço de email válido',
'field_phone'   => 'Por favor use um número de telefone válido',
'field_website' => 'Por favor escreva um endereço web válido',
'field_message' => 'Por favor escreva uma mensagem mais longa',
'field_captcha' => 'Por favor copie o seguinte texto',
'field_fieldcaptcha' => 'Por favor não preencha este campo',
'field_password'=> 'Senha errada',

// configuration panel

'config_title' => 'FormSimple configuração',
'default' => 'Default',
'save' => 'Guardar',

// messages
'config_updated' => 'Suas alterações foram salvas com sucesso.',

'config_error_open' =>
'<b>Não é possível abrir o arquivo de configuração.</b>
Verifique se o arquivo existe e suas permissões :',

'config_error_modify' =>
'<b>Não é possível modificar o arquivo de configuração.</b>
Verifique as permissões do arquivo :',

// New release alert
'new_release' => 'Há uma nova versão!',
'download' => 'Baixe a última versão',

// Links
'doc' => 'Documentação',
'forum' => 'Fórum',

// Parameters
'enable'     => 'Ativado',
'enable_sub' =>
'Ativar ou desativar o envio de email (não ocultar as formas de contato).',

'default_email'     => 'Email padrão',
'default_email_sub' => 'Deixe em branco para deixá-lo definido como',

'langs'     => 'idioma',
'langs_sub' => 'O idioma padrão é definido como',

'default_params'     => 'Parâmetros pre-definidos',
'default_params_sub' =>
'Formulário padrão de estrutura de tags. Use a sintaxe descrita na documentação.',

'message_len'     => 'Tamanho mínimo de mensagens',
'message_len_sub' => 'Número mínimo de caracteres para os campos de mensagem.',

'checklists'     => 'Campos Checklist',
'blacklist'      => 'Lista proibida',
'whitelist'      => 'Lista permitida',
'checklists_sub' =>
'Lista proibida : valores que não devem estar presentes no campo para enviar e-mail.<br />
Lista permitida : possíveis valores necessários para o campo para enviar e-mail.<br />
Separados por vírgulas.',

'general_fields' => 'Campos gerais',
'special_fields' => 'Campos especiais',

'debug'     => 'Modo de depuração',
'debug_sub' =>
'Desativar o envio de e-mail, exibir P01-estrutura de dados de contato, os dados enviados por correio e
e-mail que teria sido enviado.',
'debug_warn'=> 'Não ative para sites de produção!'
);
?>
