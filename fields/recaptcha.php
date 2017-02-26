<?php
class Recaptcha extends Field
{
    /**
     * Define if field content is valid
     * @return boolean
     */
    public function isValid()
    {
        $response = $_POST['g-recaptcha-response'];
        if (!$response) return false;
        $params = [
            'secret'    => FormSimple::setting('recaptcha_secret_key'),
            'response'  => $response
        ];
        $url = "https://www.google.com/recaptcha/api/siteverify?" . http_build_query($params);
        if (function_exists('curl_version')) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($curl);
        } else {
            $response = file_get_contents($url);
        }
        if (empty($response) || is_null($response)) {
            return false;
        }
        return json_decode($response)->success;
    }

    /**
     * Return the html content of the field.
     *
     * @return string the <div>
     *
     * @see html
     */
    public function htmlContent()
    {
        $api = 'https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit';
        $key = FormSimple::setting('recaptcha_public_key');
        if(!$key) $key = 'no key';
        return '
        <script>
        var CaptchaCallback = function() {
            var captchas = document.querySelectorAll(".FormSimple div.recaptcha");
            for(var i=0; i < captchas.length; i++) {
                grecaptcha.render(captchas[i].id, {"sitekey" : "'.$key.'"});
            }
        };
        </script>
        <script src="'.$api.'" async defer></script>
        <div class="recaptcha" id="'.$this->htmlId().'"></div>
        ';
    }
}
