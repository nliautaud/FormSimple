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
    public function htmlContent()
    {
        $html = '<input id="' . $this->htmlId() . '" ';
        $html .= 'name="' . $this->htmlName() . '" ';
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