<?php
class Paragraph extends Field
{
    /**
     * Return the field value in <p> tags.
     */
    public function html_content()
	{
        return '<p>' . $this->value() . '</p>';
	}
}
?>