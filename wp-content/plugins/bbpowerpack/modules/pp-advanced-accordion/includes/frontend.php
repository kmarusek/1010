<?php 
	$css_id = ''; 
?>

<div class="pp-accordion <?php if ( $settings->collapse ) echo ' pp-accordion-collapse'; ?>">
	<?php for ( $i = 0; $i < count( $settings->items ); $i++ ) : if ( empty( $settings->items[ $i ] ) ) continue; 
			$css_id = ( $settings->accordion_id_prefix != '' ) ? $settings->accordion_id_prefix . '-' . ($i+1) : 'pp-accord-' . $id . '-' . ($i+1); ?>
	<div id="<?php echo $css_id; ?>" class="pp-accordion-item">
		<div class="pp-accordion-button">
			<?php if( $settings->items[$i]->accordion_font_icon ) { ?>
				<span class="pp-accordion-icon <?php echo $settings->items[$i]->accordion_font_icon; ?>"></span>
			<?php } ?>
			<span class="pp-accordion-button-label" itemprop="name description"><?php echo $settings->items[ $i ]->label; ?></span>

			<?php if( $settings->accordion_open_icon != '' ) { ?>
				<span class="pp-accordion-button-icon pp-accordion-open <?php echo $settings->accordion_open_icon; ?>"></span>
			<?php } else { ?>
				<i class="pp-accordion-button-icon pp-accordion-open fa fa-plus"></i>
			<?php } ?>

			<?php if( $settings->accordion_close_icon != '' ) { ?>
				<span class="pp-accordion-button-icon pp-accordion-close <?php echo $settings->accordion_close_icon; ?>"></span>
			<?php } else { ?>
				<i class="pp-accordion-button-icon pp-accordion-close fa fa-minus"></i>
			<?php } ?>

		</div>
		<div class="pp-accordion-content fl-clearfix">
			<?php echo $module->render_content( $settings->items[ $i ] ); ?>
		</div>
	</div>
	<?php endfor; ?>
</div>
