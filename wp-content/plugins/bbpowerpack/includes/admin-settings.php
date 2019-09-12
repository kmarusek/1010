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
$remove_support_link = self::get_option( 'ppwl_remove_support_link' );
$remove_docs_link 	= self::get_option( 'ppwl_remove_docs_link' );
?>

<div class="wrap pp-admin-settings-wrap">
	<div class="pp-admin-settings-header">
		<h3>
		<?php
			$admin_label = self::get_option( 'ppwl_admin_label' );
			$admin_label = trim( $admin_label ) !== '' ? trim( $admin_label ) : esc_html__( 'PowerPack', 'bb-powerpack' );
			// translators: %s is either PowerPack or text added in white label setting.
			echo sprintf( esc_html__( '%s Settings', 'bb-powerpack' ), $admin_label );
		?>
		</h3>
		<div class="pp-admin-settings-tabs">
			<?php self::render_tabs( $current_tab ); ?>
		</div>
		<ul class="pp-admin-settings-topbar-nav">
			<?php if ( ! $remove_docs_link ) { ?>
			<li class="pp-admin-settings-topbar-nav-item">
				<a href="https://wpbeaveraddons.com/docs/" target="_blank"><span class="dashicons dashicons-editor-help"></span> <?php _e( 'Documentation', 'bb-powerpack' ); ?></a>
			</li>
			<?php } ?>
			<?php if ( ! $remove_support_link ) { ?>
			<li class="pp-admin-settings-topbar-nav-item">
				<a href="https://wpbeaveraddons.com/contact/" target="_blank"><span class="dashicons dashicons-email"></span> <?php _e( 'Support', 'bb-powerpack' ); ?></a>
			</li>
			<?php } ?>
		</ul>
	</div>

	<div class="pp-admin-settings-content pp-admin-settings-<?php echo $current_tab; ?>">
		<h2 class="pp-notices-target"></h2>
		<?php self::render_update_message(); ?>

		<form method="post" id="pp-settings-form" action="<?php echo self::get_form_action( '&tab=' . $current_tab ); ?>">

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
	</div>
</div>
<style>
#wpcontent {
	padding-left: 0;
}
</style>
