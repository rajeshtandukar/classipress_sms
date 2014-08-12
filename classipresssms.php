<?php
if (!defined('ABSPATH')) exit; // Restrict direct access

/*
Plugin Name: Classipress SMS
Plugin URI: http://www.tandukar.com
Description: Send SMS to seller in ClassiPress Theme.
Version:  1.1
Author: Rajesh Tandukar
Author URI: http://www.tandukar.com
License: GPL2
*/

/*  2014  Rajesh Tandukar  (email : rtandukar@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Classipress_SMS{

    /**
     * Constructor
     */
    public function __construct()
    {

        include_once dirname(__FILE__) . '/includes.php';

        $this->settings = new Classipress_SMS_Settings();

        register_activation_hook(__FILE__, array($this->settings, 'initialize_default_options'));

        // Add SMS form to product page
        add_action('appthemes_after_post_content', array($this, 'get_sms_form'), 16);
        add_action('wp_ajax_cps_send_sms', array($this, 'classipress_sms_sendsms'));
        add_action('wp_ajax_nopriv_cps_send_sms', array($this, 'classipress_sms_sendsms'));

        // Filter phone number
        add_filter('cp_ad_details_field', array($this, 'filter_ad_details'), 16, 3);
    }

    public function get_sms_form(){
        global $post;

        $options = get_option('classipresssms-options');

        // phone field medat key
        $phone = get_post_meta($post->ID, $options['phone_field'], true);

        // Show only on detail product page
        if (is_archive())
            return;

        // If no phone reutrn
        if (!isset($phone) || (isset($phone) && empty($phone)))
            return;

        // Get current logged
        $current_user = wp_get_current_user();
        ?>
        <p class="classipress_sms">
            <a class="classipress_sms-link"><?php _e('Send SMS');?></a>
            <img src="<?php echo CPSMS_DIR; ?>ajax-loading.gif" class="classipress_sms_loading"/>
            <span class="classipress_sms_span"></span>
        </p>


        <div id="classipress_sms_messge_wrapper">
            <form>
                <p>
                    <label style="font-weight: bold;"><?php _e('Message:');?> *</label>
                    <textarea rows="5" style="width:99%" name="classipress_sms_message"
                              id="classipress_sms_message"></textarea>

                <div style="font-size: 10px;font-weight: bold;">
                    <div style="float: left; margin-top:-10px ;padding-right: 5px; ">Maximum Charcters:</div>
                    &nbsp;
                    <div id="classipress_sms_chars" style="float: left; "><?php  echo $options['maxchar'];?></div>
                    <div style="clear: both;"></div>
                </div>
                </p>

                <p>
                    <input type="hidden" name="sms_action" value="send">
                    <input type="button" name="send" value="Send" class="btn_orange" id="classipress_sms_submit">

                </p>
            </form>
        </div>

        <style>
            p.classipress_sms {
                background: url("<?php echo CPSMS_URL;?>app-pencil.png") no-repeat scroll 0 5px transparent;
                clear: both;
                font-size: 11px;
                padding: 5px 5px 5px 25px;

            }

            .classipress_sms span {
                display: none;
                background-color: #ffffe0;
                border-top: 1px solid #e6db55;
                border-bottom: 1px solid #e6db55;
                padding-left: 3px;
                padding-right: 3px;

            }

            #classipress_sms_messge_wrapper {
                display: none;
            }

            .classipress_sms_loading {
                display: none;
            }

            .classipress_sms-link {
                cursor: pointer;
            }

            .classipress_sms_error {
                border: 1px solid #FF0000;
                border-radius: 2px;
            }

            #classipress_sms_chars {
                margin-top: -10px;
            }

        </style>

        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('a.classipress_sms-link').bind('click', function (e) {
                    e.preventDefault();
                    jQuery('#classipress_sms_messge_wrapper').toggle('slow');
                });
                jQuery('#classipress_sms_submit').click(function (e) {
                    var message = document.getElementById('classipress_sms_message').value;
                    var error = false;
                    if (message.length == 0 || message == '') {
                        jQuery('#classipress_sms_message').addClass('classipress_sms_error');
                        error = true;
                    }

                    if (error)
                        return false;

                    jQuery.ajax({
                        url: '<?php echo esc_js( admin_url( 'admin-ajax.php', 'relative' ) ); ?>',
                        beforeSend: function () {
                            jQuery('#classipress_sms_message').removeClass('classipress_sms_error');
                            jQuery('#classipress_sms_messge_wrapper').slideUp('slow');
                            jQuery('.classipress_sms_loading').show();
                        },
                        context: this,
                        data: {
                            action: 'cps_send_sms',
                            post_id: '<?php echo $post->ID;?>',
                            message: message
                        },
                        success: function (data) {
                            if (data == 1) {
                                jQuery('.classipress_sms_loading').hide();
                                jQuery('.classipress_sms_span').html("<?php _e('Your message has been send to seller.');?>").show('fast', function () {
                                    jQuery(this).delay('1000').fadeOut('slow')
                                });

                            } else {
                                jQuery('.classipress_sms_loading').hide();
                                jQuery('.classipress_sms_span').html("<?php _e('Error on sending message.Please try later.');?>").show('fast', function () {
                                    jQuery(this).delay('9000').fadeOut('slow')
                                });

                            }
                            document.getElementById('classipress_sms_message').value = '';

                        }
                    });

                });

                var elem = jQuery("#classipress_sms_chars");
                jQuery("#classipress_sms_message").limiter('<?php echo $options['maxchar'];?>', elem);

            });

            (function (jQuery) {
                jQuery.fn.extend({
                    limiter: function (limit, elem) {
                        jQuery(this).on("keyup focus", function () {
                            setCount(this, elem);
                        });
                        function setCount(src, elem) {
                            var chars = src.value.length;
                            if (chars > limit) {
                                src.value = src.value.substr(0, limit);
                                chars = limit;
                            }
                            elem.html(limit - chars);
                        }
                        setCount(jQuery(this)[0], elem);
                    }
                });
            })(jQuery);

        </script>

    <?php
    }

    public function filter_ad_details($form_field, $post, $location)
    {
        $options = get_option('classipresssms-options');

        if ($options['disable_phone'] == 1 && $form_field->field_name == $options['phone_field']) {
            return null;
        } else
            return $form_field;
    }

    public function classipress_sms_sendsms()
    {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'cps_send_sms') {
            $options = get_option('classipresssms-options');
            $id = isset($_REQUEST['post_id']) ? (int)$_REQUEST['post_id'] : 0;
            $message = isset($_REQUEST['message']) ? $_REQUEST['message'] : '';
            $gateway = lcfirst($options['sms_gateways']);
            $path = CPSMS_SMS_GATEWAYS_DIR . $gateway . '.php';
            if (file_exists($path)) {
                require_once($path);
                $className = 'Classipress_SMS_' . ucfirst($gateway);
                if (class_exists($className)) {
                    $obj = new $className();
                    if (method_exists($obj, 'send_sms')) {
                        if ($obj->send_sms($message, get_post_meta($id, $options['phone_field'], true))) {
                            echo 1;
                        } else {
                            echo 0;
                        }
                    }
                }
            }
        }
        exit;
    }

}

$GLOBALS['classipress_sms'] = new Classipress_SMS();