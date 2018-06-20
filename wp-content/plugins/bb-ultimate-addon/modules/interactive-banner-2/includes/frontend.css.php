<?php
    global $post;
    $converted = get_post_meta( $post->ID,'_uabb_converted', true );

$settings->title_background_color = UABB_Helper::uabb_colorpicker( $settings, 'title_background_color', true );
$settings->img_background_color = UABB_Helper::uabb_colorpicker( $settings, 'img_background_color', true );

$settings->title_typography_color = UABB_Helper::uabb_colorpicker( $settings, 'title_typography_color' );
$settings->desc_typography_color = UABB_Helper::uabb_colorpicker( $settings, 'desc_typography_color' );
$settings->img_overlay_color = UABB_Helper::uabb_colorpicker( $settings, 'img_overlay_color', true );

?>

<?php
if( $settings->img_overlay_color != '' ) {
?>
.fl-node-<?php echo $id; ?> .uabb-module-content.uabb-ib2-outter:after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: <?php echo $settings->img_overlay_color; ?>;
}
<?php
}
?>

.fl-node-<?php echo $id; ?> .fl-node-content .uabb-new-ib {
    <?php echo ( $settings->banner_height != '' ) ? 'height: ' . $settings->banner_height . 'px;' : ''; ?>
}

.fl-node-<?php echo $id; ?> .fl-node-content {
    overflow: hidden;
}

.fl-node-<?php echo $id; ?> .fl-node-content .uabb-new-ib:before {
    <?php echo ( $settings->img_background_color != '' ) ? 'background-color: ' . $settings->img_background_color . ';' : ''; ?>
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    content: '';
    opacity: 0;
    transition: opacity 0.35s, transform 0.35s;
    z-index: 1;
}

.fl-node-<?php echo $id; ?> .uabb-new-ib.uabb-ib2-hover:before {
    opacity: 1;
    transition: opacity 0.35s, transform 0.35s;
}

.fl-node-<?php echo $id; ?> .uabb-new-ib-content,
.fl-node-<?php echo $id; ?> .uabb-new-ib-content * {
    color: <?php echo uabb_theme_text_color( $settings->desc_typography_color ); ?>;
    <?php
    if( $settings->desc_typography_font_family['family'] != 'Default' ){
        UABB_Helper::uabb_font_css( $settings->desc_typography_font_family );
    } ?>

    <?php if( $converted === 'yes' || isset( $settings->desc_typography_font_size_unit ) && $settings->desc_typography_font_size_unit != '' ) { ?>
        font-size: <?php echo $settings->desc_typography_font_size_unit; ?>px;
    <?php } else if(isset( $settings->desc_typography_font_size_unit ) && $settings->desc_typography_font_size_unit == '' && isset( $settings->desc_typography_font_size['desktop'] ) && $settings->desc_typography_font_size['desktop'] != '') { ?>
        font-size: <?php echo $settings->desc_typography_font_size['desktop']; ?>px;
     <?php } ?> 
    
    <?php if( isset( $settings->desc_typography_font_size['desktop'] ) && $settings->desc_typography_font_size['desktop'] == '' && isset( $settings->desc_typography_line_height['desktop'] ) && $settings->desc_typography_line_height['desktop'] != '' && $settings->desc_typography_line_height_unit == '' ) { ?>
        line-height: <?php echo $settings->desc_typography_line_height['desktop']; ?>px;
    <?php } ?>


    <?php if( $converted === 'yes' || isset( $settings->desc_typography_line_height_unit ) && $settings->desc_typography_line_height_unit != '' ) { ?>
        line-height: <?php echo $settings->desc_typography_line_height_unit; ?>em;
    <?php } else if(isset( $settings->desc_typography_line_height_unit ) && $settings->desc_typography_line_height_unit == '' && isset( $settings->desc_typography_line_height['desktop'] ) && $settings->desc_typography_line_height['desktop'] != '') { ?>
        line-height: <?php echo $settings->desc_typography_line_height['desktop']; ?>px;
    <?php } ?>

    <?php if( $settings->desc_typography_transform != '' )?>
       text-transform: <?php echo $settings->desc_typography_transform; ?>;

    <?php if( $settings->desc_typography_letter_spacing != '' )?>
       letter-spacing: <?php echo $settings->desc_typography_letter_spacing; ?>px;
}

.fl-node-<?php echo $id; ?> <?php echo $settings->title_typography_tag_selection; ?>.uabb-new-ib-title {
    <?php echo ( $settings->title_typography_color != '' ) ? 'color: ' . $settings->title_typography_color . ';' : ''; ?>
    <?php
    if( $settings->title_typography_font_family['family'] != 'Default' ){
        UABB_Helper::uabb_font_css( $settings->title_typography_font_family );
    } ?>

    <?php if( $converted === 'yes' || isset( $settings->title_typography_font_size_unit ) && $settings->title_typography_font_size_unit != '' ) {
        ?>
        font-size: <?php echo $settings->title_typography_font_size_unit; ?>px;
    <?php } else if(isset( $settings->title_typography_font_size_unit ) && $settings->title_typography_font_size_unit == '' && isset( $settings->title_typography_font_size['desktop'] ) && $settings->title_typography_font_size['desktop'] != '') { ?>
        font-size: <?php echo $settings->title_typography_font_size['desktop']; ?>px;
     <?php } ?>
    
    <?php if( isset( $settings->title_typography_font_size['desktop'] ) && $settings->title_typography_font_size['desktop'] == '' && isset( $settings->title_typography_line_height['desktop'] ) && $settings->title_typography_line_height['desktop'] != '' && $settings->title_typography_line_height_unit == '' ) { ?>
        line-height: <?php echo $settings->title_typography_line_height['desktop']; ?>px;
    <?php } ?>

    <?php if( $converted === 'yes' || isset( $settings->title_typography_line_height_unit ) && $settings->title_typography_line_height_unit != '' ) { ?>
        line-height: <?php echo $settings->title_typography_line_height_unit; ?>em;
    <?php } else if(isset( $settings->title_typography_line_height_unit ) && $settings->title_typography_line_height_unit == '' && isset( $settings->title_typography_line_height['desktop'] ) && $settings->title_typography_line_height['desktop'] != '') { ?>
        line-height: <?php echo $settings->title_typography_line_height['desktop']; ?>px;
    <?php } ?>

    <?php if( $settings->title_typography_transform != '' )?>
       text-transform: <?php echo $settings->title_typography_transform; ?>;

    <?php if( $settings->title_typography_letter_spacing != '' )?>
       letter-spacing: <?php echo $settings->title_typography_letter_spacing; ?>px;
}

/*.fl-node-<?php //echo $id; ?> .uabb-new-ib.uabb-ib2-hover .uabb-new-ib-img {
    opacity: <?php //echo ( $settings->hover_opacity / 100 ); ?>;
}

.fl-node-<?php //echo $id; ?> .uabb-new-ib .uabb-new-ib-img {
    opacity: <?php //echo ( $settings->opacity / 100 ); ?>;
}*/

<?php
if( $settings->banner_style == 'style5' ) {
?>
    .fl-node-<?php echo $id; ?> .uabb-ib-effect-style5 .uabb-new-ib-desc {
        background: <?php echo uabb_theme_base_color( $settings->title_background_color ); ?>;
    }
    <?php
}

if( $global_settings->responsive_enabled ) { // Global Setting If started
?>
    @media ( max-width: <?php echo $global_settings->medium_breakpoint; ?>px ) {
 
        .fl-node-<?php echo $id; ?> .uabb-new-ib-content {

            <?php if( $converted === 'yes' || isset( $settings->desc_typography_font_size_unit_medium ) && $settings->desc_typography_font_size_unit_medium != '' ) { ?>
                font-size: <?php echo $settings->desc_typography_font_size_unit_medium; ?>px;
            <?php } else if( isset( $settings->desc_typography_font_size_unit_medium ) && $settings->desc_typography_font_size_unit_medium == '' && isset( $settings->desc_typography_font_size['medium'] ) && $settings->desc_typography_font_size['medium'] != '' ) { ?> 
                font-size: <?php echo $settings->desc_typography_font_size['medium']; ?>px;
            <?php } ?>
            
            <?php if( isset( $settings->desc_typography_font_size['medium'] ) && $settings->desc_typography_font_size['medium'] == '' && isset( $settings->desc_typography_line_height['medium'] ) && $settings->desc_typography_line_height['medium'] != '' && $settings->desc_typography_line_height_unit_medium == '' && $settings->desc_typography_line_height_unit == '' ) { ?>
                    line-height: <?php echo $settings->desc_typography_line_height['medium']; ?>px;
            <?php } ?>


            <?php if( $converted === 'yes' || isset( $settings->desc_typography_line_height_unit_medium ) && $settings->desc_typography_line_height_unit_medium != '' ) { ?>
                line-height: <?php echo $settings->desc_typography_line_height_unit_medium; ?>em;
            <?php } else if( isset( $settings->desc_typography_line_height_unit_medium ) && $settings->desc_typography_line_height_unit_medium == '' && isset( $settings->desc_typography_line_height['medium'] ) && $settings->desc_typography_line_height['medium'] != '' ) { ?> 
                line-height: <?php echo $settings->desc_typography_line_height['medium']; ?>px;
            <?php } ?>
            
        }

        .fl-node-<?php echo $id; ?> <?php echo $settings->title_typography_tag_selection; ?>.uabb-new-ib-title {

            <?php if( $converted === 'yes' || isset( $settings->title_typography_font_size_unit_medium ) && $settings->title_typography_font_size_unit_medium != '' ) { ?>
                font-size: <?php echo $settings->title_typography_font_size_unit_medium; ?>px;
            <?php } else if( isset( $settings->title_typography_font_size_unit_medium ) && $settings->title_typography_font_size_unit_medium == '' && isset( $settings->title_typography_font_size['medium'] ) && $settings->title_typography_font_size['medium'] != '' ) { ?> 
                font-size: <?php echo $settings->title_typography_font_size['medium']; ?>px;
            <?php } ?> 
            
            <?php if( isset( $settings->title_typography_font_size['medium'] ) && $settings->title_typography_font_size['medium'] == '' && isset( $settings->title_typography_line_height['medium'] ) && $settings->title_typography_line_height['medium'] != '' && $settings->title_typography_line_height_unit_medium == '' && $settings->title_typography_line_height_unit == '' ) { ?>
                    line-height: <?php echo $settings->title_typography_line_height['medium']; ?>px;
            <?php } ?>

            <?php if( $converted === 'yes' || isset( $settings->title_typography_line_height_unit_medium ) && $settings->title_typography_line_height_unit_medium != '' ) { ?>
                line-height: <?php echo $settings->title_typography_line_height_unit_medium; ?>em;
            <?php } else if( isset( $settings->title_typography_line_height_unit_medium ) && $settings->title_typography_line_height_unit_medium == '' && isset( $settings->title_typography_line_height['medium'] ) && $settings->title_typography_line_height['medium'] != '' ) { ?> 
                line-height: <?php echo $settings->title_typography_line_height['medium']; ?>px;
            <?php } ?>
             
        }
    }
 
     @media ( max-width: <?php echo $global_settings->responsive_breakpoint; ?>px ) {
        .fl-node-<?php echo $id; ?> .uabb-new-ib-content {

            <?php if( $converted === 'yes' || isset( $settings->desc_typography_font_size_unit_responsive ) && $settings->desc_typography_font_size_unit_responsive != '' ) { ?>
                font-size: <?php echo $settings->desc_typography_font_size_unit_responsive; ?>px;
            <?php } else if( isset( $settings->desc_typography_font_size_unit_responsive ) && $settings->desc_typography_font_size_unit_responsive == '' && isset( $settings->desc_typography_font_size['small'] ) && $settings->desc_typography_font_size['small'] != '' ) { ?> 
                font-size: <?php echo $settings->desc_typography_font_size['small']; ?>px;
            <?php } ?>

            <?php if( isset( $settings->desc_typography_font_size['small'] ) && $settings->desc_typography_font_size['small'] == '' && isset( $settings->desc_typography_line_height['small'] ) && $settings->desc_typography_line_height['small'] != '' && $settings->desc_typography_line_height_unit_responsive == '' && $settings->desc_typography_line_height_unit_medium == '' && $settings->desc_typography_line_height_unit == '' ) { ?>
                    line-height: <?php echo $settings->desc_typography_line_height['small']; ?>px;
            <?php } ?>

            <?php if( $converted === 'yes' || isset( $settings->desc_typography_line_height_unit_responsive ) && $settings->desc_typography_line_height_unit_responsive != '' ) { ?>
                line-height: <?php echo $settings->desc_typography_line_height_unit_responsive; ?>em;
            <?php } else if( isset( $settings->desc_typography_line_height_unit_responsive ) && $settings->desc_typography_line_height_unit_responsive == '' && isset( $settings->desc_typography_line_height['small'] ) && $settings->desc_typography_line_height['small'] != '' ) { ?> 
                line-height: <?php echo $settings->desc_typography_line_height['small']; ?>px;
            <?php } ?> 

        }

        .fl-node-<?php echo $id; ?> <?php echo $settings->title_typography_tag_selection; ?>.uabb-new-ib-title {

            <?php if( $converted === 'yes' || isset( $settings->title_typography_font_size_unit_responsive ) && $settings->title_typography_font_size_unit_responsive != '' ) { ?>
                font-size: <?php echo $settings->title_typography_font_size_unit_responsive; ?>px;
            <?php } else if( isset( $settings->title_typography_font_size_unit_responsive ) && $settings->title_typography_font_size_unit_responsive == '' && isset( $settings->title_typography_font_size['small'] ) && $settings->title_typography_font_size['small'] != '' ) { ?> 
                font-size: <?php echo $settings->title_typography_font_size['small']; ?>px;
            <?php } ?> 

            <?php if( isset( $settings->title_typography_font_size['small'] ) && $settings->title_typography_font_size['small'] == '' && isset( $settings->title_typography_line_height['small'] ) && $settings->title_typography_line_height['small'] != '' && $settings->title_typography_line_height_unit_responsive == '' && $settings->title_typography_line_height_unit_medium == '' && $settings->title_typography_line_height_unit == '' ) { ?>
                    line-height: <?php echo $settings->title_typography_line_height['small']; ?>px;
            <?php } ?>

            <?php if( $converted === 'yes' || isset( $settings->title_typography_line_height_unit_responsive ) && $settings->title_typography_line_height_unit_responsive != '' ) { ?>
                line-height: <?php echo $settings->title_typography_line_height_unit_responsive; ?>em;
            <?php } else if( isset( $settings->title_typography_line_height_unit_responsive ) && $settings->title_typography_line_height_unit_responsive == '' && isset( $settings->title_typography_line_height['small'] ) && $settings->title_typography_line_height['small'] != '' ) { ?> 
                line-height: <?php echo $settings->title_typography_line_height['small']; ?>px;
            <?php } ?>
            
        }
    }
<?php
}
?>