<?php
$rotating_text = str_replace( array("\r\n", "\n", "\r", "<br/>", "<br>"), '|', $settings->rotating_text );
$durations = apply_filters( 'pp_animated_headline_durations', array(
	'animationDelay' => isset( $settings->animation_delay ) && ! empty( $settings->animation_delay ) ? absint( $settings->animation_delay ) : 2500,
	'lettersDelay' => isset( $settings->letters_delay ) && ! empty( $settings->letters_delay ) ? absint( $settings->letters_delay ) : 50,
	'typeLettersDelay' => isset( $settings->type_letters_delay ) && ! empty( $settings->type_letters_delay ) ? absint( $settings->type_letters_delay ) : 150,
	'selectionDuration' => isset( $settings->selection_duration ) && ! empty( $settings->selection_duration ) ? absint( $settings->selection_duration ) : 500,
	'revealDuration' => isset( $settings->reveal_duration ) && ! empty( $settings->reveal_duration ) ? absint( $settings->reveal_duration ) : 600,
	'revealAnimationDelay' => isset( $settings->reveal_animation_delay ) && ! empty( $settings->reveal_animation_delay ) ? absint( $settings->reveal_animation_delay ) : 1500,
), $settings );
?>

;(function($) {

    new PPAnimatedHeadlines({
        id: '<?php echo $id; ?>',
        headline_style: '<?php echo $settings->headline_style; ?>',
        rotating_text: '<?php echo str_replace("'", "\'", $rotating_text); ?>',
        highlighted_text: '<?php echo str_replace("'", "\'", $settings->highlighted_text ); ?>',
        headline_shape: '<?php echo $settings->headline_shape; ?>',
        animation_type: '<?php echo $settings->animation_type; ?>',
		durations: <?php echo json_encode( $durations ); ?>
    });

})(jQuery);
