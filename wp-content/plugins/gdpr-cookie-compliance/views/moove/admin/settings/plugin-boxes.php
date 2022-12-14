<?php
/**
 * Plugin Boxes Doc Comment
 *
 * @category  Views
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$gdpr_controller = new Moove_GDPR_Controller();
$plugin_details  = $gdpr_controller->get_gdpr_plugin_details( 'gdpr-cookie-compliance' );
?>
<div class="moove-plugins-info-boxes">

	<?php ob_start(); ?>
	<div class="m-plugin-box m-plugin-box-highlighted">
		<div class="box-header">
			<h4>Premium Add-On</h4>
		</div>
		<!--  .box-header -->
		<div class="box-content">
			<div class="gdpr-faq-forum-content">
				<p><span class="gdpr-chevron-left">&#8250;</span> Includes a cookiewall layout</p>
				<p><span class="gdpr-chevron-left">&#8250;</span> Export & import</p>
        <p><span class="gdpr-chevron-left">&#8250;</span> Consent Log</p>
				<p><span class="gdpr-chevron-left">&#8250;</span> WordPress Multisite support</p>
				<p><span class="gdpr-chevron-left">&#8250;</span> Accept cookies on scroll</p>
				<p><span class="gdpr-chevron-left">&#8250;</span> Consent Analytics</p>
				<p><span class="gdpr-chevron-left">&#8250;</span> Display cookie banner for EU visitors only</p>
				<p><span class="gdpr-chevron-left">&#8250;</span> Language specific scripts</p>
				<p><span class="gdpr-chevron-left">&#8250;</span> and more...</p>
      </div>
			<!-- gdpr-faq-forum-content -->
			<hr />
			<strong>Buy Now for only <span>£59</span></strong>
			<a href="https://www.mooveagency.com/wordpress-plugins/gdpr-cookie-compliance/" target="_blank" class="plugin-buy-now-btn">Buy Now</a>

		</div>
		<!--  .box-content -->
	</div>
	<!--  .m-plugin-box -->
	<?php
		$content = apply_filters( 'gdpr_cookie_compliance_premium_section', ob_get_clean() );
		apply_filters( 'gdpr_cc_keephtml', $content, true );
		$support_class = apply_filters( 'gdpr_support_sidebar_class', '' );
	?>
	
	<div class="m-plugin-box">
		<div class="box-header">
			<h4>Find this plugin useful?</h4>
		</div>
		<!--  .box-header -->
		<div class="box-content">

			<p>You can help other users find it too by <a href="https://wordpress.org/support/plugin/gdpr-cookie-compliance/reviews/?rate=5#new-post" target="_blank">rating this plugin</a>.</p>
			<?php if ( $plugin_details ) : ?>
				<hr />
				<div class="plugin-stats">
					<div class="plugin-download-ainstalls-cnt">
						<div class="plugin-downloads">
							Downloads: <strong><?php echo number_format( $plugin_details->downloaded, 0, '', ',' ); ?></strong>
						</div>
						<!--  .plugin-downloads -->
						<div class="plugin-active-installs">
							Active installations: <strong><?php echo number_format( $plugin_details->active_installs, 0, '', ',' ); ?>+</strong>
						</div>
						<!--  .plugin-downloads -->
					</div>
					<!--  .plugin-download-ainstalls-cnt -->

					<div class="plugin-rating">
						<a href="https://wordpress.org/support/plugin/gdpr-cookie-compliance/reviews/" target="_blank">
							<span class="plugin-stars">
								<?php
									$rating_val = $plugin_details->rating * 5 / 100;
								if ( $rating_val > 0 ) :
									$args   = array(
										'rating' => $rating_val,
										'number' => $plugin_details->num_ratings,
										'echo'   => false,
									);
									$rating = wp_star_rating( $args );
									endif;
								if ( $rating ) :
									apply_filters( 'gdpr_cc_keephtml', $rating, true );
									endif;
								?>
							</span>
						</a>
					</div>
					<!--  .plugin-rating -->
				</div>
				<!--  .plugin-stats -->
			<?php endif; ?>
		</div>
		<!--  .box-content -->
	</div>
	<!--  .m-plugin-box -->
</div>
<!--  .moove-plugins-info-boxes -->
