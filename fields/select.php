<?php
class Select extends Checkbox
{
    /**
     * Return the html content of the field.
	 *
	 * Create a list with the multiple values.
	 *
     * @return string the <div>
	 *
	 * @see html
     */
    public function html_content()
    {
        $html = '<select id="' . $this->html_id() . '" ';
        $html .= 'name="' . $this->html_name() . '" ';
		$html .= 'placeholder="' . $this->placeholder() . '" ';
		if($this->locked()) $html .= 'disabled="disabled" ';
		if($this->required()) $html .= 'required ';
        $html .= '>';
		if(is_array($this->value()))
		foreach($this->value() as $i => $arr)
		{
			$html .= '<option id="' . $this->html_id() . '_option' . $i . '" ';
			$html .= 'value="' . $arr[1] . '" ';
			if(isset($arr[2]) && $arr[2] == 'selected')
				$html .= 'selected="selected" ';
			$html .= '>' . $arr[1] . '</option>';
		}
		$html .= '</select>';

        return $html;
    }
}
?>