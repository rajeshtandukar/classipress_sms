<?php
class Classipress_SMS_Lib{

    public $gateways = array();

    public function __construct(){

        $files = scandir(CPSMS_SMS_GATEWAYS_DIR);
        $settings=null;
        if($files){
            foreach($files as $file){
                $path_parts = pathinfo(CPSMS_SMS_GATEWAYS_DIR.$file);
                if(strpos($file,'.php') ){
                    $className = $path_parts['filename'];
                    $this->gateways[]= $className;
                }
            }
        }
    }

    public function settings(){

        foreach($this->gateways as $gateway){

            $path = CPSMS_SMS_GATEWAYS_DIR. $gateway.'.php';

            require_once $path;

            $className= 'Classipress_SMS_'.ucfirst($gateway);
            if (class_exists($className))
            {
                $obj = new $className();
                if(method_exists($obj,'setting_options')){
                    $obj->setting_options();
                }
            }

        }

    }

}