<?php
/**
 * PowerPack admin settings page.
 *
 * @since 1.0.0
 * @package bb-powerpack
 */

?>

<?php

$license 	  		= self::get_option( 'bb_powerpack_license_key' );
$status 	  		= self::get_option( 'bb_powerpack_license_status' );
$current_tab  		= self::get_current_tab();
$hide_templates 	= self::get_option( 'ppwl_hide_templates_tab' );
$hide_extensions 	= self::get_option( 'ppwl_hide_extensions_tab' );
$hide_integration 	= self::get_option( 'ppwl_hide_integration_tab' );
$hide_support_msg 	= self::get_option( 'ppwl_hide_support_msg' );
?>

<div class="wrap">

    <h2>
        <?php
            $admin_label = self::get_option( 'ppwl_admin_label' );
            $admin_label = trim( $admin_label ) !== '' ? trim( $admin_label ) : esc_html__( 'PowerPack', 'bb-powerpack' );
            echo sprintf( esc_html__( '%s Settings', 'bb-powerpack' ), $admin_label );
        ?>
    </h2>

    <?php self::render_update_message(); ?>

    <form method="post" id="pp-settings-form" action="<?php echo self::get_form_action( '&tab=' . $current_tab ); ?>">

        <div class="icon32 icon32-pp-settings" id="icon-pp"><br /></div>

        <h2 class="nav-tab-wrapper pp-nav-tab-wrapper">

            <?php self::render_tabs( $current_tab ); ?>

        </h2>

        <?php

        // General settings.
        if ( ! isset($_GET['tab']) || 'general' == $current_tab ) {
            include BB_POWERPACK_DIR . 'includes/admin-settings-license.php';
        }

        // White Label settings.
        if ( 'white-label' == $current_tab ) {
            include BB_POWERPACK_DIR . 'includes/admin-settings-wl.php';
        }

        // Page templates settings.
        if ( 'templates' == $current_tab && ( ! $hide_templates || $hide_templates == 0 ) ) {
            include BB_POWERPACK_DIR . 'includes/admin-settings-templates.php';
        }

        // Extensions settings.
        if ( 'extensions' == $current_tab && ( ! $hide_extensions || $hide_extensions == 0 ) ) {
            include BB_POWERPACK_DIR . 'includes/admin-settings-extensions.php';
		}
		
		// Integration settings.
        if ( 'integration' == $current_tab && ( ! $hide_integration || $hide_integration == 0 ) ) {
            include BB_POWERPACK_DIR . 'includes/admin-settings-integration.php';
		}

        do_action( 'pp_admin_settings_forms', $current_tab );

        ?>

    </form>

    <?php if ( ! $hide_support_msg || $hide_support_msg == 0 ) { ?>
    <hr />

    <h2><?php esc_html_e('Support', 'bb-powerpack'); ?></h2>
    <p>
        <?php
            $support_link = self::get_option( 'ppwl_support_link' );
            $support_link = !empty( $support_link ) ? $support_link : 'https://wpbeaveraddons.com/contact/';
            esc_html_e('For submitting any support queries, feedback, bug reports or feature requests, please visit', 'bb-powerpack'); ?> <a href="<?php echo $support_link; ?>" target="_blank"><?php esc_html_e('this link', 'bb-powerpack'); ?></a>
    </p>
    <?php } ?>

</div>
