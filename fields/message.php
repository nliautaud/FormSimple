<?php
require_once 'textarea.php';
class Message extends Textarea
{
    /**
     * Define if the field content is valid
     * @return boolean
     */
    public function is_valid()
    {
        $size = strlen($this->value());
        if($size > 100) return True;
        return False;
    }
}
?>