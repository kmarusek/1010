<?php
$fb_app_id = pp_get_fb_app_id();
$fb_sdk_url = pp_get_fb_sdk_url( $fb_app_id );
?>
;(function($) {

	new PPLoginForm({
		id: '<?php echo $id; ?>',
		messages: {
			empty_username: '<?php _e( 'Enter a username or email address.', 'bb-powerpack' ); ?>',
			empty_password: '<?php _e( 'Enter password.', 'bb-powerpack' ); ?>',
			empty_password_1: '<?php _e( 'Enter a password.', 'bb-powerpack' ); ?>',
			empty_password_2: '<?php _e( 'Re-enter password.', 'bb-powerpack' ); ?>',
			empty_recaptcha: '<?php _e( 'Please check the captcha to verify you are not a robot.', 'bb-powerpack' ); ?>',
			email_sent: '<?php _e( 'A password reset email has been sent to the email address for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.', 'bb-powerpack' ); ?>',
			reset_success: '<?php _e( 'Your password has been reset successfully.', 'bb-powerpack' ); ?>',
		},
		page_url: '<?php echo get_permalink(); ?>',
		facebook_login: <?php echo isset( $settings->facebook_login ) && 'yes' === $settings->facebook_login ? 'true' : 'false'; ?>,
		facebook_app_id: '<?php echo $fb_app_id; ?>',
		facebook_sdk_url: '<?php echo $fb_sdk_url; ?>',
		google_login: <?php echo isset( $settings->google_login ) && 'yes' === $settings->google_login ? 'true' : 'false'; ?>,
		google_client_id: '<?php echo BB_PowerPack_Admin_Settings::get_option( 'bb_powerpack_google_client_id' ); ?>',
		ajaxurl: '<?php echo admin_url( 'admin-ajax.php' ); ?>'
	});

})(jQuery);