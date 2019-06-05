<?php
$custom_css_general = [
    '.video-placeholder-container',
    '.video-play-button' => [
        '.video-play-icon' => [
            'font-size' => $module->getModuleSettingWithUnits( 'play_icon_size' ),
            'color'     => $module->getModuleSettingColor( 'play_icon_color' ),
            '&:hover' => [
                'color' => $module->getModuleSettingColor( 'play_icon_hover_color' )
            ]
        ]
    ]
];

$module->renderModuleCSS( $custom_css_general );

// IF we're using a modal, render the styles
if ( $module->isPlayerTypeModal() ){
    $background_color = $module->getModuleSettingColor( 'modal_overlay_background_color' );
    $background_blur  = $module->getModuleSettingWithUnits( 'modal_overlay_background_blur' );
    ?>
    .featherlight.video-node-<?php echo $id;?>:last-of-type {
        background-color: <?php echo $background_color;?>;
    }

    body.bw-video-modal-open .fl-page header,
    body.bw-video-modal-open .fl-page .fl-page-content {
    filter: blur(<?php echo $background_blur;?>);
    }
    body.bw-video-modal-open #wpadminbar {
    filter: blur(<?php echo $background_blur;?>);
    }
<?php
}
