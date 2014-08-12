<?php
if (!defined('ABSPATH')) exit; // Restrict direct access

class Classipress_SMS_Settings {

    public $option = 'classipresssms-options';
    public $options = array();
    public $defaults = array();
    public $groups = array();

    public function __construct() {

        add_action( 'admin_init', array($this,'register' ));
    }

    public function initialize_default_options(){
        $default_options = array(
            "email" => '',
            "password" => '',
            "phone_field" => '',
            "max_character" => 100,
            "sms_gateways" => 'textmarketer',
            'disable_phone' => 1
        );

        update_option('classipresssms-options', $default_options);
    }

    public function register() {
        register_setting('classipresssms_plugin_option','classipresssms-options',array($this,'validate_options'));
    }
    public function validate_options($input){
        return $input;
    }
}