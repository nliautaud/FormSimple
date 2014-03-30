<?php
require_once 'text.php';
class Email extends Text
{
    /**
     * Define if the field content is valid
     * @return boolean
     */
    public function is_valid()
    {
        $pattern =
            "`^(?:(?:[a-z0-9][-.+_=']?)*[a-z0-9])+" .
            "@(?:(?:[a-z0-9][-._]?){0,62}[a-z0-9])+" .
            "\.[a-z0-9]{2,6}$`i";
        if(preg_match($pattern, $this->value())) return True;
        return False;
    }
}
?>