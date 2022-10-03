<?php

$post_per_column_desktop = $settings->posts_per_column ? $settings->posts_per_column : 4;
$post_per_column_tablet  = $settings->posts_per_column_medium ? $settings->posts_per_column_medium : 2;
$post_per_column_mobile  = $settings->posts_per_column_responsive ? $settings->posts_per_column_responsive : 1;

// The overall CSS
$custom_css_general = [
    '.SearchResults-post' => [
        'margin' => $module->getModuleSettingDimension( 'posts_margin' ),
    ],
];


$module->renderModuleCSS( $custom_css_general );
