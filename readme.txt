=== Classipress SMS ===
Contributors: tandubhai
Donate link:
Tags: sms,classipress,wordpress
Requires at least: 3.0
Tested up to: 3.9.2
Stable tag: 1..1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Send SMS to seller in ClassiPress Theme

== Description ==

Send SMS to seller in ClassiPress Theme. Recently plugin supports following SMS Providers

1. Textmarketer (http://www.textmarketer.co.uk/)
2. Sendhub (https://www.sendhub.com/)

Note: Only one SMS provider can be enabled at a time.

Textmarketer API

1. To use Textmarketer API, Create an account at Textmarketer.
2. Follow the link http://www.textmarketer.co.uk/signUpGoogle/ to create you textmarketer account.
3. Enter the username and password from your textmarkter account at the plugin's settings page.

Sendhub API

1. To use sendhub API, you need to create an account at sendhub
2. Follow the link https://www.sendhub.com/signup/ to create your sendhub account.
3. Enter the api key from your sendhub account at the plugin's settings page.


For Developers

1. Developers can add any other SMS Provider code.
2. Add your class in 'sms_gateways' folder.
3. Follow the naming structure for your class as described below.
4. File name should be 'yourclassname.php' (all in small letters).
5. Class name should be Classipress_SMS_Yourclassname (Beginning letter to Uppercase).
6. Your class must have setting_options() method to call your setting options.
7. Your class must have send_sms($message,$mobile) method to send SMS.

== Installation ==

1. Upload the folder classipress_sms  to the wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Update the plugin's setting page. Add your textmarkter or sendbhu account credentials.

== Frequently asked questions ==



== Screenshots ==

1. SMS Text message form
2. Setting Textmarketer
3. Setting Sendhub

== Changelog ==



== Upgrade notice ==



