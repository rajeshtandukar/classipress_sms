<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Restrict direct access

define('CPSMS_BASENAME', trailingslashit(basename(dirname(__FILE__))));
define('CPSMS_DIR', WP_CONTENT_DIR . '/plugins/' . CPSMS_BASENAME);
define('CPSMS_URL', WP_CONTENT_URL . '/plugins/' . CPSMS_BASENAME);
define('CPSMS_SMS_GATEWAYS_DIR', CPSMS_DIR . 'sms_gateways/');

require_once(CPSMS_DIR.'sms.php');
require_once(CPSMS_DIR.'settings.php');
require_once(CPSMS_DIR.'functions.php');