<?php
/**
 * PowerPack admin settings white-label tab.
 *
 * @since 1.0.0
 * @package bb-powerpack
 */

?>

<?php if ( ! self::get_option('ppwl_hide_form') || self::get_option('ppwl_hide_form') == 0 ) { ?>

    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row" valign="top">
                    <label for="ppwl_plugin_name"><?php esc_html_e('Plugin Name', 'bb-powerpack'); ?></label>
                </th>
                <td>
                    <input id="ppwl_plugin_name" name="ppwl_plugin_name" type="text" class="regular-text" value="<?php esc_attr_e( self::get_option('ppwl_plugin_name') ); ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" valign="top">
					<label for="ppwl_plugin_desc"><?php esc_html_e('Plugin Description', 'bb-powerpack'); ?></label>
                </th>
                <td>
                    <textarea id="ppwl_plugin_desc" name="ppwl_plugin_desc" style="width: 25em;"><?php echo self::get_option('ppwl_plugin_desc'); ?></textarea>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" valign="top">
                    <label for="ppwl_plugin_author"><?php esc_html_e('Developer / Agency Name', 'bb-powerpack'); ?></label>
                </th>
                <td>
                    <input id="ppwl_plugin_author" name="ppwl_plugin_author" type="text" class="regular-text" value="<?php echo self::get_option('ppwl_plugin_author'); ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" valign="top">
                    <label for="ppwl_plugin_uri"><?php esc_html_e('Website URL', 'bb-powerpack'); ?></label>
                </th>
                <td>
                    <input id="ppwl_plugin_uri" name="ppwl_plugin_uri" type="text" class="regular-text" value="<?php echo esc_url( self::get_option('ppwl_plugin_uri') ); ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" valign="top">
                    <label for="ppwl_admin_label"><?php esc_html_e('Admin Label', 'bb-powerpack'); ?></label>
                </th>
                <td>
                    <input id="ppwl_admin_label" name="ppwl_admin_label" type="text" class="regular-text" value="<?php echo self::get_option('ppwl_admin_label'); ?>" placeholder="PowerPack" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" valign="top">
                    <label for="ppwl_builder_label"><?php esc_html_e('Category in Panel', 'bb-powerpack'); ?></label>
                </th>
                <td>
                    <input id="ppwl_builder_label" name="ppwl_builder_label" type="text" class="regular-text" value="<?php echo self::get_option('ppwl_builder_label'); ?>" placeholder="PowerPack <?php _e( 'Modules', 'bb-powerpack' ); ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" valign="top">
                    <label for="ppwl_tmpcat_label"><?php esc_html_e('Category in Templates Lightbox', 'bb-powerpack'); ?></label>
                </th>
                <td>
                    <input id="ppwl_tmpcat_label" name="ppwl_tmpcat_label" type="text" class="regular-text" value="<?php echo self::get_option('ppwl_tmpcat_label'); ?>" placeholder="PowerPack Layouts" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" valign="top">
                    <label for="ppwl_rt_label"><?php esc_html_e('Row Templates Shortcut', 'bb-powerpack'); ?></label>
                </th>
                <td>
                    <input id="ppwl_rt_label" name="ppwl_rt_label" type="text" class="regular-text" value="<?php echo self::get_option('ppwl_rt_label'); ?>" placeholder="PowerPack <?php _e( 'Row Templates', 'bb-powerpack' ); ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" valign="top">
                    <label for="ppwl_support_link"><?php esc_html_e('Support link', 'bb-powerpack'); ?></label>
                </th>
                <td>
                    <input id="ppwl_support_link" name="ppwl_support_link" type="text" class="regular-text" value="<?php echo self::get_option('ppwl_support_link'); ?>" placeholder="https://wpbeaveraddons.com/contact/" />
                </td>
            </tr>
			<tr valign="top">
                <th scope="row" valign="top">
                    <label for="ppwl_remove_license_key_link"><?php esc_html_e('Remove license key link', 'bb-powerpack'); ?></label>
                </th>
                <td>
                    <input id="ppwl_remove_license_key_link" name="ppwl_remove_license_key_link" type="checkbox" value="1" <?php echo self::get_option('ppwl_remove_license_key_link') == 1 ? 'checked="checked"' : '' ?> />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" valign="top">
                    <label for="ppwl_hide_support_msg"><?php esc_html_e('Hide Support message', 'bb-powerpack'); ?></label>
                </th>
                <td>
                    <input id="ppwl_hide_support_msg" name="ppwl_hide_support_msg" type="checkbox" value="1" <?php echo self::get_option('ppwl_hide_support_msg') == 1 ? 'checked="checked"' : '' ?> />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" valign="top">
                    <label for="ppwl_hide_templates_tab"><?php esc_html_e('Hide Templates setting tab', 'bb-powerpack'); ?></label>
                </th>
                <td>
                    <input id="ppwl_hide_templates_tab" name="ppwl_hide_templates_tab" type="checkbox" value="1" <?php echo self::get_option('ppwl_hide_templates_tab') == 1 ? 'checked="checked"' : '' ?> />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" valign="top">
                    <label for="ppwl_hide_extensions_tab"><?php esc_html_e('Hide Extensions setting tab', 'bb-powerpack'); ?></label>
                </th>
                <td>
					<input id="ppwl_hide_extensions_tab" name="ppwl_hide_extensions_tab" type="checkbox" value="1" <?php echo self::get_option('ppwl_hide_extensions_tab') == 1 ? 'checked="checked"' : '' ?> />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" valign="top">
                    <label for="ppwl_hide_form"><?php esc_html_e('Hide White Label settings', 'bb-powerpack'); ?></label>
                </th>
                <td>
                    <input id="ppwl_hide_form" name="ppwl_hide_form" type="checkbox" value="1" <?php echo self::get_option('ppwl_hide_form') == 1 ? 'checked="checked"' : '' ?> />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" valign="top">
                    <label for="ppwl_hide_plugin"><?php esc_html_e('Hide plugin', 'bb-powerpack'); ?></label>
                </th>
                <td>
                    <input id="ppwl_hide_plugin" name="ppwl_hide_plugin" type="checkbox" value="<?php echo absint( self::get_option('ppwl_hide_plugin') ); ?>" <?php echo self::get_option('ppwl_hide_plugin') == 1 ? 'checked="checked"' : '' ?> />
                </td>
            </tr>
        </tbody>
    </table>
    <script>
    jQuery(document).ready(function(){
        jQuery('#ppwl_hide_plugin').on('change', function() {
            if ( jQuery(this).is(':checked') ) {
                jQuery(this).val(1);
            } else {
                jQuery(this).val(0);
            }
        });
    });
    </script>
    <p style="">
        <?php esc_html_e( 'You can hide this form to prevent your client from seeing these settings.', 'bb-powerpack' ); ?><br />
        <?php esc_html_e( 'To re-enable the form, you will need to first deactivate the plugin and activate it again.', 'bb-powerpack' ); ?>
    </p>
    <?php submit_button(); ?>
    <?php wp_nonce_field('pp-wl-settings', 'pp-wl-settings-nonce'); ?>

<?php } else { ?>

    <?php if ( isset($_GET['tab']) && 'white-label' == $_GET['tab'] ) { ?>

        <p style=""><?php esc_html_e( 'Done! To re-enable the form, you will need to first deactivate the plugin and activate it again.', 'bb-powerpack' ); ?></p>

    <?php } ?>

<?php } ?>
