<?php

/**
 * Translation Tool for FormSimple
 * @author Francesco Simone Carta <entuland@gmail.com>
 * @package FormSimple
 */
class FormSimpleTranslator
{

    private static $_formsimple_uri = '';
    private static $_translate_uri = '';
    private static $_gui_lang = 'en';

    /**
     *  GUI entry point, handles both the main panel
     *  as well as viewing and editing single translation files
     * 
     * @param string $gui_lang The language to use for the translation interface
     * @param string $uri The URI this tool should use to go back to the caller;
     *                    if omitted, it will default to $_SERVER['REQUEST_URI']
     *                    This tool is designed to consider the caller URI
     *                    and its own URI as differing only by a 'translate' GET
     *                    parameter. For instance, if this function resolves
     *                    "$uri === '/some/path?foo&translate'"
     *                    then '/some/path?foo' will be considered as the
     *                    caller URI (used to exit from this tool's interface)
     */
    static public function gui($gui_lang = null, $uri = null) {

        if (!empty($gui_lang)) {
            self::$_gui_lang = $gui_lang;
        }

        if (empty($uri)) {
            $uri = $_SERVER['REQUEST_URI'];
        }

        // unset some GET values to get the URI pointing to the caller point
        $get = $_GET;
        unset($get['translate']);
        unset($get['rollback']);

        // get rid of the query string, if any
        $pos_get = strpos($uri, '?');
        if ($pos_get) {
            $uri = substr($uri, 0, $pos_get);
        }

        // rebuild the query string
        $query = '';
        $translate = '?translate';
        if (count($get)) {
            $query = '?' . http_build_query($get, '', '&amp;');
            $translate = $query . '&amp;translate';
        }

        // store the caller's URI and the tool's own URI
        self::$_formsimple_uri = $uri . $query;
        self::$_translate_uri = $uri . $translate;

        $check = array('folder_key', 'from_lang', 'to_lang');

        // show the main context-selection form if that's the case
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            self::_main();
            return;
        }

        $post = $_POST;
        // loop over needed POST parameters and...
        foreach ($check as $name) {
            // ...store each param as a local variable
            $$name = empty($post[$name]) ? '' : trim($post[$name]);
            // ...and unset them from the array
            unset($post[$name]);
        }

        // bail out if a forged path has been passed
        if (!self::_isValidFolder($folder_key)) {
            self::_main();
            return;
        }

        // display the editing form for this folder / languages combo
        self::_edit($folder_key, $from_lang, $to_lang, $post);
    }

    /**
     * Check if $folder_key is a valid key for _validFolders()
     * 
     * @param string $folder_key The key to be checked
     * @return boolean
     */
    private static function _isValidFolder($folder_key) {
        return array_key_exists($folder_key, self::_validFolders());
    }

    /**
     * Displays the available translatable folders
     */
    private static function _main() {
        echo '<h2>' . self::_('context_page_title') . '</h2>';
        if (isset($_GET['rollback'])) {
            self::_rollback($_GET['rollback']);
        }
        ?>
        <p><?php echo self::_('back_to') ?>:
            <a href="<?php echo self::$_formsimple_uri ?>">
                <?php echo self::_('formsimple_settings') ?>
            </a>
        </p>
        <?php
        $valid_folder_keys = array_keys(self::_validFolders());
        foreach ($valid_folder_keys as $folder_key) {
            echo self::_invocationForm($folder_key);
        }
    }

    /**
     * Returns a form for invoking the edit of an available folder
     * with the available languages
     * 
     * @param string $folder_key A valid key for the array returned by _validFolders()
     * @return string The HTML markup for the edit form
     */
    private static function _invocationForm($folder_key) {
        ob_start();

        $context = self::_getContext($folder_key);

        $valid_folders = self::_validFolders();
        $folder = $valid_folders[$folder_key];
        $translations_stats = self::_translationsStats($folder);

        // prepare the options to be used in the invocation form selects
        $lang_options = '';
        foreach ($translations_stats as $code => $stats) {
            $selected = '';
            if ($code === 'en') {
                $selected = 'selected';
            }
            $text = self::_('language_' . $code) . ', '
                    . $stats['count'] . ' ' . self::_('strings') . ', '
                    . round($stats['percent'], 2) . '%';
            $lang_options .= <<<HTML
            <option value="$code" $selected>
                $text
            </option>
HTML;
        }

        // print out the actual form
        ?>
        <form action="<?php echo self::$_translate_uri ?>" method="POST">
            <input type="hidden" name="folder_key" value="<?php echo $folder_key ?>" >
            <table style="width: 100%">
                <tr>
                    <th>
                        <?php echo self::_('context') ?>
                    </th>
                    <th>
                        <?php echo self::_('reference_language') ?>
                    </th>
                    <th>
                        <?php echo self::_('target_language') ?>
                    </th>
                    <th>

                    </th>
                </tr>
                <tr>
                    <td style="width: 30%">
                        <?php echo $context ?>
                    </td>
                    <td style="width: 30%">
                        <select name="from_lang" style="width: 100%">
                            <?php echo $lang_options ?>
                        </select>
                    </td>
                    <td style="width: 30%">
                        <select name="to_lang" style="width: 100%">
                            <?php echo $lang_options ?>
                            <option value='new'>
                                <?php echo self::_('new_language') ?>
                            </option>
                        </select>
                    </td>
                    <td style="width: 10%">
                        <input type="submit" value="<?php echo self::_('translate') ?>">
                    </td>
                </tr>
            </table>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Extracts human readable, translated version of the context from the
     * context path
     * 
     * @param string $folder_key A key aligned with the results of _validFolders()
     * @return string The human readable, translated version of the context
     */
    private static function _getContext($folder_key) {
        $type = dirname($folder_key);
        $item = basename($folder_key);
        // extract the base folder type (none, actions or tools)
        // and return an appropriate translation accordingly
        if ($type === '.') {
            return $item . ' (' . self::_('main_interface') . ')';
        }
        return $item . ' (' . self::_($type) . ')';
    }

    /**
     * Prepares the list of paths available for translation
     * 
     * @return array Array of valid paths with this format:
     *      array(
     *          'FormSimple' => 'path/to/FormSimple/lang',
     *          'actions/sendmail' => 'path/to/FormSimple/actions/sendmail/lang',
     *          'tools/translator' => 'path/to/FormSimple/tools/translator/lang'
     *      );
     */
    private static function _validFolders() {
        $folders = array();

        $base_folder = FSPATH;
        if (!preg_match('#/$#', $base_folder)) {
            $base_folder .= '/';
        }

        // add first entry to translate FormSimple
        $folders[basename($base_folder)] = $base_folder . 'lang';

        $actions = self::_validSubfolders($base_folder . 'actions');
        $tools = self::_validSubfolders($base_folder . 'tools');

        return array_merge($folders, $actions, $tools);
    }

    /**
     * Helper function for _validFolders()
     * 
     * @param string $basefolder The folder to look into for sub/lang folders
     * @return array Array with the same format of _validFolders() result
     * @see _validFolders()
     */
    private static function _validSubfolders($base_folder) {
        $folders = array();

        // extracts 'actions' from '/path/to/FormSimple/actions'
        $type = basename($base_folder);

        // get subfolders and check for contained '/lang' folders
        $subfolders = scandir($base_folder);
        foreach ($subfolders as $sub) {
            $real_folder = $base_folder . '/' . $sub . '/lang';
            if ($sub[0] === '.' || !is_dir($real_folder)) {
                continue;
            }
            $folders[$type . '/' . $sub] = $real_folder;
        }
        return $folders;
    }

    /**
     * Get all translations of a subfolder
     * 
     * @param string $folder The real path to scan for xx.php language files
     * @param boolean $refresh Force reloading from disk, bypass cache
     * @return array An array containing the translation arrays keyed after 
     *               each language code (just like the actual language files)
     */
    static private function _translations($folder, $refresh = false) {
        static $cache = array();
        if (!array_key_exists($folder, $cache) || $refresh) {
            $files = scandir($folder);
            $result = array();
            foreach ($files as $file) {
                $filename = $folder . '/' . $file;
                if (!preg_match('#\.php$#i', $file) || !is_file($filename)) {
                    continue;
                }
                $langcode = basename(strtolower($file), '.php');

                // unset every time and check after the inclusion
                // to make sure we have a valid language file
                unset($FormSimple_lang);
                include $filename;
                if (isset($FormSimple_lang) && is_array($FormSimple_lang)) {
                    $result[$langcode] = $FormSimple_lang;
                }
            }
            $cache[$folder] = $result;
        }
        return $cache[$folder];
    }

    /**
     * Returns a list of available translations for a given folder
     * along with the percentage of completeness of every translation
     * 
     * Translations completeness is checked against English
     * 
     * @param string $folder Folder to scan for xx.php language files
     * @param boolean $refresh Force reloading from disk, bypass cache
     * @return array An array keyed on langcodes pointing to the completeness
     *               and size of the translation
     */
    static private function _translationsStats($folder, $refresh = false) {
        $translations = self::_translations($folder, $refresh);
        $result = array();
        $base = $translations['en'];
        $base_count = count($base);
        foreach ($translations as $code => $strings) {
            $this_count = count($strings);
            $percent = 100 / $base_count * $this_count;
            $result[$code] = array(
                'count' => $this_count,
                'percent' => $percent,
            );
        }
        return $result;
    }

    /**
     * Get a translation for the translator itself
     * 
     * @param string $string_key The key to look for
     * @return string The corresponding translation or the key itself
     */
    private static function _($string_key) {
        static $cache = null;
        if (!is_array($cache)) {
            $langpath = dirname(__FILE__) . '/lang/';
            $langfile = $langpath . self::$_gui_lang . '.php';
            if (!is_file($langfile)) {
                $langfile = $langpath . 'en.php';
            }
            if (is_file($langfile)) {
                include $langfile;
                $cache = $FormSimple_lang;
            } else {
                $cache = array();
            }
        }
        if (array_key_exists($string_key, $cache)) {
            return $cache[$string_key];
        }
        // return an ugly, overly long string on purpose
        // to ease spotting the missing translation
        return '{FormSimple/tools/translator/' . $string_key . '}';
    }

    /**
     * Displays the editing form for the chosen folder
     * 
     * @param string $folder_key A valid key for the _validFolders() array
     * @param string $from_lang The language for the original strings
     * @param string $to_lang The language currently under edit
     * @param array $post The array with the edited strings, if any
     * @param array $context The context of this translation (already translated
     *                       text here, please)
     */
    private static function _edit($folder_key, $from_lang, $to_lang, $post) {

        $valid_folders = self::_validFolders();
        $folder = $valid_folders[$folder_key];
        $translations = self::_translations($folder);

        if ($to_lang === 'new') {
            $to_lang = '';
        }

        // verify if any data has been posted, for starters,
        // and refresh the translations if needed
        if (self::_checkEditPost($folder, $to_lang, $post, $translations)) {
            $translations = self::_translations($folder, true);
            $post = array(); // not needed anymore in case of a successful post
        }

        $inputs = self::_inputsMarkup($from_lang, $to_lang, $post, $translations);
        $additional_inputs = self::_additionalInputsMarkup($post);

        $context = self::_getContext($folder_key);
        ?>
        <h2><?php echo self::_('edit_page_title') ?></h2>
        <p><?php echo self::_('current_context') ?>:
            <strong><?php echo $context ?></strong>
        </p>
        <p><?php echo self::_('back_to') ?>:
            <a href = "<?php echo self::$_formsimple_uri ?>"
               ><?php echo self::_('formsimple_settings') ?></a>
            -
            <a href="<?php echo self::$_translate_uri ?>"
               ><?php echo self::_('context_choice') ?></a>
        </p>
        <form action="<?php echo self::$_translate_uri ?>" method="POST">
            <input type="hidden" name="folder_key" 
                   value="<?php echo $folder_key ?>">
            <input type="hidden" name="from_lang" 
                   value="<?php echo $from_lang ?>">
            <table>
                <thead>
                    <tr>
                        <th>
                            <?php echo self::_('key') ?>
                        </th>
                        <th>
                            <?php
                            echo self::_('reference_language');
                            echo ' (' . self::_('language_' . $from_lang) . ')';
                            ?>
                        </th>
                        <th>
                            <?php
                            echo self::_('target_langcode');
                            ?><br>
                            <input type="text" 
                                   name="to_lang" 
                                   value="<?php echo $to_lang ?>" >
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $inputs ?>
                    <?php echo $additional_inputs ?>
                </tbody>
            </table>
            <input type="submit" value="<?php echo self::_('submit') ?>">
            <?php echo self::_('or') ?>
            <a href="<?php echo self::$_translate_uri ?>">
                <?php echo self::_('cancel') ?>
            </a>
        </form>
        <?php
    }

    /**
     * If any data has been posted to the edit form, then process it
     * 
     * @param string $folder The real folder path under edit
     * @param string $to_lang The language currently under edit
     * @param array $strings The array with the edited strings, if any
     * @param array $translations The array with all the available strings
     */
    private static function _checkEditPost($folder, $to_lang, $post,
                                           $translations) {

        $strings = array();
        if (array_key_exists('string', $post)) {
            $strings = $post['string'];
            // make sure no empty key makes it to the final language file
            unset($strings['']);
        }

        $new = array();
        if (array_key_exists('new', $post)) {
            $new = array_combine($post['new']['key'], $post['new']['string']);
            // make sure no empty key makes it to the final language file
            unset($new['']);
        }

        // bail out without errors if no data has been posted
        if (empty($strings) && empty($new)) {
            return false;
        }

        $to_lang = basename($to_lang);
        if (empty($to_lang)) {
            echo self::_message(self::_('provide_langcode'), 'error');
            return false;
        }

        $filename = $folder . '/' . $to_lang . '.php';

        $fileintro = "<?php\n\n\$FormSimple_lang = ";

        // proceed to backing up the original file
        // to allow for rollback
        if (is_file($filename)) {
            $filecontent = file_get_contents($filename);
            if (preg_match('#.*\$FormSimple_lang#s', $filecontent, $matches)) {
                $fileintro = $matches[0] . ' = ';
            }
            if (!file_put_contents($filename . '.bck', $filecontent)) {
                echo self::_message(self::_('backup_failed'), 'error');
                return false;
            }
            $rollback = $filename;
        }

        $base = array();

        if (array_key_exists($to_lang, $translations)) {
            $base = $translations[$to_lang];
        }

        foreach ($strings as $string_key => $string) {
            $string = trim($string);
            if (empty($string)) {
                unset($base[$string_key]);
                unset($strings[$string_key]);
            }
        }

        $merged = array_merge($base, $strings, $new);

        $new_filecontent = $fileintro . var_export($merged, true) . ";\n";

        if (!file_put_contents($filename, $new_filecontent)) {
            echo self::_message(self::_('save_failed'), 'error');
            return false;
        }

        // the actual rollback function is invoked by self::_main()
        if (isset($rollback)) {
            echo self::_message(
                    self::_('save_success')
                    . ' - <a href="' . self::$_translate_uri . '&rollback='
                    . $rollback . '">'
                    . self::_('rollback')
                    . '</a>'
            );
        } else {
            echo self::_message(self::_('save_success'));
        }
        return true;
    }

    /**
     * Processes the rollback request
     * 
     * @param string $current The full filename to be overwritten with its 
     *                        backup, for instance '/path/to/file.php', which
     *                        will be overwritten with the contents of 
     *                        '/path/to/file.php.bck', if existing
     */
    private static function _rollback($current) {
        $backup = $current . '.bck';

        $details = self::_('rollback_current') . ':<br>'
                . '<kbd>[' . $current . ']</kbd><br>'
                . self::_('rollback_backup') . ':<br>'
                . '<kbd>[' . $backup . ']</kbd>';

        try {
            if (!is_file($backup)) {
                throw new Exception('rollback_backup_not_file');
            }
            $backup_content = file_get_contents($backup);
            if (empty($backup_content)) {
                throw new Exception('rollback_backup_reading_failure');
            }
            if (!file_put_contents($current, $backup_content)) {
                throw new Exception('rollback_current_overwrite_failure');
            }
            if (!unlink($backup)) {
                throw new Exception('rollback_backup_deletion_failure');
            }
            echo self::_message(
                    self::_('rollback_success') . '<br>' . $details
            );
        } catch (Exception $ex) {
            $msg = self::_('rollback_failure') . '<br>'
                    . $details . '<br>'
                    . self::_('error') . ': '
                    . self::_($ex->getMessage());
            echo self::_message($msg, 'error');
        }
    }

    /**
     * Prepares the input elements for the editing of a language file
     * 
     * @param string $from_lang The reference language
     * @param string $to_lang The destination language
     * @param array $post The array with the posted strings (if any)
     * @param array $translations The array with all the available strings
     * @return string The resulting HTML markup with all the input elements in
     *                a table
     */
    private static function _inputsMarkup($from_lang, $to_lang, $post,
                                          $translations) {
        $from_array = $translations[$from_lang];

        if (empty($to_lang)) {
            $to_array = array();
        } else {
            $to_array = $translations[$to_lang];
        }

        // extract all keys from both languages to ensure that no key gets lost
        // in case of misaligned translation files
        $string_keys = array_unique(
                array_merge(
                        array_keys($from_array), array_keys($to_array)
                )
        );

        // sort them because they're very likely messed up in any case
        sort($string_keys);

        ob_start();
        foreach ($string_keys as $string_key) {
            $from_string = '';
            if (array_key_exists($string_key, $from_array)) {
                $from_string = htmlspecialchars($from_array[$string_key]);
            }
            $to_string = '';
            if (array_key_exists($string_key, $to_array)) {
                $to_string = htmlspecialchars($to_array[$string_key]);
            }

            // if present, override $to_string with the data in the POST array
            // to allow for reposting in case of a save failure
            if (isset($post['string'][$string_key])) {
                $to_string = htmlspecialchars($post['string'][$string_key]);
            }
            ?>
            <tr>
                <td style="width: 20%">
                    <var><?php echo $string_key ?></var>
                </td>
                <td style="width: 40%">
                    <?php echo $from_string ?>
                </td>
                <td style="width: 40%">
                    <textarea style="width: 100%; height: 2em;"
                              name="string[<?php echo $string_key ?>]"
                              ><?php echo $to_string ?></textarea>
                </td>
            </tr>
            <?php
        }
        return ob_get_clean();
    }

    /**
     * Prepares the additional input elements to allow for string addition
     * 
     * @param array $post The array with the posted strings (if any)
     * @return string The resulting HTML markup with all the additional input 
     *                elements in a table
     */
    private static function _additionalInputsMarkup($post) {
        ob_start();
        for ($i = 0; $i < 5; ++$i) {
            $string = '';
            $string_key = '';
            // if present, fill $string and $string_key with the data in the 
            // POST array to allow for reposting in case of a save failure
            if (isset($post['new']['string'][$i])) {
                $string = htmlspecialchars($post['new']['string'][$i]);
            }
            if (isset($post['new']['key'][$i])) {
                $string_key = htmlspecialchars($post['new']['key'][$i]);
            }
            ?>
            <tr>
                <td style="width: 20%">
                    <input type="text" name="new[key][]" 
                           value="<?php echo $string_key ?>">
                </td>
                <td style="width: 40%; text-align: center">
                    <strong>&lt;&lt;&lt;</strong> <?php echo self::_('new_key') ?> |
                    <?php echo self::_('new_string') ?> <strong>&gt;&gt;&gt;</strong>
                </td>
                <td style="width: 40%">
                    <textarea style="width: 100%; height: 2em;"
                              name="new[string][]"><?php echo $string ?></textarea>
                </td>
            </tr>
            <?php
        }
        return ob_get_clean();
    }

    /**
     * Wraps a message in a DIV tag with a default 'updated' class
     * 
     * @return string The formatted HTML message
     */
    private static function _message($message, $class = 'updated') {
        if (empty($message)) {
            return '';
        }
        return '<div class="' . $class . '">' . $message . '</div>';
    }

}
