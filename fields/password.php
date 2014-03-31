<?php
require_once 'text.php';
class Password extends Text
{
    /**
     * Define field attributes from tag parameter.
     */
    public function construct(
        $punctuation, $title,
        $content_type, $content
    ){
        $this->title($title);
        $this->required($content);
        $this->locked($content_type == '=&gt;' ? true : false);
        if($punctuation == '?')
        {
            $this->hidden(true);
            $this->locked(true);
        }
    }

    /**
    * Define if the field content is valid.
    *
    * @return boolean
    */
    public function isValid()
    {
        if($this->value() == $this->required()) return true;
        return false;
    }
}
?>