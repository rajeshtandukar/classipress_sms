<?php
if (!defined('ABSPATH')) exit; // Restrict direct access

function classipress_sms_manager_register_menu_page()
{
    global $wp_module_site, $wp_module_module;
    $menuSlug = 'classipress_sms';

    add_menu_page(__('Classipress SMS', 'preview'), __('Classipress SMS', 'preview'), 'manage_options', $menuSlug, 'classipress_sms_settings');
}

add_action('admin_menu', 'classipress_sms_manager_register_menu_page');

function classipress_sms_settings(){
    ?>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('#classipresssms_gateways_select').change(function () {
                var value = jQuery(this).attr('value');
                if (value == '') {
                    jQuery('.smsapicrend').hide();
                } else {
                    jQuery('.smsapicrend').hide();
                    jQuery('#' + value).show();
                }
            });
        });

    </script>
    <div class="wrap">

        <table class="widefat" style="width: 600px;">
            <thead>
            <tr>
                <th colspan="2">
                    <div class="icon32" id="icon-edit"></div>
                    <h2><?php _e('Classipress SMS Settings');?></h2></th>
            </tr>
            </thead>
        </table>


        <form method="post" name="as-settins-form" action="<?php echo 'options.php'; ?>">
            <?php settings_fields('classipresssms_plugin_option');
            $options = get_option('classipresssms-options');
            $sms_gateway = isset($options['sms_gateways']) ? $options['sms_gateways'] : 'textmarketer';
            $obj_gateways = new Classipress_SMS_Lib();
            ?>
            <table class="widefat" style="width: 600px;">
                <thead>

                <tr>
                    <th colspan="2">To configure classipress SMS update the following values</th>
                </tr>
                </thead>

                <tbody>

                <tr>
                    <td><label for="map_width">Max Character Length: </label></td>
                    <td><input type="text" name="classipresssms-options[maxchar]" size="30"
                               value="<?php echo $options['maxchar']; ?>"/></td>

                </tr>

                <tr>
                    <td><label for="map_width">Phone Meta Name: </label></td>
                    <td><input type="text" name="classipresssms-options[phone_field]" size="30"
                               value="<?php echo $options['phone_field']; ?>"/></td>

                </tr>

                <tr>
                    <td><label for="map_width">Hide Phone Number: </label></td>
                    <td>
                        <input type="radio" id="disable_phone1" name="classipresssms-options[disable_phone]"
                               value="1" <?php if ($options['disable_phone'] == 1) echo 'checked'; ?> > Yes
                        <input type="radio" id="disable_phone2" name="classipresssms-options[disable_phone]"
                               value="2" <?php if ($options['disable_phone'] == 2) echo 'checked'; ?> > No
                    </td>

                </tr>
                <tr>
                    <td><label for="latitude">SMS Gateways: </label></td>
                    <td>
                        <select name="classipresssms-options[sms_gateways]" id="classipresssms_gateways_select">
                            <option value="">--Select--</option>
                            <?php foreach ($obj_gateways->gateways as $gateway) { ?>
                                <option
                                    value="<?php echo $gateway; ?>" <?php if ($gateway == $options['sms_gateways']) echo 'selected';?> ><?php echo ucfirst($gateway);?></option>
                            <?php }?>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>

            <?php $obj_gateways->settings();?>

            <table class="widefat" style="width: 600px;">
                <tbody>
                <tr>
                    <td></td>
                    <td><?php submit_button(__('Update Settings')); ?></td>
                </tr>

                </tbody>
            </table>

        </form>
    </div>
<?php
}