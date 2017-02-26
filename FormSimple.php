<?php
/**
 * FormSimple - Simply create forms!
 *
 * @link http://nliautaud.fr/wiki/travaux/FormSimple Documentation
 * @link http://get-simple.info/extend/plugin/FormSimple/35 Latest Version
 * @author Nicolas Liautaud <contact@nliautaud.fr>
 * @package FormSimple
 */
if(session_id()=='') session_start();

require_once 'fields/field.php';
require_once 'actions/action.php';

class FormSimple
{
    private $id;
    private $lang;

    private $action;
    private $fields;
    private $params;

    private $visible;
    private $enabled;
    private $completed;

    private $message;
    private $fieldset;
    /**
     * FormSimple version.
     */
    public static function version() {return '1.0';}

    /*
     * INITIALISATION
     */

    public function __construct()
    {
        FormSimple::defineConstants();

        $this->defineId();
        $this->lang = FSLANG;

        $this->fields = array();
        $this->params = array();

        $this->visible = true;
        $this->enabled = true;
        $this->completed = false;

        $this->message = '';
        $this->fieldset = -1;
    }

    /**
     * Define the paths and url constants.
     */
    private static function defineConstants()
    {
        if(!defined('FSSERVER')) {
            define('FSSERVER',      'http://' . $_SERVER['SERVER_NAME']);
            define('FSROOT',        $_SERVER['DOCUMENT_ROOT']);
            define('FSPATH',        dirname(__FILE__) . '/');
            define('FSRELPATH',     substr(FSPATH, strlen(FSROOT)));
            define('FSURL',         FSSERVER . FSRELPATH);

            define('FSACTIONSPATH', FSPATH . 'actions/');
            define('FSFIELDSPATH',  FSPATH . 'fields/');
            define('FSLANGSPATH',   FSPATH . 'lang/');
            define('FSCONFIGPATH',  FSPATH . 'config.php');

            define('FSWEBSITE',     'https://github.com/nliautaud/FormSimple');
            define('FSDOCURL',      'https://github.com/nliautaud/FormSimple');
            define('FSDOWNURL',     'https://github.com/nliautaud/FormSimple');
            define('FSFORUMURL',    'https://github.com/nliautaud/FormSimple');
            define('FSVERSIONURL',  'https://github.com/nliautaud/FormSimple');
        }
    }

    /**
     * Define the form ID according
     * to forms count in page.
     */
    private function defineId()
    {
        global $FSCOUNT;
        if (!isset($FSCOUNT)) {
            $FSCOUNT = 0;
        } else $FSCOUNT++;
        $this->id = $FSCOUNT;
    }

    /*
     * OUTPUT
     */


    /**
     * Return the html display of the form
     * @return string the <form>
     */
    public function html()
    {
        $html = '<div class="FormSimple" id="FormSimple' . $this->id . '">';

        // debug
        if (FormSimple::setting('debug')) {
            $html .= $this->htmlDebug();
        }

        // message
        $html .= $this->htmlMessage(
            $this->word($this->message),
            $this->completed ? 'success' : 'failed'
        );

        // form
        if (FormSimple::setting('visible') && $this->visible) {
            $html .= '<form method="post" ';
            $html .= 'action="#FormSimple' . $this->id . '" ';
            $html .= 'class="' . $this->action->name() . '" ';
            $html .= 'autocomplete="off" >';

            foreach ($this->fields as $id => $field) {
                // field
                if ($field->type() != 'fieldset') {
                    $html .= $field->html();
                } else {
                    if ($this->fieldset() > 0) {
                        $html .= '</fieldset>';
                    }
                    $html .= '<fieldset>';
                    if ($field->title()) {
                        $html .= '<legend>' . $field->title() . '</legend>';
                    }
                    $this->fieldset = $field->value();
                }
                if($this->fieldset == 0) {
                    $html.= '</fieldset>';
                }
                if($this->fieldset > -1) {
                    $this->fieldset--;
                }
            }
            // id, token and submit
            $html .= '<div class="field submit">';
            $html .= '<input type="hidden" name="FormSimple_form[id]" value="' . $this->id . '" />';
            $html .= '<input type="hidden" name="FormSimple_form[token]" value="' . $this->tokenGet() . '" />';
            $html .= '<input type="submit" value="' . $this->word('btn_send') . '" /></div>';
            $html .= '</form>';
        }
        $html .= '</div>';

        return $html;
    }

    /**
     * Return an html display of a message.
     * @return string the <div>
     */
    private function htmlMessage($message, $class = 'failed')
    {
        if(empty($message)) return '';
        $msg = '<div class="alert ' . $class . '">';
        $msg .= $message;
        $msg .= '</div>';
        return $msg;
    }

    /**
     * Return an html display of a box report.
     * @return string the <div>
     */
    private static function htmlReport($title, $content)
    {
        $html = '<h4 style="color:#c33">' . $title . '</h4>';
        $html .= '<pre class="debug">' . $content . '</pre>';
        return $html;
    }

    /**
     * Return debug infos : SESSION, POST and FormSimple Object.
     */
    public function htmlDebug()
    {
        // store debug infos
        ini_set('display_errors', 'on');
        error_reporting(E_ALL);
        ob_start();

        if (!empty($_SESSION)) {
            echo'Session ';
            print_r($_SESSION);
        }
        if (!empty($_POST['FormSimple_form'])
            && $_POST['FormSimple_form']['id'] == $this->id
        ) {
            echo'Post ';
            print_r($_POST);
        }
        print_r($this);
        $debug = ob_get_clean();

        // debug layout
        $output = FormSimple::htmlReport('FormSimple #' . $this->id . ' debug', $debug);
        if ($this->completed) {
            $output .= FormSimple::htmlReport('FormSimple #' . $this->id . ' action debug', $this->action->htmlDebug());
        }
        $output .= '<h4 style="color:#c33">FormSimple #' . $this->id . '</h4>';

        return $output;
    }


    /*
     * POST - ACTION
     */


    /**
     * Update POSTed form and execute action
     *
     * Check posted data, update form data,
     * define fields errors and form status.
     * At least, if there is no errors, try to send mail.
     */
    public function post()
    {
        if (!empty($_POST['FormSimple_form'])
            && $_POST['FormSimple_form']['id'] == $this->id
        ) {
            // Update and check fields values
            foreach ( $this->formatData($_POST['FormSimple_fields']) as $field_id => $field_post ) {
                $field = $this->field($field_id);

                // Define field value or values selections
                if (is_array($field->value())) {
                    $field->valueSelection($field_post);
                } else $field->value($field_post);

                if (!$field->checkContent()) {
                    $errors = true;
                }
            }
            // Security tokens
            if (FormSimple::setting('enable_token') && !$this->tokenCompare()) {
                $this->message('form_error_token');
                $this->visible(false);
            } elseif (isset($errors)) {
                $this->message('form_error_fields');
            } else {
                // Action
                if (!FormSimple::setting('enable') || !$this->enabled) {
                    // FormSimple is disabled
                    $this->message('form_error_disabled');
                } elseif ($this->action() == null) {
                    // No action is defined
                    $this->message('form_error_action');
                } else {
                    // Execute action
                    $this->completed(true);
                    $this->action->exec();
                }
            }
            $this->tokenSet();
        }
    }

    /**
     * Define or return the action.
     *
     * If an action name is given search file and
     * instanciate action class as form action.
     * @param string $class
     */
    public function action($class = null)
    {
        if($class === null) {
            return $this->action;
        }

        $file = FSACTIONSPATH . $class . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once($file);
            if(class_exists($class)) {
                $this->action = new $class($this);
                return true;
            } else $this->error = 'the action class "'.$class.'" does not exist.';
        } else $this->error = 'the action file "'.$class.'.php" does not exist.';
    }

    /**
     * Return the list of existing actions.
     *
     * @return array
     */
    public static function actionsList()
    {
        $actions = array();
        if ($handle = opendir(FSACTIONSPATH)) {
            while (false !== ($dir = readdir($handle))) {
                if ($dir[0] != '.'
                    && is_dir(FSACTIONSPATH . $dir)
                    && file_exists(FSACTIONSPATH . $dir . '/' . $dir . '.php')
                ) {
                    $actions[] = $dir;
                }
            }
            closedir($handle);
        }
        return $actions;
    }


    /*
     * LANG
     */


    /**
     * Return a traduction of the keyword.
     *
     * Use form language if defined or language setting if defined or
     * language constant FSLANG if defined or english, and call {@link sword()}.
     *
     * @param string $key the keyword
     * @return string the keyword translation
     *
     * @see sword
     */
    public function word($key)
    {
        $langs = FormSimple::setting('langs');
        $this->lang = $langs['selected'];

        if (empty($this->lang)) {
            if (!defined('FSLANG')) {
                return $this->sword($key, 'en');
            }
            return $this->sword($key, FSLANG);
        }
        return $this->sword($key, $this->lang);
    }

    /**
     * Return a static traduction of the keyword.
     *
     * If a language is not given the one defined in settings is used,
     * and if the language file does not exist, english is used.
     * If the keyword does not exist in language file, the given default
     * value is returned (formated) or the key itself (formated).
     *
     * @param string $key the keyword
     * @param string $lang the language
     * @param string $default return that if the key have no translation
     * @return string the keyword translation
     *
     * @see word
     */
    public static function sword(
        $key,
        $lang = null,
        $default = null,
        $langs_path = FSLANGSPATH
    ) {
        if(empty($key)) return '';

        // use setting defined language if not given
        if ($lang === null) {
            $langs = FormSimple::setting('langs');
            $lang = $langs['selected'];
        }

        // use english file if translation file does not exist
        $path = $langs_path . $lang . '.php';
        if (!file_exists($path)) {
            $path = $langs_path . 'en.php';
        }

        global $FormSimple_lang;
        include_once $path;

        // return translation if exists
        if (isset($FormSimple_lang[$key])) {
            return $FormSimple_lang[$key];
        }

        // return formated default value if exists
        if ($default !== null) {
            return ucfirst(str_replace('_', ' ', $default));
        }

        // finally, return formated key
        return ucfirst(str_replace('_', ' ', $key));
    }


    /*
     * TOKENS
     */


    /**
     * Create an unique hash in SESSION.
     */
    private function tokenSet()
    {
        $_SESSION['FormSimple_token'] = uniqid(md5(microtime()), true);
    }
    /**
     * Get the token in SESSION.
     *
     * @return string
     */
    private function tokenGet()
    {
        if(!isset($_SESSION['FormSimple_token'])) {
            $this->tokenSet();
        }
        return $_SESSION['FormSimple_token'];
    }
    /**
     * Compare the POST token to the SESSION one.
     *
     * @return boolean
     */
    private function tokenCompare()
    {
        if (isset($_POST['FormSimple_form']['token'])
            && $this->tokenGet() === $_POST['FormSimple_form']['token']
        ) {
            return true;
        }
        return false;
    }


    /*
     * TOOLS
     */


    /**
     * Format recursively any value to
     * be securely displayed in a page.
     *
     * @param mixed $data
     * @return mixed data
     */
    private function formatData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $data[$key] = $this->formatData($val);
            }
        } else {
            $data = stripslashes($data);
            $data = htmlentities($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }

    /**
     * Reset all fields values and errors
     */
    public function reset()
    {
        foreach ($this->fields as $field) {
            $field->value('');
            $field->error('');
        }
    }

    /**
     * Hides the form
     * (used by Actions after successful processing of the form)
     * @todo implement FormSimple::hide()
     */
    public function hide() {
        // nothing yet
    }

    /**
     * Check if a given field class exists,
     * optionally after including the class file.
     * @param string $className the field class name
     * @param boolean $include include class if possible
     * @return boolean
     */
    public static function isFieldClass($className, $include = false)
    {
        $file = FSFIELDSPATH . $className . '.php';
        if ($include && file_exists($file)) {
            require_once $file;
        }
        if(class_exists($className)) return true;
        return false;
    }


    /*
     * TAGS PARSING
     */


    /**
     * Find all tags in string and replace them by forms.
     *
     * @param string $string the string to parse
     * @param string $opening the tag opening character(s), default (%
     * @param string $closing the tag closing character(s), default %)
     * @return string
     */
    public static function parse($string, $opening = '(%', $closing = '%)')
    {
        // find tags
        $pattern =  '`(?<!<code>)'          //// don't parse tags preceded by <code>
                    . '(?:<p>\s*)?'         //// skip the surrounding <p> if exists
                    . preg_quote($opening)  // tag opening
                    . '\s*(\S*)'            // the action name
                    . '\s*form'             // the word form
                    . '\s*(?::\s*(.*))?\s*' // sometimes a semicolon followed by the parameters
                    . preg_quote($closing)  // tag closing
                    . '(?:\s*</p>)?`';      //// skip the surrounding <p> if exists
        preg_match_all($pattern, $string, $tags, PREG_SET_ORDER);

        foreach ($tags as $tag) {
            // create form
            $form = new FormSimple();
            $return = $form->action($tag[1]);
            if(!$return) continue;
            $form->parseParameters(isset($tag[2]) ? $tag[2] : '');

            // check POST
            $form->post();

            // replace tag by form
            $string = preg_replace($pattern, $form->html(), $string, 1);

        }
        return $string;
    }

    /**
     * Create and setup fields from a list of parameters.
     *
     * @param string $parameters the parameters to parse
     * @param string $separator the separator, a comma by default
     * @return boolean the parsing status
     */
    public function parseParameters($parameters, $separator = ',')
    {
        if($parameters === null) {
            return false;
        }

        // assure encoding
        $parameters = str_replace('&nbsp;', ' ', $parameters);
        $parameters = html_entity_decode($parameters, ENT_QUOTES, 'utf-8');

        // find and analyse parameters
        $s = $separator;
        $pattern =  '`([^ '.$s.'"=!?]+)'    // field type
                    . '\s*([!?])?'          // is required! or hidden?
                    . '\s*(?:"([^"]*)")?'   // field "title"
                    . '\s*(?:(=[><]?)?'     // content type = value, => locked or =< placeholder
                    . '\s*([^'.$s.']*))?`'; // content
        preg_match_all($pattern, $parameters, $parameters, PREG_SET_ORDER);

        // define unknown parameters
        $fieldsnbr = 0;
        foreach ($parameters as $p) {
            if (!FormSimple::isFieldClass($p[1], true)) {
                $this->addParam($p[1]);
            } else $fieldsnbr++;
        }
        // use action default fields if there is no fields in parameters
        if ($fieldsnbr == 0 && $this->action != null) {
            $default = $this->action->setting('default_params');
            $this->parseParameters($default);
        }
        // create and add fields
        $id = 0;
        foreach ($parameters as $p) {
            $class = $p[1];
            if (FormSimple::isFieldClass($class, true)) {
                $field = new $class($id);
                $field->construct($p[2], $p[3], $p[4], $p[5]);
                $this->addField($field);
                $id++;
            }
        }
        return true;
    }


    /*
     * CONFIGURATION
     */


    /**
     * Return a setting value from config file.
     *
     * @param string $key the setting key
     * @return mixed the setting value
     */
    public static function setting($key)
    {
        if(!defined('FSCONFIGPATH'))
            FormSimple::defineConstants();

        global $FormSimple_settings;
        require_once FSCONFIGPATH;
        if(isset($FormSimple_settings[$key]))
            return $FormSimple_settings[$key];
    }

    /**
     * Display the configuration panel.
     *
     * Create on the fly the configuration forms
     * from FormSimple and actions config files.
     */
    public static function config()
    {
        FormSimple::defineConstants();

        $head = '';
        if (!file_exists(FSCONFIGPATH)) {
            $head .= '<div class="error">';
            $head .= FormSimple::sword('error_file_open');
            $head .= '<pre>' . FSCONFIGPATH . '</pre></div>';
            return;
        }
        $head .= '<h2>' . FormSimple::sword('config_title') . '</h2>';

        // Links
        $head .= '<p><a href="' . FSDOCURL . '">' . FormSimple::sword('documentation') . '</a>';
        $head .= ' - <a href="' . FSFORUMURL . '">' . FormSimple::sword('forum') . '</a></p>';


        // Main settings
        $forms = FormSimple::configForm();
        $toc = '<li><a href="#FormSimple">FormSimple</a></li>';
        // Actions settings
        foreach (FormSimple::actionsList() as $action) {
            $form = FormSimple::configForm($action);
            if (!empty($form)) {
                $forms .= $form;
                $toc .= '<li><a href="#'. $action . '">' . ucfirst($action) . '</a></li>';
            }
        }
        // Table of contents
        if(!empty($toc)) {
            $toc = FormSimple::sword('table_of_contents') . '<ul>' . $toc . '</ul>';
        }

        echo $head . $toc . $forms;
    }

    /**
     * Return a configuration form capable to manage the
     * FormSimple settings or a given action settings.
     *
     * @param string $action an action name. FormSimple config by default.
     * @return string the form
     */
    private static function configForm($action = '')
    {
        if (!empty($action)) {
            $name = 'FormSimple_' . $action . '_settings';
            $file = FSACTIONSPATH . $action . '/config.php';
        } else {
            $action = 'FormSimple';
            $name = 'FormSimple_settings';
            $file = FSCONFIGPATH;
        }
        if(!file_exists($file)) return '';

        global $$name;
        require_once $file;

        $html = '<h3 id="' . $action . '" style="margin-top:30px;">' . ucfirst($action) . '</h3>';

        // POST

        if (isset($_POST[$name])) {
            if (FormSimple::configEdit($file, $name, $$name, $_POST[$name])) {
                $html .= '<div class="updated">';
                $html .= FormSimple::sword('config_updated');
                $html .= '</div>';
                require $file;
            } else {
                $html .= '<div class="error">';
                $html .= FormSimple::sword('error_file_modify');
                $html .= '<pre>' . $file_path . '</pre></div>';
            }
        }

        // DISPLAY

        $html .= '<form action="#' . $action . '" method="post">';
        foreach ($$name as $setting => $value) {
            $html .= '<div style="margin:10px 0;">';
            $html .= FormSimple::configField($name, $setting, $value);
            $html .= '</div>';
        }
        $html .= '<input type="submit" class="submit" value="' . FormSimple::sword('btn_save') . '" />';
        $html .= '</form>';

        return $html;
    }

    /**
     * Return a setting edition field.
     *
     * Replace old values in a given config file by new values.
     *
     * @param string $name the config var name
     * @param string $setting the setting name
     * @param mixed $value the setting valuele
     * @return string the field
     */
    private static function configField($name, $setting, $value)
    {
        if (is_bool($value)) {
            $html = '<input type="checkbox" name="' . $name . '[' . $setting . ']" ';
            $html .= $value ? 'checked="checked" ' : '';
            $html .= 'style="float:right;" />';
        } elseif (is_string($value)) {
            $html = '<b>' . FormSimple::sword('setting_' . $setting, null, $setting) . '</b><br />';
            $html .= '<i>' . FormSimple::sword('setting_' . $setting . '_sub', null, '') . '</i><div>';
            $html .= '<textarea name="' . $name . '[' . $setting . ']" style="width:100%;height:40px">';
            $html .= $value . '</textarea>';
        } elseif (is_numeric($value)) {
            $html = '<input type="text" name="' . $name . '[' . $setting . ']" ';
            $html .= 'value="' . $value . '" style="float:right;" />';
        } elseif (is_array($value)) {
            $html = '<select name="' . $name . '[' . $setting . ']" style="float:right;">';
            foreach ($value['values'] as $key => $val) {
                $html .= '<option value="' . $key . '" ';
                if ($value['selected'] == $key) {
                    $html .= 'selected="selected" ';
                }
                $html .= '/>' . $val . '</option>';
            }
            $html .= '</select>';
        }
        // Title after field because of float:right
        if (!is_string($value)) {
            $html .= '<b>' . FormSimple::sword('setting_' . $setting, null, $setting) . '</b><br />';
            $html .= '<i>' . FormSimple::sword('setting_' . $setting . '_sub', null, '') . '</i><div>';
        }
        $html .= '</div>';
        return $html;
    }

    /**
     * Modify a configuration file.
     *
     * Replace old values in a given config file by new values.
     *
     * @param string $file_path the config file path
     * @param string $name the config var name
     * @param array $old_values the values to change
     * @param array $new_values the new values to write
     * @return boolean file edition sucess
     */
    private static function configEdit($file_path, $name, $old_values, $new_values)
    {
        if (file_exists($file_path)) {
            $content = file_get_contents($file_path);
            foreach ($old_values as $setting => $value) {
                $setting = preg_quote($setting);
                if(isset($_POST[$name][$setting])) {
                    $new = $_POST[$name][$setting];
                } else $new = $value;

                if (is_bool($value)) {
                    $pattern = '`(\'' . $setting . '\' => )' . ($value ? 'true' : 'false') . '(,)`';
                    $new = isset($_POST[$name][$setting]) ? 'true' : 'false';
                } elseif (is_string($value)) {
                    $pattern = '`(\'' . $setting . '\' => \')' . preg_quote($value) . '(\',)`';
                } elseif (is_numeric($value)) {
                    $pattern = '`(\'' . $setting . '\' => )' . $value . '(,)`';
                } elseif (is_array($value)) {
                    $pattern = '`(\'' . $setting . '\' => array\(\s*\'selected\' => \')' . preg_quote($value['selected']) . '(\',)`';
                }
                $content = preg_replace($pattern, '${1}' . $new . '$2', $content);
            }

            if ($file = fopen($file_path, 'w')) {
                fwrite($file, $content);
                fclose($file);
                return true;
            } else return false;
        }
    }


    /*
     * GETTERS / SETTERS
     */


    public function id()
    {
        return $this->id;
    }
    public function lang($key = null)
    {
        if($key === null) return $this->lang;
        if(is_string($key)) $this->lang = $key;
    }


    public function fields()
    {
        return $this->fields;
    }
    public function field($id)
    {
        return $this->fields[$id];
    }
    public function addField(Field $field)
    {
        $field->lang($this->lang);
        $this->fields[] = $field;
    }
    public function delField(int $id)
    {
        unset($this->fields[$id]);
    }
    public function delFields()
    {
        $this->fields = array();
    }


    public function params()
    {
        return $this->params;
    }
    public function addParam($str)
    {
        if(is_string($str)) $this->params[] = $str;
    }


    public function visible($val = null)
    {
        if($val === null) return $this->visible;
        if(is_bool($val)) $this->visible = $val;
    }
    public function enabled($val = null)
    {
        if($val === null) return $this->enabled;
        if(is_bool($val)) $this->enabled = $val;
    }
    public function completed($val = null)
    {
        if($val === null) return $this->completed;
        if(is_bool($val)) $this->completed = $val;
    }


    public function message($val = null)
    {
        if($val === null) return $this->message;
        if(is_string($val)) $this->message = $val;
    }
    public function fieldset($val = null)
    {
        if($val === null) return $this->fieldset;
        $this->fieldset = intval($val);
    }
}
/**
 * Unset each $a[array[$i]]
 */
function unsetR($a, $i) {
    foreach($a as $k=>$v) {
        if(isset($v[$i])) {
            unset($a[$k][$i]);
        }
    }
    return $a;
}
