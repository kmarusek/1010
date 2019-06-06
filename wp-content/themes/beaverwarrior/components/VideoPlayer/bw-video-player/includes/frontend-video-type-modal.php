<?php
// Add featherlight
wp_enqueue_script( 'featherlight-js', get_stylesheet_directory_uri() . '/assets/vendor/featherlight/release/featherlight.min.js', array('jquery') );
wp_enqueue_style( 'featherlight-css', get_stylesheet_directory_uri() . '/assets/vendor/featherlight/release/featherlight.min.css' );
?>
<div class="video-placeholder-container">
    <?php
    echo wp_get_attachment_image( $settings->placeholder_image, 'full', null, array( 'height' => 'auto', 'width' => '100%' ) );
    ?>
    <div class="video-play-icon-container">
        <button class="video-play-button">
            <?php
            echo sprintf(
                '<i class="video-play-icon %s" aria-hidden="true"></i>',
                $settings->play_icon
            );
            ?>
        </button>
    </div>
    <div class="video-content">
        <?php
        echo $module->getVideoContent();
        ?>
    </div>
</div>