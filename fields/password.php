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
		$this->locked($content_type == '=&gt;' ? True : False);
		if($punctuation == '?')
		{
			$this->hidden(True);
			$this->locked(True);
		}
    }

	/**
	* Define if the field content is valid.
	*
	* @return boolean
	*/
	public function is_valid()
	{
		if($this->value() == $this->required()) return True;
		return False;
	}
}
?>