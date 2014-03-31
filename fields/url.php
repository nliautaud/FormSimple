<?php
require_once 'text.php';
class Url extends Text
{
    /**
     * Define field attributes from tag parameter.
     */
    public function construct($punctuation, $title, $content_type, $content)
    {
        $this->title($title);
        $this->value($content_type == '=<' ? '' : $content);
        $this->placeholder($content_type == '=<' ? $content : '');
        $this->locked($content_type == '=>' ? true : false);
        $this->required($punctuation == '!' ? true : false);
        if ($punctuation == '?') {
            $this->hidden(true);
            $this->locked(true);
        }

        // Add http:// if there is no placeholder.
        if(!$this->placeholder()) $this->placeholder('http://');
    }

    /**
     * Define if the field content is valid
     * @return boolean
     */
    public function isValid()
    {
        $pattern =
            "`^(?:https?:\/\/(?:www\.)?|www\.)" .
            "(?:(?:[a-z0-9][-._]?){0,62}[a-z0-9])+" .
            "\.[a-z0-9]{2,6}$`i";
        if(preg_match($pattern, $this->value())) return true;
        else return false;
    }
}
