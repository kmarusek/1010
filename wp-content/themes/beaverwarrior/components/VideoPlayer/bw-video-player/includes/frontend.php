<?php
// Dependencies for the player
wp_enqueue_script( 'plyr-js', get_stylesheet_directory_uri() . '/assets/vendor/plyr/dist/plyr.polyfilled.min.js' );
wp_enqueue_style( 'plyr-css', get_stylesheet_directory_uri() . '/assets/vendor/plyr/dist/plyr.css' );

// Get the attachent file
$attachment_file = wp_get_attachment_url( $settings->video );

switch( $module->getPlayerType() ){

    case 'modal':
    include __DIR__ . '/frontend-video-type-modal.php';
    break;

    case 'inline':
    default:
    include __DIR__ . '/frontend-video-type-inline.php';
    break;
}