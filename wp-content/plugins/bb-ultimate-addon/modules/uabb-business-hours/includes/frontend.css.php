<?php
$i = 1;

foreach( $settings->businessHours as $business_hours_content ) {
    if( $business_hours_content->highlight_styling != 'no' ) :
        ?>
        .fl-node-<?php echo $id; ?> .uabb-business-hours-wrap .uabb-business-day-<?php echo $i; ?> {
            <?php if( $business_hours_content->day_color != ''  && isset($business_hours_content->day_color) ) { ?>
                color: <?php echo ( false === strpos( $business_hours_content->day_color, 'rgb' ) ) ? '#' . $business_hours_content->day_color : $business_hours_content->day_color; ?>;
            <?php } ?>
        }
        .fl-node-<?php echo $id; ?> .uabb-business-hours-wrap .uabb-business-hours-<?php echo $i; ?> {
            <?php if( $business_hours_content->hour_color != ''  && isset($business_hours_content->hour_color) ) ?>
                color: <?php echo ( false === strpos( $business_hours_content->hour_color, 'rgb' ) ) ? '#' . $business_hours_content->hour_color : $business_hours_content->hour_color; ?>;         
        }
        .fl-node-<?php echo $id; ?> .uabb-business-hours-container .uabb-business-hours-wrap.uabb-business-hours-wrap-<?php echo $i; ?>{
           background-color: <?php echo ( false === strpos( $business_hours_content->background_color, 'rgb' ) ) ? '#' . $business_hours_content->background_color : $business_hours_content->background_color; ?>;
        }
<?php 
    endif;
$i++;
} ?>

.fl-node-<?php echo $id; ?> .uabb-business-hours-wrap:nth-child(odd) {
    <?php if( $settings->striped_effect != 'no' ) : ?>
        background-color: <?php echo ( false === strpos( $settings->striped_odd_rows_color, 'rgb' ) ) ? '#' . $settings->striped_odd_rows_color : $settings->striped_odd_rows_color; ?>;  
    <?php endif; ?> 
}

.fl-node-<?php echo $id; ?> .uabb-business-hours-wrap:nth-child(even) {
    <?php if( $settings->striped_effect != 'no' ) : ?>
        background-color: <?php echo ( false === strpos( $settings->striped_even_rows_color, 'rgb' ) ) ? '#' . $settings->striped_even_rows_color : $settings->striped_even_rows_color; ?>;  
    <?php endif; ?> 
}

.fl-node-<?php echo $id; ?> .uabb-business-hours-container {
    <?php if(isset($settings->background_color_all) && $settings->background_color_all!='') { ?>
        background-color:<?php echo ( false === strpos( $settings->background_color_all, 'rgb' ) ) ? '#' . $settings->background_color_all : $settings->background_color_all; ?>;

        <?php } ?> 
        <?php 
            if(isset($settings->box_padding_top)&&isset($settings->box_padding_bottom)&&isset($settings->box_padding_left)&&isset($settings->box_padding_right)){
                if(isset($settings->box_padding_top)) {
                    echo ($settings->box_padding_top!='') ? 'padding-top:'.$settings->box_padding_top.'px;':'padding-top:10px;';
                }
                 if(isset($settings->box_padding_bottom)){
                    echo ( $settings->box_padding_bottom!='' )?'padding-bottom:'.$settings->box_padding_bottom.'px;':'padding-bottom:10px;';
                }
                if(isset($settings->box_padding_left)){
                    echo ($settings->box_padding_left!='')?'padding-left:'.$settings->box_padding_left.'px;':'padding-left:10px;';
                }
                if(isset($settings->box_padding_right)){
                    echo ($settings->box_padding_right!='')?'padding-right:'.$settings->box_padding_right.'px;':'padding-right:10px;';
                 }
            }

        ?>

    border-radius: <?php echo $settings->border_radius; ?>px;

    border-style: <?php echo $settings->border_style_all; ?>;
    border-top-width: <?php echo $settings->border_width_top; ?>px;
    border-bottom-width: <?php echo $settings->border_width_bottom; ?>px;
    border-left-width: <?php echo $settings->border_width_left; ?>px;
    border-right-width: <?php echo $settings->border_width_right; ?>px;
    border-color:<?php echo ( false === strpos( $settings->border_color, 'rgb' ) ) ? '#' . $settings->border_color : $settings->border_color; ?>; 
}

<?php if( $settings->row_divider != 'no' ) : ?>
    .fl-node-<?php echo $id; ?> .uabb-business-hours-container .uabb-business-hours-wrap:not(:first-child){
         border-top-style: <?php echo $settings->divider_style; ?>;
         border-color:<?php echo ( false === strpos( $settings->divider_color, 'rgb' ) ) ? '#' . $settings->divider_color : $settings->divider_color; ?>; 
         border-top-width: <?php echo $settings->divider_weight; ?>px;
    }
<?php endif; ?>    

.fl-node-<?php echo $id; ?> .uabb-business-hours-wrap {
    padding-top:    <?php echo ( $settings->row_spacing_top!='' ) ? $settings->row_spacing_top : '10' ?>px;
    padding-bottom: <?php echo ( $settings->row_spacing_bottom!='' ) ? $settings->row_spacing_bottom : '10'; ?>px;
    padding-left:   <?php echo ( $settings->row_spacing_left!='' ) ? $settings->row_spacing_left : '10'; ?>px;
    padding-right:  <?php echo ( $settings->row_spacing_right!='' ) ? $settings->row_spacing_right : '10'; ?>px; 
}

.fl-node-<?php echo $id; ?> .uabb-business-day {
    <?php if( $settings->days_alignment != ''  && isset($settings->days_alignment ) ) ?>
        text-align: <?php echo $settings->days_alignment; ?>;
    <?php if( $settings->days_new_font_size != '' && isset($settings->days_new_font_size) ) : ?>
        font-size:  <?php echo $settings->days_new_font_size; ?>px;
    <?php endif;  ?>
    <?php if( $settings->days_new_line_height !='' && isset($settings->days_new_line_height) ) : ?>
        line-height:  <?php echo $settings->days_new_line_height; ?>em;
    <?php endif;  ?>
    <?php if( $settings->days_font['family'] != 'default' && $settings->days_font['weight'] != 'default' ) : ?>
       <?php FLBuilderFonts::font_css( $settings->days_font ); ?>
    <?php endif; ?>
    <?php if( $settings->days_letter_spacing != ""  && isset($settings->days_letter_spacing) ) : ?>
        letter-spacing: <?php echo $settings->days_letter_spacing; ?>px;
    <?php endif; ?>
    <?php if( $settings->days_decoration != ''  && isset($settings->days_decoration) ) ?>        
        text-decoration: <?php echo $settings->days_decoration; ?>;
    <?php if( $settings->days_transform != 'none'  && isset($settings->days_transform) ) ?>        
        text-transform: <?php echo $settings->days_transform; ?>;
    <?php if( $settings->days_color != ''  && isset($settings->days_color) ) : ?>         
        color:<?php echo ( false === strpos( $settings->days_color, 'rgb' ) ) ? '#' . $settings->days_color : $settings->days_color; ?>; 
    <?php endif; ?>    
}

.fl-node-<?php echo $id; ?> .uabb-business-hours {
    <?php if( $settings->hours_alignment != ''  && isset($settings->hours_alignment) ) ?>
        text-align: <?php echo $settings->hours_alignment; ?>;
    <?php if( $settings->hours_new_font_size != '' && isset($settings->hours_new_font_size) ) : ?>
        font-size: <?php echo $settings->hours_new_font_size; ?>px;
    <?php endif;  ?>
     <?php if( $settings->hours_new_line_height != '' && isset($settings->hours_new_line_height) ) : ?>
        line-height: <?php echo $settings->hours_new_line_height; ?>em;
    <?php endif;  ?>
    <?php if( $settings->hours_font['family'] != 'default' && $settings->hours_font['weight'] != 'default' ) : ?>
       <?php FLBuilderFonts::font_css( $settings->hours_font ); ?>
    <?php endif; ?>

    <?php if( $settings->hours_letter_spacing != ""  && isset($settings->hours_letter_spacing) ) : ?> 
        letter-spacing: <?php echo $settings->hours_letter_spacing; ?>px;
    <?php endif; ?>    
    <?php if( $settings->hours_decoration != ''  && isset($settings->hours_decoration) ) ?>        
        text-decoration: <?php echo $settings->hours_decoration; ?>;
    <?php if( $settings->hours_transform != 'none'  && isset($settings->hours_transform) ) ?>        
        text-transform: <?php echo $settings->hours_transform; ?>;
    <?php if( $settings->hours_color != ''  && isset($settings->hours_color) ) : ?>
        color: <?php echo ( false === strpos( $settings->hours_color, 'rgb' ) ) ? '#' . $settings->hours_color : $settings->hours_color; ?>; 
    <?php endif; ?>    
}

@media only screen and ( max-width: <?php echo $global_settings->medium_breakpoint; ?>px ) {
    .fl-node-<?php echo $id; ?> .uabb-business-hours-wrap {
        padding-top:    <?php echo $settings->row_spacing_top_medium; ?>px;
        padding-bottom: <?php echo $settings->row_spacing_bottom_medium; ?>px;
        padding-left:   <?php echo $settings->row_spacing_left_medium; ?>px;
        padding-right:  <?php echo $settings->row_spacing_right_medium; ?>px;
    }
    .fl-node-<?php echo $id; ?> .uabb-business-day {
        <?php if( isset($settings->days_new_font_size_medium) ) : ?>
            font-size: <?php echo $settings->days_new_font_size_medium; ?>px;
        <?php endif;?>    
        <?php if( isset($settings->days_new_line_height_medium) ) : ?>
            line-height: <?php echo $settings->days_new_line_height_medium; ?>em;
        <?php endif; ?>
    }

    .fl-node-<?php echo $id; ?> .uabb-business-hours {
    
        <?php if( isset($settings->hours_new_font_size_medium) ) : ?>
            font-size: <?php echo $settings->hours_new_font_size_medium; ?>px;
        <?php endif;  ?>
         <?php if( isset($settings->hours_new_line_height_medium) ) : ?>
            line-height: <?php echo $settings->hours_new_line_height_medium; ?>em;
        <?php endif;  ?>
    }
    .fl-node-<?php echo $id; ?> .uabb-business-hours-container {
        <?php 
            if(isset($settings->box_padding_top_medium)){
                echo ($settings->box_padding_top_medium!='') ?'padding-top:'.$settings->box_padding_top_medium.'px;':'';
            } 
            if(isset($settings->box_padding_bottom_medium)){
                echo ($settings->box_padding_bottom_medium!='') ? 'padding-bottom:'.$settings->box_padding_bottom_medium.'px;':'';
            }
            if(isset($settings->box_padding_left_medium)){
                echo ($settings->box_padding_left_medium!='') ? 'padding-left:'.$settings->box_padding_left_medium.'px;':'';
            }
            if(isset($settings->box_padding_right_medium)){
                echo ($settings->box_padding_right_medium!='')?'padding-right:'.$settings->box_padding_right_medium.'px;':'';
            }
        ?>
    }

} 

@media only screen and ( max-width: <?php echo $global_settings->responsive_breakpoint; ?>px ) {
    .fl-node-<?php echo $id; ?> .uabb-business-hours-wrap {
        padding-top:    <?php echo $settings->row_spacing_top_responsive; ?>px;
        padding-bottom: <?php echo $settings->row_spacing_bottom_responsive; ?>px;
        padding-left:   <?php echo $settings->row_spacing_left_responsive; ?>px;
        padding-right:  <?php echo $settings->row_spacing_right_responsive; ?>px;
    }
    
    .fl-node-<?php echo $id; ?> .uabb-business-day {
        <?php if(isset($settings->days_new_font_size_responsive) ) : ?>
            font-size:  <?php echo $settings->days_new_font_size_responsive; ?>px;
        <?php endif; ?>
        <?php if( isset($settings->days_new_line_height_responsive) ) : ?>
            line-height:  <?php echo $settings->days_new_line_height_responsive; ?>em;
        <?php endif; ?>
    }

    .fl-node-<?php echo $id; ?> .uabb-business-hours {
        <?php if( isset($settings->hours_new_font_size_responsive) ) : ?>
            font-size: <?php echo $settings->hours_new_font_size_responsive; ?>px;
        <?php endif;  ?>
         <?php if( isset($settings->hours_new_line_height_responsive) ) : ?>
            line-height: <?php echo $settings->hours_new_line_height_responsive; ?>em;
        <?php endif;  ?>
    }
    .fl-node-<?php echo $id; ?> .uabb-business-hours-container {
        <?php
            if(isset($settings->box_padding_top_responsive)){
                echo ($settings->box_padding_top_responsive!='')?'
                padding-top:'.$settings->box_padding_top_responsive.'px;':'';
            }
            if(isset($settings->box_padding_bottom_responsive)){
                echo ($settings->box_padding_bottom_responsive!='' )?'padding-bottom:'.$settings->box_padding_bottom_responsive.'px;':'';
            }
            if(isset($settings->box_padding_left_responsive)){
                echo ($settings->box_padding_left_responsive!='')?'
                padding-left:'.$settings->box_padding_left_responsive.'px;':'';
            }
            if(isset($settings->box_padding_right_responsive)){
                echo ($settings->box_padding_right_responsive!='')?'padding-right:'.$settings->box_padding_right_responsive.'px;':'';
            }
        ?>
    }

} 