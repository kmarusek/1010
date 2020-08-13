<?php
global $wp_embed;
$w = 1;
$logo = $settings->the_logo;

wp_enqueue_script( 
    "owl-carousel-2.0-js",
    get_stylesheet_directory_uri() . "/assets/vendor/owl-carousel/owl.carousel.min.js", 
    array("jquery") 
);
wp_enqueue_style( 
    "owl-carousel-2.0-css", 
    get_stylesheet_directory_uri() . "/assets/vendor/owl-carousel/assets/owl.carousel.min.css"
);
$gradientClass;
$class;
if($settings->marquee == 'option-1'){
    $class='marquee';
    $gradientClass='gradient';
}
?> 

<div class='logo-list'> 
        <div class="container-fluid <?php echo $gradientClass; ?>">
            <div class="row <?php echo $class; ?>">
                <?php foreach($settings->the_logo as $logo):
                        $margin_left = $logo->margin_left;
                        $margin_top = $logo->margin_top;
                        $margin_right = $logo->margin_right;
                        $margin_bottom = $logo->margin_bottom;
                        $padding_left = $logo->padding_left;
                        $padding_top = $logo->padding_top;
                        $padding_right = $logo->padding_right;
                        $padding_bottom = $logo->padding_bottom;
                        $background_color = $logo->bg_color;
                        if($logo->logo_sizes_field == 'thumbnail'){
                            $logo_size = 150;
                            $unit = 'px';
                        }
                        if($logo->logo_sizes_field == 'medium'){
                            $logo_size = 300;
                            $unit = 'px';
                        }
                        if($logo->logo_sizes_field == 'large'){
                            $logo_size = 1024;
                            $unit = 'px';
                        }
                        if($logo->logo_sizes_field == '1536x1536'){
                            $logo_size = 1536;
                            $unit = 'px';
                        }
                        if($logo->logo_sizes_field == '2048x2048'){
                            $logo_size = 2048;
                            $unit = 'px';
                        }
                        if($logo->logo_sizes_field == 'Menu 24x24'){
                            $logo_size = 24;
                            $unit = 'px';
                        }
                        if($logo->logo_sizes_field == 'Menu 36x36'){
                            $logo_size = 36;
                            $unit = 'px';
                        }
                        if($logo->logo_sizes_field == 'Menu 48x48'){
                            $logo_size = 48;
                            $unit = 'px';
                        }
                        if($logo->logo_sizes_field == 'Full Size'){
                            $logo_size = getimagesize();
                            $unit = '%';
                        }
                    ?>
                <div class="logo_block cursorAnim">
                    <div class="logo_block_wrapper">
                        <a href="<?php echo $logo->url; ?>" 
                        class="triptych_panel_link">
                            <?php echo 
                            '<div class="logo_image_container" 
                                style="
                                    margin-left:'.$margin_left.'px;
                                    margin-top:'.$margin_top.'px;
                                    margin-right:'.$margin_right.'px;
                                    margin-bottom:'.$margin_bottom.'px;
                                    padding-left:'.$padding_left.'px;
                                    padding-top:'.$padding_top.'px;
                                    padding-right:'.$padding_right.'px;
                                    padding-bottom:'.$padding_bottom.'px;
                                    background-color:#'.$background_color.';
                                    ">';
                            ?>
                            <img src="<?php echo wp_get_attachment_image_src($logo->image, 'full', false)[0]; ?>" style="width:<?php echo $logo_size ?><?php echo $unit ?>;"> 
                            </div>
                        </a>
                    </div>
                </div>
                <?php 
                    endforeach;
                ?>
            </div>
        </div>
    </div>
</div>