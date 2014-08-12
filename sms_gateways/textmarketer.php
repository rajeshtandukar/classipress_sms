<?php
if (!defined('ABSPATH')) exit; // Restrict direct access

class Classipress_SMS_Textmarketer
{

    private $name = 'textmarketer';
    private $url = 'http://api.textmarketer.co.uk/services/rest/sms'; // url of the service
    private $username;
    private $password;

    function __construct()
    {
        $this->options = get_option('classipresssms-options');
        $this->username = $this->options['textmarketer_username'];
        $this->password = $this->options['textmarketer_passowrd'];
    }


    public function setting_options()
    {
        $options = get_option('classipresssms-options');
        ?>
        <table id="<?php echo $this->name; ?>" class="widefat smsapicrend textmarketer"
               style="width: 600px; <?php if ($options['sms_gateways'] != 'textmarketer') echo 'display:none;'; ?>">
            <thead>
            <tr>
                <th colspan="2">To configure Textmarketer update the following values.
                    <small>( If you have no account, <a href="http://www.textmarketer.co.uk/">create one</a>)</small></th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td><label for="day_less_five_fare">Username: </label></td>
                <td><input type="text" name="classipresssms-options[textmarketer_username]" size="30"
                           value="<?php echo $options['textmarketer_username']; ?>"/></td>
            </tr>

            <tr>
                <td><label for="day_less_five_fare">Password: </label></td>
                <td><input type="password" name="classipresssms-options[textmarketer_passowrd]" size="30"
                           value="<?php echo $options['textmarketer_passowrd']; ?>"/></td>
            </tr>

            </tbody>
        </table>
    <?php

    }

    public function send_sms($message, $mobile)
    {

        $url_array = array('message' => $message, 'mobile_number' => $mobile, 'originator' => get_bloginfo('name'),
            'username' => $this->username, 'password' => $this->password);
        $url_string = $data = http_build_query($url_array, '', '&');
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $this->url);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $url_string);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        $responseBody = curl_exec($curlHandle);
        $responseInfo = curl_getinfo($curlHandle);
        curl_close($curlHandle);
        return $this->handleResponse($responseBody, $responseInfo);
    }

    private function handleResponse($body, $info)
    {
        if ($info['http_code'] == 200)
            return true;
        else
            return false;
    }

}