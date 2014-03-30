<?php
class Checkbox extends Field
{
    /**
     * Define field attributes from tag parameter.
     */
    public function construct($punctuation, $title, $content_type, $content)
    {
        $this->title($title);

        // find and set multiple values
        $pattern = '`(?:^|\|)\s*(?:"([^"]+)")?\s*([^| ]+)?`';
        preg_match_all($pattern, $content, $values, PREG_SET_ORDER);
        $this->value(unset_r($values, 0));

        // set attributes
        $this->locked($content_type == '=>;' ? True : False);
        $this->required($punctuation == '!' ? True : False);
        if($punctuation == '?')
        {
            $this->hidden(True);
            $this->locked(True);
        }
    }

    /**
     * Return the html content of the field.
	 * Create the multiple inputs according to the multiple values.
	 *
     * @return string
	 *
	 * @see html
     */
    public function html_content()
    {
		$html = '';
        if(is_array($this->value()))
		{
            $html .= '<fieldset>';
			foreach($this->value() as $i => $arr)
			{
				$html .= '<div class="' . $this->type() . '">';
				$html .= '<input id="' . $this->html_id() . '_option' . $i . '" ';
				$html .= 'type="' . $this->type() . '" ';
				$html .= 'name="' . $this->html_name() . '" ';
				$html .= 'value="' . $arr[1] . '" ';
				if($this->locked()) $html .= 'disabled="disabled" ';
				if(isset($arr[2]) && $arr[2] == 'selected')
					$html .= 'checked ';
				$html .= '/>' . $arr[1];
				$html .= '</div>';
			}
            $html .= '</fieldset>';
		}

        return $html;
    }
}
?>