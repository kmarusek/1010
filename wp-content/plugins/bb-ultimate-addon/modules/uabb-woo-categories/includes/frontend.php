<div class="uabb-woo-categories uabb-woo-categories-<?php echo $settings->layout; ?>">
	<div class="uabb-woocommerce">
		<div class="uabb-woo-categories-inner <?php echo $module->get_inner_classes(); ?>">
		<?php	
			$module->query_product_categories();
			
			//$module->render_query();

			//$query = $module->get_query();

			//if ( ! $query->have_posts() ) {
			//	return;
			//}

			//$module->render_loop_args();
			//$module->render_woo_loop_start();
				//$module->render_woo_loop();
			//$module->render_woo_loop_end();
			//$module->render_pagination_structure();
			//$module->render_reset_loop();
		?>
		</div>
	</div>
</div>