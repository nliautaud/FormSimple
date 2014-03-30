<?php
class Captcha extends Field
{
    /**
     * Define if field content is valid
     * @return boolean
     */
    public function is_valid()
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
    public function html_content()
    {
        // Image-reload
        $html = '<a href="#" onclick="document.getElementById(\'captchaimg' . $this->html_id() . '\').src = ';
        $html .= '\'' . FSURL . 'fields/securimage/securimage_show.php?\'+ Math.random(); return false">';
        $html .= '<img id="captchaimg' . $this->html_id() . '"style="display:block;" ';
        $html .= 'src="' . FSURL . 'fields/securimage/securimage_show.php?' . mt_rand() . '" ';
        $html .= 'alt="CAPTCHA Image" />';
        $html .= '</a>';
        // Input
        $html .= '<input id="' . $this->html_id() . '" ';
        $html .= 'type="text" ';
        $html .= 'name="' . $this->html_name() . '" ';
        $html .= 'size="10" maxlength="6" ';

        $html .= 'placeholder="' . $this->placeholder() . '" ';
        if($this->locked()) $html .= 'disabled="disabled" ';
        if($this->required()) $html .= 'required ';
        $html .= '/>';

        return $html;
    }
}
?>