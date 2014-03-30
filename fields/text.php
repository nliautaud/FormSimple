<?php
class Text extends Field
{

    /**
     * Return the html content of the field.
	 *
     * @return string the <div>
	 *
	 * @see html
     */
    public function html_content()
    {
        $html = '<input id="' . $this->html_id() . '" ';
        $html .= 'name="' . $this->html_name() . '" ';
		$html .= 'type="' . $this->type() . '" ';
        $html .= 'value="' . $this->value() . '"';
		$html .= 'placeholder="' . $this->placeholder() . '" ';
		if($this->locked()) $html .= 'disabled="disabled" ';
		if($this->required()) $html .= 'required ';
        $html .= '/>';

        return $html;
    }
}
?>