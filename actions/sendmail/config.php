<?php
/**
 * Sendmail configuration file for FormSimple.
 *
 * Internally managed by FormSimple,
 * use FormSimple::panel().
 * @author Nicolas Liautaud
 * @package FormSimple
 */
$FormSimple_sendmail_settings = array(
    'default_targets' => 'nicolas.liautaud@gmail.com',
    'default_params' => 'name!, email!, subject!, message!, captcha!',
    'skip_fields' => 'name,email,subject,captcha,fieldset',
);?>
