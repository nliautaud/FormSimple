<?php
require_once 'text.php';
class Honeypot extends Text
{
    /**
     * Define if field content is valid
     * @return boolean
     */
    public function isValid()
    {
        if($this->value()) return False;
        return True;
    }
}
?>