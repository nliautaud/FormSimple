<?php
require_once 'text.php';

class Phone extends Text
{
    /**
     * Define if the field content is valid
     * @return boolean
     */
    public function isValid()
    {
        $pattern = '`^\+?[-0-9(). ]{6,}$$`i';
        if(preg_match($pattern, $this->value())) return True;
        else return False;
    }
}
?>