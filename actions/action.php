<?php
/**
 * FormSimple action base class
 *
 * @author Nicolas Liautaud <contact@nliautaud.fr>
 * @package FormSimple
 */
abstract class Action
{
    protected $version;
    protected $form;

    final public function _Construct($form)
    {
        $this->version = '0';
        $this->form = $form;
    }

    /*
     * Action initialisation.
     */
    abstract public function construct();

    /*
     * Execute the action. Called when a Form
     * using this Action is validated.
     * @param Form $form the posted Form
     * @return string the action status
     */
    abstract public function exec();

    /*
     * Return informations about the action error.
     * @return string
     */
    abstract public function htmlDebug();

    final protected function error($keyword)
    {
        $this->form->completed(false);
        $this->form->message($this->word($keyword));
    }

    /*
     * Return the Action version.
     */
    final public function version()
    {
        return $this->version;
    }
    /*
     * Return the Action name.
     */
    final public function name()
    {
        return strtolower(get_class($this));
    }

    /**
     * Return a setting value from action config file.
     * @param string $key the setting key
     * @return mixed the setting value
     */
    public function setting($key)
    {
        $settings = 'FormSimple_' . $this->name() . '_settings';
        global $$settings;
        require_once FSACTIONSPATH . $this->name() . '/config.php';
        $s = $$settings;
        if (isset($s[$key])) {
            return $s[$key];
        }
        else return null;
    }

    /**
     * Return a traduction of the keyword.
     *
     * Manage languages between requested langs and existing traductions.
     * @param string $key the keyword
     * @return string
     */
    final protected function word($key)
    {
        $path = FSACTIONSPATH . $this->name() . '/lang/';
        global $FormSimple_lang;

        $file =  $this->form->lang() . '.php';
        if (!file_exists($path.$file)) {
            $file = 'en.php';
        }
        if (file_exists($path.$file)) {
            require $path.$file;

            if(isset($FormSimple_lang[$key]))
                return $FormSimple_lang[$key];
            else
                return ucfirst(str_replace('_', ' ', $key));
        }
    }
}

