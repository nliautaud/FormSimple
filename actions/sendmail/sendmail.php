<?php
/**
 * FormSimple sendmail action class
 *
 * Send a mail to form targets with the fields contents.
 * Create the mail content and headers along form datas,
 * update the form status and reset the form.
 * @author Nicolas Liautaud <contact@nliautaud.fr>
 * @package FormSimple
 */
class Sendmail extends Action
{
    private $targets;
    private $subject;
    private $headers;
    private $body;

    /*
     * Action initialisation.
     */
    public function construct()
    {
        $this->version = '1.0';

        $this->targets = '';
    }

    /*
     * Send a mail to fom targets with the fields contents.
     * @param Form $form the posted form
     * @return string the mail sending status
     */
    function exec()
    {
        $server = $_SERVER['SERVER_NAME'];
        $uri = $_SERVER['REQUEST_URI'];
        $askcopy = False;

        // title
        $html  = '<h2>' . $this->word('fromsite') . ' <i>' . $server . '</i></h2>';

        // fields
        $skip = explode(',', $this->setting('skip_fields'));
        foreach($this->form->fields() as $field)
        {
            $type  = $field->type();
            $value = $field->value();
            $title = $field->title();
            switch($type)
            {
                case 'name' : $name = $value; break;
                case 'email': $email = $value; break;
                case 'subject': $this->subject = $value; break;
                case 'askcopy': $askcopy = True; break;
            }
            if(!in_array($type, $skip) && !empty($value))
            {
                // field name
                $title = !empty($title) ? $title : $type;
                $html .= '<p><b>' . $this->word($title);
                if($field->hidden()) $html .= ' (' . $this->word('hidden').')';
                $html .= ' :</b> ';

                // field value
                switch($type)
                {
                    case 'message' :
                    case 'textarea' :
                        $html .= '<p style="margin:10px;padding:10px;border:1px solid silver">';
                        $html .= nl2br($value) . '</p>';
                        break;
                    case 'website' :
                        $html .= $this->htmlLink($value);
                        break;
                    case 'checkbox' :
                    case 'select' :
                    case 'radio' :
                        foreach($value as $v)
                            if(isset($v[2]) && $v[2] == 'selected')
                                $html .=  $v[1];
                        break;
                    default :
                        $html .= $value;
                }
                $html .= '</p>';
            }
        }

        // footer
        $html .= '<p><i>';
        $html .= $this->word('frompage') . ' ';
        $html .= $this->htmlLink($server.$uri, $uri);
        $html .= '</i></p>';

        if(empty($name))
        {
            $name = $this->word('anonymous');
        }
        if(empty($email))
        {
            $askcopy = False;
            $email = $this->word('anonymous');
        }
        if(empty($this->subject))
        {
            $this->subject = $this->word('nosubject');
        }
        $this->subject = '=?utf-8?B?' . base64_encode($this->subject) . '?=';

        $mime_boundary = '----FormSimple_boundary--'.md5(time());

        $this->headers  = "From: $name <$email>\n";
        $this->headers .= "Reply-To: $name <$email>\n";
        $this->headers .= "Return-Path: $name <$email>\n";
        $this->headers .= "MIME-Version: 1.0\n";
        $this->headers .= "Content-type: multipart/alternative; boundary=\"$mime_boundary\"\n";
        $this->headers .= "X-Mailer: PHP/" . phpversion() . "\n" ;

        //plain text version
        $this->body  = "--$mime_boundary\n";
        $this->body .= "Content-Type: text/plain; charset=UTF-8\n";
        $this->body .= "Content-Transfer-Encoding: 7bit\n";
        $arr = array('`</?p[^>]*>`','`<br ?/>`','`</?h2>`');
        $this->body .= strip_tags(preg_replace($arr,"\n",$html))."\n";
        //html version
        $this->body .= "--$mime_boundary\n";
        $this->body .= "Content-Type: text/html; charset=UTF-8\n";
        $this->body .= "Content-Transfer-Encoding: 7bit\n\n";
        $this->body .= $html."\n\n";
        $this->body .= "--$mime_boundary--\n\n";

        $this->targets = $this->getEmails($this->form->params(), $this->setting('default_targets'));

        if(empty($this->targets))
        {
            $this->error('error_targets');
        }
        else
        {
            // send mail
            if(mail($this->targets, $this->subject, $this->body, $this->headers))
            {
                if($askcopy)
                {
                    mail($email, $this->subject, $this->body, $this->headers);
                }
                $this->completed();
            }
            else $this->error('error');
        }
    }

    /*
     * Things that happened when the action is complete.
     */
    private function completed()
    {
        $this->form->message($this->word('completed'));
        $this->form->hide(True);
    }

    /*
     * Return informations about the action.
     * @return string
     */
    public function htmlDebug()
    {
        $output = 'Targets<pre>' . $this->targets . '</pre>';
        $output .= 'Base64 subject<pre>' . $this->subject . '</pre>';
        $output .= 'Headers<pre>' . $this->headers . '</pre>';
        $output .= 'Content<pre>' . $this->body . '</pre>';
        return $output;
    }

    /*
     * Find and return email addresses from some inputs.
     * @param string|array any number of parameters
     * @return string the emails separated by commas
     */
    private function getEmails()
    {
        $email_pattern =
            "`(?:(?:[a-z0-9][-.+_=']?)*[a-z0-9])+" .
            "@(?:(?:[a-z0-9][-._]?){0,62}[a-z0-9])+" .
            "\.[a-z0-9]{2,6}`i";
        foreach(func_get_args() as $arg)
        {
            if(is_array($arg)) $arg = implode($arg, ',');
            preg_match_all($email_pattern, $arg, $targets);
            $targets = implode($targets[0], ',');
            if(!empty($targets))
            {
                if(!isset($all_targets)) $all_targets = $targets;
                else $all_targets .= ',' . $targets;
            }
        }
        return $all_targets;
    }

    /*
    * Return an html link
    * @param string $href the link address
    * @param string $title if not used, the link title will be the address
    * @param string $protocol http:// by default
    * @return string the <a>
    */
    function htmlLink($href, $title = False, $protocol = 'http://')
    {
        if(!$title) $title = $href;
        return '<a href="' . $protocol . $href . '">' . $title . '</a>';
    }
}
?>
