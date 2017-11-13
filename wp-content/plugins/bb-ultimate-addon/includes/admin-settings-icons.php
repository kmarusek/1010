<div id="fl-uabb-icons-form" class="fl-settings-form uabb-fl-settings-form">

	<h3 class="fl-settings-form-header"><?php _e( 'Reload Icons', 'uabb' ); ?></h3>

	<form id="uabb-icons-form" action="<?php UABBBuilderAdminSettings::render_form_action( 'uabb-icons' ); ?>" method="post">

		<div class="fl-settings-form-content">

			<p><?php echo sprintf(
					__( 'Clicking the button below will reinstall %s icons on your website. If you are facing issues to load %s icons then you are at right place to troubleshoot it.', 'uabb' ),
					UABB_PREFIX,
					UABB_PREFIX
				); ?></p>
			<span class="button uabb-reload-icons">
				<i class="dashicons dashicons-update" style="padding: 3px;"></i>
				<?php _e( 'Reload Icons', 'uabb' ); ?>
			</span>

		</div>
	</form>
</div>
