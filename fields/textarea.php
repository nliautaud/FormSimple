<?php
class Textarea extends Field
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
        $html = '<textarea id="' . $this->html_id() . '" rows="10" ';
        $html .= 'name="' . $this->html_name() . '" ';
		$html .= 'placeholder="' . $this->placeholder() . '" ';
		if($this->locked()) $html .= 'disabled="disabled" ';
		if($this->required()) $html .= 'required ';
        $html .= '>' . $this->value() . '</textarea>';

        return $html;
    }
}
?>