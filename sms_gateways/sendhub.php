<?php
if (!defined('ABSPATH')) exit; // Restrict direct access

class Classipress_SMS_Sendhub
{

    private $name = 'sendhub';

    private $url = 'https://api.sendhub.com/v1/messages/?username={NUM}\&api_key={APIKEY}'; // url of the service


    public function __construct()
    {

        $this->options = get_option('classipresssms-options');
        $this->apikey = $this->options['sendhub_api_key'];
    }


    public function setting_options()
    {
        $options = get_option('classipresssms-options');
        ?>
        <table id="<?php echo $this->name; ?>" class="widefat smsapicrend textmarketer"
               style="width: 600px; <?php if ($options['sms_gateways'] != 'sendhub') echo 'display:none;'; ?>">
            <thead>
            <tr>
                <th colspan="2">To configure Sendhub update the following values.
                    <small>( If you have no account, <a href="https://www.sendhub.com/signup/">create one</a>)</small>
                </th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td style="width:199px;"><label for="day_less_five_fare">SendHub API Key: </label></td>
                <td><input type="text" name="classipresssms-options[sendhub_api_key]" size="30"
                           value="<?php echo $options['sendhub_api_key']; ?>"/></td>
            </tr>

            </tbody>
        </table>
    <?php

    }


    public function send_sms($message, $mobile)
    {
        $this->url = str_replace('{NUM}', $mobile, $this->url);
        $this->url = str_replace('{APIKEY}', $this->apikey, $this->url);
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $this->url);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, FALSE);

        if (LOCAL_MODE) {
            curl_setopt($curlHandle, CURLOPT_HTTPPROXYTUNNEL, TRUE);
            curl_setopt($curlHandle, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($curlHandle, CURLOPT_PROXY, FALSE);
        }

        curl_setopt($curlHandle, CURLOPT_POST, 1);

        $data = '{
                   "contacts": [
                      1111
                   ],
                   "text": "' . $message . '"
                }';

        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
        $responseBody = $result = curl_exec($curlHandle);
        $responseInfo = curl_getinfo($curlHandle);
        curl_close($curlHandle);

        return $this->handleResponse($responseBody, $responseInfo);
    }

    private function handleResponse($body, $info)
    {
        if ($info['http_code'] == 200 || $info['http_code'] == 201) // successful submission
            return true;
        else
            return false;
    }
}