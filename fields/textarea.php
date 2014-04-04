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
    public function htmlContent()
    {
        $html = '<textarea id="' . $this->htmlId() . '" rows="10" ';
        $html .= 'name="' . $this->htmlName() . '" ';
        $html .= 'placeholder="' . $this->placeholder() . '" ';
        if($this->locked()) $html .= 'disabled="disabled" ';
        if($this->required()) $html .= 'required ';
        $html .= '>' . $this->value() . '</textarea>';

        return $html;
    }
}
