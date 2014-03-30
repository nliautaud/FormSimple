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
    /*
     * FormSimple version.
     */
    public static function version() {return '1.0';}

    /**
     * INITIALISATION
     */


    public function __construct()
    {
        FormSimple::define_constants();

        $this->define_id();
        $this->lang = FSLANG;

        $this->fields = array();
        $this->params = array();

        $this->visible = True;
        $this->enabled = True;
        $this->completed = False;

        $this->message = '';
        $this->fieldset = -1;
    }

    /*
     * Define the paths and url constants.
     */
    private static function define_constants()
    {
        $dir = dirname(__FILE__) . '/';
        $const = array(
            'path' => $dir,
            'url' => '',
            'langs' => '',
            'actionspath' => $dir . 'actions/',
            'fieldspath' => $dir . 'fields/',
            'langspath' => $dir . 'lang/',
            'configpath' => $dir . 'config.php',

            'docurl' =>  'http://nliautaud.fr/wiki/travaux/FormSimple',
            'downurl' =>  'http://get-simple.info/extend/plugin/FormSimple/35',
            'forumurl' =>  'http://get-simple.info/forum/topic/1108',
            'versionurl' =>  'http://get-simple.info/api/extend/?id=35',
        );
        foreach($const as $key => $val)
        {
            $key = strtoupper('FS' . $key);
            if(!defined($key)) define($key, $val);
        }
    }

    /*
     * Define the form ID according
     * to forms count in page.
     */
    private function define_id()
    {
        global $FSCOUNT;
        if(!isset($FSCOUNT))
            $FSCOUNT = 0;
        else $FSCOUNT++;
        $this->id = $FSCOUNT;
    }

    /**
     * OUTPUT
     */


    /*
     * Return the html display of the form
     * @return string the <form>
     */
    public function html()
    {
        $html = '<div class="FormSimple" id="FormSimple' . $this->id . '">';

        // debug
        if(FormSimple::setting('debug')) $html .= $this->html_debug();

		// message
        $html .= $this->html_message(
			$this->word($this->message),
			$this->completed ? 'confirm' : 'error');

		// form
        if(FormSimple::setting('visible') && $this->visible)
		{
			$html .= '<form method="post" ';
			$html .= 'action="#FormSimple' . $this->id . '" ';
			$html .= 'class="' . $this->action->name() . '" ';
			$html .= 'autocomplete="off" >';

			foreach($this->fields as $id => $field)
			{
				// field
				if($field->type() != 'fieldset')
				{
					$html .= $field->html();
				}
				// fieldset
				else
				{
					if($this->fieldset() > 0) $html .= '</fieldset>';
					$html .= '<fieldset>';
					if($field->title())
						$html .= '<legend>' . $field->title() . '</legend>';
					$this->fieldset = $field->value();
				}
				if($this->fieldset == 0) $html.= '</fieldset>';
				if($this->fieldset > -1) $this->fieldset--;
			}
			// id, token and submit
			$html .= '<div class="field submit">';
			$html .= '<input type="hidden" name="FormSimple_form[id]" value="' . $this->id . '" />';
			$html .= '<input type="hidden" name="FormSimple_form[token]" value="' . $this->token_get() . '" />';
			$html .= '<input type="submit" value="' . $this->word('btn_send') . '" /></div>';
			$html .= '</form>';
		}
		$html .= '</div>';

        return $html;
    }

    /*
     * Return an html display of a message.
     * @return string the <div>
     */
    private function html_message($message, $class = 'error')
    {
        if(empty($message)) return '';
        $msg = '<div class="alert ' . $class . '">';
		$msg .= $message;
		$msg .= '</div>';
		return $msg;
    }

    /*
     * Return an html display of a box report.
     * @return string the <div>
     */
    private static function html_report($title, $content)
    {
        $html = '<h4 style="color:#c33">' . $title . '</h4>';
        $html .= '<pre class="debug">' . $content . '</pre>';
        return $html;
    }

    /**
     * Return debug infos : SESSION, POST and FormSimple Object.
     */
    public function html_debug()
    {
        // store debug infos
        ini_set('display_errors', 'on');
        error_reporting(E_ALL);
        ob_start();

        if(!empty($_SESSION))
        {
            echo'Session ';
            print_r($_SESSION);
        }
        if(!empty($_POST['FormSimple_form'])
        && $_POST['FormSimple_form']['id'] == $this->id)
        {
            echo'Post ';
            print_r($_POST);
        }
        print_r($this);
        $debug = ob_get_clean();

        // debug layout
        $output = FormSimple::html_report('FormSimple #' . $this->id . ' debug', $debug);
        if($this->completed)
            $output .= FormSimple::html_report('FormSimple #' . $this->id . ' action debug', $this->action->html_debug());
        $output .= '<h4 style="color:#c33">FormSimple #' . $this->id . '</h4>';

        return $output;
    }


    /**
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
        if(!empty($_POST['FormSimple_form']) &&
            $_POST['FormSimple_form']['id'] == $this->id)
        {
            // Update and check fields values
            foreach($this->format_data($_POST['FormSimple_fields']) as $field_id => $field_post)
            {
                $field = $this->field($field_id);

                // Define field value or values selections
                if(is_array($field->value()))
                {
                    $field->value_selection($field_post);
                }
                else $field->value($field_post);

                if(!$field->check_content())
                {
                    $errors = True;
                }
            }
            // Security tokens
            if(FormSimple::setting('enable_token') && !$this->token_compare())
            {
                $this->message('form_error_token');
                $this->visible(False);
            }
            elseif(isset($errors))
            {
				$this->message('form_error_fields');
			}
            else // Action
            {
                // FormSimple is disabled
                if(!FormSimple::setting('enable') && $this->enable)
                {
                    $this->message('form_error_disabled');
                }
                // No action is defined
                elseif($this->action() == Null)
                {
                    $this->message('form_error_action');
                }
                // Execute action
                else
                {
					$this->completed(True);
                    $this->action->exec();
                }
            }
            $this->token_set();
        }
    }

    /**
     * Define or return the action.
     *
     * If an action name is given search file and
     * instanciate action class as form action.
     * @param string $class
     */
    public function action($class = Null)
    {
        if($class === Null) return $this->action;

        $file = FSACTIONSPATH . $class . '/' . $class . '.php';
        if(file_exists($file))
        {
            require_once($file);
            if(class_exists($class))
                $this->action = new $class($this);
            else
                $this->error = 'the action class "'.$class.'" does not exist.';
        }
        else $this->error = 'the action file "'.$class.'.php" does not exist.';
    }

    /**
     * Return the list of existing actions.
	 *
     * @return array
     */
    public static function actions_list()
    {
        $actions = array();
        if($handle = opendir(FSACTIONSPATH))
        {
            while(false !== ($dir = readdir($handle)))
            {
                if($dir[0] != '.'
                && is_dir(FSACTIONSPATH . $dir)
                && file_exists(FSACTIONSPATH . $dir . '/' . $dir . '.php'))
                {
                    $actions[] = $dir;
                }
            }
            closedir($handle);
        }
        return $actions;
    }

    /**
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

        if(empty($this->lang))
        {
			if(!defined('FSLANG'))
			{
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
		$lang = Null,
		$default = Null,
		$langs_path = FSLANGSPATH
	){
		if(empty($key)) return '';

		// use setting defined language if not given
        if($lang === Null)
        {
            $langs = FormSimple::setting('langs');
            $lang = $langs['selected'];
		}

		// use english file if translation file does not exist
        $path = $langs_path . $lang . '.php';
        if(!file_exists($path))
		{
            $path = $langs_path . 'en.php';
		}

        global $FormSimple_lang;
        include_once $path;

		// return translation if exists
        if(isset($FormSimple_lang[$key]))
		{
            return $FormSimple_lang[$key];
		}

		// return formated default value if exists
		if($default !== Null)
		{
            return ucfirst(str_replace('_', ' ', $default));
		}

		// finally, return formated key
		return ucfirst(str_replace('_', ' ', $key));
    }

    /**
     * TOKENS
     */


    /*
     * Create an unique hash in SESSION.
     */
    private function token_set()
    {
        $_SESSION['FormSimple_token'] = uniqid(md5(microtime()), True);
    }
    /*
     * Get the token in SESSION.
	 *
     * @return string
     */
    private function token_get()
    {
        if(!isset($_SESSION['FormSimple_token']))
            $this->token_set();
        return $_SESSION['FormSimple_token'];
    }
    /*
     * Compare the POST token to the SESSION one.
	 *
     * @return boolean
     */
    private function token_compare()
    {
        if(isset($_POST['FormSimple_form']['token'])
        && $this->token_get() === $_POST['FormSimple_form']['token'])
            return True;
        else return False;
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
    private function format_data($data)
    {
        if(is_array($data))
            foreach($data as $key => $val)
                $data[$key] = $this->format_data($val);
        else {
            $data = stripslashes($data);
            $data = htmlentities($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }

    /*
     * Reset all fields values and errors
     */
    public function reset()
    {
        foreach($this->fields as $field)
        {
            $field->value('');
            $field->error('');
        }
    }

    /**
     * Check if a given field class exists,
     * optionally after including the class file.
     * @param string $className the field class name
     * @param boolean $include include class if possible
     * @return boolean
     */
    public static function is_field_class($className, $include = False)
    {
        $file = FSFIELDSPATH . $className . '.php';
        if($include && file_exists($file)) require_once $file;
        if(class_exists($className)) return True;
        return False;
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

        foreach($tags as $tag)
        {
            // create form
            $form = new FormSimple();
            $form->action($tag[1]);
            $form->parse_parameters(isset($tag[2]) ? $tag[2] : '');

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
    public function parse_parameters($parameters, $separator = ',')
    {
		if($parameters === Null) return False;

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
        foreach($parameters as $p)
        {
            if(!FormSimple::is_field_class($p[1], True))
            {
                $this->add_param($p[1]);
            }
            else $fieldsnbr++;
        }
        // use action default fields if there is no fields in parameters
        if($fieldsnbr == 0 && $this->action != Null)
        {
            $default = $this->action->setting('default_params');
            $this->parse_parameters($default);
        }
        // create and add fields
        $id = 0;
        foreach($parameters as $p)
        {
            $class = $p[1];
            if(FormSimple::is_field_class($class, True))
            {
                $field = new $class($id);
                $field->construct($p[2], $p[3], $p[4], $p[5]);
                $this->add_field($field);
                $id++;
            }
        }
		return True;
    }

    /**
     * VERSION - UPDATE
     */


    /**
     * Return the last version of FormSimple in GS.
	 *
     * @return string
     */
    public static function last_version()
    {
        $apiback = file_get_contents(FSVERSIONURL);
        $response = json_decode($apiback);
        if($response->status == 'successful')
			return $response->version;
    }
    /**
     * Check if a new version exists.
	 *
     * @return mixed new version number if exists, or False.
     */
    public static function exists_new_version()
    {
        $actual = explode('.', FormSimple::version());
        $last = FormSimple::last_version();
        $last_r = explode('.', $last);
        foreach($actual as $key => $val)
		{
            if(isset($last_r[$key]))
            {
                if($val < $last_r[$key]) return $last;
                if($val > $last_r[$key]) return False;
            }
		}
        return False;
    }
    /**
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
			FormSimple::define_constants();

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
        FormSimple::define_constants();

        $head = '';
        if(!file_exists(FSCONFIGPATH))
        {
            $head .= '<div class="error">';
            $head .= FormSimple::sword('error_file_open');
            $head .= '<pre>' . FSCONFIGPATH . '</pre></div>';
            return;
        }
        $head .= '<h2>' . FormSimple::sword('config_title') . '</h2>';

        // Update
        if($newversion = FormSimple::exists_new_version())
        {
            $head .= '<div class="updated">' . FormSimple::sword('update_alert');
            $head .= '<br /><a href="' . FSDOWNURL . '">';
            $head .= FormSimple::sword('update_download') . ' (' . $newversion . ')</a></div>';
        }
        // Links
        $head .= '<p><a href="' . FSDOCURL . '">' . FormSimple::sword('documentation') . '</a>';
        $head .= ' - <a href="' . FSFORUMURL . '">' . FormSimple::sword('forum') . '</a></p>';


        // Main settings
        $forms = FormSimple::config_form();
        $toc = '<li><a href="#FormSimple">FormSimple</a></li>';
        // Actions settings
        foreach(FormSimple::actions_list() as $action)
        {
            $form = FormSimple::config_form($action);
            if(!empty($form))
            {
                $forms .= $form;
                $toc .= '<li><a href="#'. $action . '">' . ucfirst($action) . '</a></li>';
            }
        }
        // Table of contents
        if(!empty($toc))
            $toc = FormSimple::sword('table_of_contents') . '<ul>' . $toc . '</ul>';

        echo $head . $toc . $forms;
    }

    /**
     * Return a configuration form capable to manage the
     * FormSimple settings or a given action settings.
	 *
     * @param string $action an action name. FormSimple config by default.
     * @return string the form
     */
    private static function config_form($action = '')
    {
        if(!empty($action))
        {
            $name = 'FormSimple_' . $action . '_settings';
            $file = FSACTIONSPATH . $action . '/config.php';
        }
        else
        {
            $action = 'FormSimple';
            $name = 'FormSimple_settings';
            $file = FSCONFIGPATH;
        }
        if(!file_exists($file)) return '';

        global $$name;
        require_once $file;

        $html = '<h3 id="' . $action . '" style="margin-top:30px;">' . ucfirst($action) . '</h3>';

        // POST

        if(isset($_POST[$name]))
        {
            if(FormSimple::config_edit($file, $name, $$name, $_POST[$name]))
            {
                $html .= '<div class="updated">';
                $html .= FormSimple::sword('config_updated');
                $html .= '</div>';
                require $file;
            }
            else
            {
                $html .= '<div class="error">';
                $html .= FormSimple::sword('error_file_modify');
                $html .= '<pre>' . $file_path . '</pre></div>';
            }
        }

        // DISPLAY

        $html .= '<form action="#' . $action . '" method="post">';
        foreach($$name as $setting => $value)
        {
            $html .= '<div style="margin:10px 0;">';
            $html .= FormSimple::config_field($name, $setting, $value);
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
    private static function config_field($name, $setting, $value)
    {
        if(is_bool($value))
        {
            $html = '<input type="checkbox" name="' . $name . '[' . $setting . ']" ';
            $html .= $value ? 'checked="checked" ' : '';
            $html .= 'style="float:right;" />';
        }
        elseif(is_string($value))
        {
            $html = '<b>' . FormSimple::sword('setting_' . $setting, Null, $setting) . '</b><br />';
            $html .= '<i>' . FormSimple::sword('setting_' . $setting . '_sub', Null, '') . '</i><div>';
            $html .= '<textarea name="' . $name . '[' . $setting . ']" style="width:100%;height:40px">';
            $html .= $value . '</textarea>';
        }
        elseif(is_numeric($value))
        {
            $html = '<input type="text" name="' . $name . '[' . $setting . ']" ';
            $html .= 'value="' . $value . '" style="float:right;" />';
        }
        elseif(is_array($value))
        {
            $html = '<select name="' . $name . '[' . $setting . ']" style="float:right;">';
            foreach($value['values'] as $key => $val)
            {
                $html .= '<option value="' . $key . '" ';
                if($value['selected'] == $key) $html .= 'selected="selected" ';
                $html .= '/>' . $val . '</option>';
            }
            $html .= '</select>';
        }
        // Title after field because of float:right
        if(!is_string($value))
        {
            $html .= '<b>' . FormSimple::sword('setting_' . $setting, Null, $setting) . '</b><br />';
            $html .= '<i>' . FormSimple::sword('setting_' . $setting . '_sub', Null, '') . '</i><div>';
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
    private static function config_edit($file_path, $name, $old_values, $new_values)
    {
        if(file_exists($file_path))
        {
            $content = file_get_contents($file_path);
            foreach($old_values as $setting => $value)
            {
                $setting = preg_quote($setting);
                if(isset($_POST[$name][$setting]))
                    $new = $_POST[$name][$setting];
                else $new = $value;

                if(is_bool($value))
                {
                    $pattern = '`(\'' . $setting . '\' => )' . ($value ? 'True' : 'False') . '(,)`';
                    $new = isset($_POST[$name][$setting]) ? 'True' : 'False';
                }
                elseif(is_string($value))
                {
                    $pattern = '`(\'' . $setting . '\' => \')' . preg_quote($value) . '(\',)`';
                }
                elseif(is_numeric($value))
                {
                    $pattern = '`(\'' . $setting . '\' => )' . $value . '(,)`';
                }
                elseif(is_array($value))
                {
                    $pattern = '`(\'' . $setting . '\' => array\(\s*\'selected\' => \')' . preg_quote($value['selected']) . '(\',)`';
                }
                $content = preg_replace($pattern, '${1}' . $new . '$2', $content);
            }

            if($file = fopen($file_path, 'w'))
            {
                fwrite($file, $content);
                fclose($file);
                return True;
            }
            else return False;
        }
    }


    /**
     * GETTERS / SETTERS
     */


    public function id()
    {
        return $this->id;
    }
    public function lang($key = Null)
    {
        if($key === Null) return $this->lang;
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
    public function add_field(Field $field)
    {
        $field->lang($this->lang);
        $this->fields[] = $field;
    }
    public function del_field(int $id)
    {
        unset($this->fields[$id]);
    }
    public function del_fields()
    {
		$this->fields = array();
    }


    public function params()
    {
        return $this->params;
    }
    public function add_param($str)
    {
        if(is_string($str)) $this->params[] = $str;
    }


    public function visible($val = Null)
    {
        if($val === Null) return $this->visible;
		if(is_bool($val)) $this->visible = $val;
    }
    public function enabled($val = Null)
    {
        if($val === Null) return $this->enabled;
		if(is_bool($val)) $this->enabled = $val;
    }
    public function completed($val = Null)
    {
        if($val === Null) return $this->completed;
        if(is_bool($val)) $this->completed = $val;
    }


    public function message($val = Null)
    {
        if($val === Null) return $this->message;
        if(is_string($val)) $this->message = $val;
    }
    public function fieldset($val = Null)
    {
        if($val === Null) return $this->fieldset;
        $this->fieldset = intval($val);
    }
}
/**
 * Unset each $a[array[$i]]
 */
function unset_r($a, $i) {
    foreach($a as $k=>$v)
        if(isset($v[$i]))
            unset($a[$k][$i]);
    return $a;
}
?>