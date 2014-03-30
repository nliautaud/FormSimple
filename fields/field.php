<?php
abstract class Field
{
    private $id;
    private $lang;
    private $error;

    private $type;
    private $title;
    private $value;
    private $required;
    private $placeholder;

    private $hidden;
    private $locked;
    final public function __construct(
        $id,
        $title = '', $value = '', $placeholder = '',
        $required = False, $hidden = False, $locked = False
    ){
        $this->id = $id;
        $this->type = strtolower(get_class($this));
        $this->error = '';

        $this->title = $title;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->hidden = $hidden;
        $this->locked = $locked;
    }
    /**
     * Define field attributes from tag syntax.
	 *
	 * This method is often overwritten by subclasses.
	 *
	 * @see FormSimple::parse_parameters()
     */
    public function construct($punctuation, $title, $content_type, $content)
    {
        $this->title($title);
        $this->value($content_type == '=<' ? '' : $content);
        $this->placeholder($content_type == '=<' ? $content : '');
        $this->locked($content_type == '=>' ? True : False);
        $this->required($punctuation == '!' ? True : False);
        if($punctuation == '?')
        {
            $this->hidden(True);
            $this->locked(True);
        }
    }

    /**
     * Check field value, set field error.
     *
     * Check if field is empty and required or
     * not empty but not valid.
	 *
     * @return bool check status
	 *
	 * @see is_valid
	 * @see is_blacklisted
	 * @see FormSimple::post()
     */
    final public function check_content()
    {
		// empty and required
        if(empty($this->value) && $this->required)
		{
            $this->error('field_required');
            return False;
        }
		// not empty but not valid
        if(
            !empty($this->value) &&
            ($this->is_blacklisted()
            || !$this->is_valid())
        ) {
			// use specific keyword if translation exists, or use generic
			$lang = FormSimple::sword('field_error_' . $this->type(), Null, '');
			if(!empty($lang))
				$this->error('field_error_' . $this->type());
			else
				$this->error('field_invalid');
            return False;
        }
        $this->error('');
        return True;
    }
    /**
     * Check if field value is blacklisted.
     *
     * Search field value in setting filter and return
	 * bool according to the result and the filter type.
	 *
     * @return boolean
	 *
	 * @see check_content
     */
    final public function is_blacklisted()
    {
        if(is_array($this->value) || empty($this->value)) return False;

        $list = FormSimple::setting($this->type . '_field_filter');
        $type = FormSimple::setting($this->type . '_field_filter_type');

		if($list === Null || $type === Null) return False;

        $type = $type['selected'];
        $value = preg_quote($this->value);

        $found = preg_match('`' . $value . '`', $list);

        if($type == 'b') return $found ? True : False;
        else return $found ? False : True;
    }

    /**
     * Define if field value is valid.
	 *
     * Valid mean different things depending on field type :
	 * this method is often overwritten by subclasses.
	 *
     * @return boolean
	 *
	 * @see check_content
     */
    public function is_valid()
    {
        return True;
    }

    /**
     * Return the html display of the field in a prepared <div>.
     *
     * Manage div class and error message.
	 *
     * @return string the <div>
	 *
	 * @see html_content
	 * @see FormSimple::html()
     */
    final public function html()
	{
		$html = '<label class="field ' . $this->type;
		if($this->required) $html .= ' required';
		if($this->locked) $html .= ' disabled';
		if($this->error) $html .= ' error';
		$html .= '"';
		if($this->hidden) $html .= ' style="display:none;"';
		$html .= '>';

		$html .= '<div class="title">';
		$html .= $this->html_title();
		$html .= '</div>';

		$html .= $this->html_content();

		$error = $this->error;
		if(!empty($error))
		{
			$html .= '<span class="error">';
			$html .= FormSimple::sword($error, $this->lang);
			$html .= '</span>';
		}
		$html .= '</label>';
		return $html;
	}

    /**
     * Return the html title of the field.
	 *
     * @return string
	 *
	 * @see html
     */
    public function html_title()
	{
        if(!empty($this->title)) return $this->title;

        return FormSimple::sword('field_title_' . $this->type, $this->lang, $this->type);
	}

    /**
     * Return the html content of the field.
	 *
     * @return string
	 *
	 * @see html
     */
    abstract public function html_content();

    /**
     * Return the field input identifier.
	 *
	 * Used to link label and input.
	 *
     * @return string
	 *
	 * @see html_label
	 * @see html_content
     */
    final protected function html_id()
    {
        return 'FormSimple_field' . $this->id;
    }

    /**
     * Return the field input name.
	 *
     * @return string
	 *
	 * @see html_content
     */
    final protected function html_name()
    {
        return 'FormSimple_fields[' . $this->id . ']';
    }


    /**
     * GETTERS / SETTERS
     */


    final public function lang($lang = Null)
    {
        if($lang === Null) return $this->lang;
        if(is_string($lang)) $this->lang = $lang;
    }

    final public function type($type = Null)
    {
        if($type === Null) return $this->type;
        if(is_string($type)) $this->type = $type;
    }

    final public function title($title = Null)
    {
        if($title === Null) return $this->title;
        if(is_string($title)) $this->title = $title;
    }

    final public function value($value = Null)
    {
        if($value === Null) return $this->value;
        if(is_string($value) || is_array($value))
        {
            $this->value = $value;
        }
    }
    /**
     * Define selected values of the multi-values field.
     * @param mixed $values the value or array of values to select
     */
    final public function value_selection($values)
    {
        if(!is_array($this->value)) return False;

        // given values need to be an array
        if(!is_array($values)) $values = array($values);

        // reset actual values selections
        $tmp_values = $this->value;
        foreach($tmp_values as $key => $val)
            $tmp_values[$key][2] = '';

        // set new values selections
        foreach($values as $value)
        {
            foreach($tmp_values as $key => $val)
            {
                if(trim($val[1]) == trim($value))
                    $tmp_values[$key][2] = 'selected';
            }
        }
        $this->value($tmp_values);
    }
    final public function placeholder($val = Null)
    {
        if($val === Null) return $this->placeholder;
        if(is_string($val)) $this->placeholder = $val;
    }

    final public function required($val = Null)
    {
        if($val === Null) return $this->required;
        if(is_bool($val) || is_string($val))
            $this->required = $val;
    }
    final public function hidden($hidden = Null)
    {
        if($hidden === Null) return $this->hidden;
        if(is_bool($hidden)) $this->hidden = $hidden;
    }
    final public function locked($locked = Null)
    {
        if($locked === Null) return $this->locked;
        if(is_bool($locked)) $this->locked = $locked;
    }

    final public function error($val = Null)
    {
        if($val === Null) return $this->error;
        if(is_string($val)) $this->error = $val;
    }
}
?>