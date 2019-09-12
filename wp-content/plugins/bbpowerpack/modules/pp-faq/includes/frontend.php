<?php 
	$css_id = ''; 
?>

<div class="pp-faq <?php if ( 'all' != $settings->expand_option && $settings->collapse ) echo 'pp-faq-collapse'; ?>" itemscope itemtype="https://schema.org/FAQPage">
	<?php for ( $i = 0; $i < count( $settings->items ); $i++ ) : if ( empty( $settings->items[ $i ] ) ) continue; 
		$css_id = ( $settings->faq_id_prefix != '' ) ? $settings->faq_id_prefix . '-' . ($i+1) : 'pp-faq-' . $id . '-' . ($i+1); ?>
		<div id="<?php echo $css_id; ?>" class="pp-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
			<div class="pp-faq-button">
				<h3 class="pp-faq-button-label" itemprop="name"><?php echo $settings->items[ $i ]->faq_question; ?></h3>

				<?php if( $settings->faq_open_icon != '' ) { ?>
					<span class="pp-faq-button-icon pp-faq-open <?php echo $settings->faq_open_icon; ?>"></span>
				<?php } else { ?>
					<i class="pp-faq-button-icon pp-faq-open fa fa-plus"></i>
				<?php } ?>

				<?php if( $settings->faq_close_icon != '' ) { ?>
					<span class="pp-faq-button-icon pp-faq-close <?php echo $settings->faq_close_icon; ?>"></span>
				<?php } else { ?>
					<i class="pp-faq-button-icon pp-faq-close fa fa-minus"></i>
				<?php } ?>

			</div>
			<div class="pp-faq-content fl-clearfix" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
				<?php echo $module->render_content( $settings->items[ $i ] ); ?>
			</div>
		</div>
	<?php endfor; ?>
</div>
