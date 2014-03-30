<?php
class Paragraph extends Field
{
    /**
     * Return the field value in <p> tags.
     */
    public function htmlContent()
	{
        return '<p>' . $this->value() . '</p>';
	}
}
?>