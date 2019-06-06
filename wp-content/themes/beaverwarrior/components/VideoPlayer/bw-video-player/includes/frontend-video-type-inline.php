<div class="video-inline-container">
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
    </div>
    <div class="video-container">
        <?php
        echo $module->getVideoContent();
        ?>
    </div>
</div>
