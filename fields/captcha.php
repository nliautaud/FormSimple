<?php
class Captcha extends Field
{
    /**
     * Define if field content is valid
     * @return boolean
     */
    public function isValid()
    {
        include_once FSPATH . 'fields/securimage/securimage.php';
        $s = new Securimage();
        if(!$s->check($this->value())) return False;
        return True;
    }

    /**
     * Return the html content of the field.
     *
     * @return string the <div>
     *
     * @see html
     */
    public function htmlContent()
    {
        // Image-reload
        $html = '<a href="#" onclick="document.getElementById(\'captchaimg' . $this->htmlId() . '\').src = ';
        $html .= '\'' . FSURL . 'fields/securimage/securimage_show.php?\'+ Math.random(); return false">';
        $html .= '<img id="captchaimg' . $this->htmlId() . '"style="display:block;" ';
        $html .= 'src="' . FSURL . 'fields/securimage/securimage_show.php?' . mt_rand() . '" ';
        $html .= 'alt="CAPTCHA Image" />';
        $html .= '</a>';
        // Input
        $html .= '<input id="' . $this->htmlId() . '" ';
        $html .= 'type="text" ';
        $html .= 'name="' . $this->htmlName() . '" ';
        $html .= 'size="10" maxlength="6" ';

        $html .= 'placeholder="' . $this->placeholder() . '" ';
        if($this->locked()) $html .= 'disabled="disabled" ';
        if($this->required()) $html .= 'required ';
        $html .= '/>';

        return $html;
    }
}
?>