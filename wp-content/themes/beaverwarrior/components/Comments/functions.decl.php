<?php

function skeletonwarrior_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    $url = get_comment_link($comment->comment_ID);
    switch ( $comment->comment_type ) {
        case 'pingback':
        case 'trackback':
        ?>
        <li class="post pingback">
            <p><?php _e( 'Pingback:', 'skeleton_warrior' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'skeleton_warrior' ), ' ' ); ?></p>
        </li>
        <?php
            break;
        default:
            ?>
                <li <?php comment_class('Comments-list_item'); ?> id="li-comment-<?php comment_ID(); ?>">
                    <article id="comment-<?php comment_ID(); ?>" class="Comments-comment">
                        <img src="<?php echo get_avatar_url($comment->user_id); ?>" class="Comments-image">
                        
                        <div class="Comments-comment_content">
                            <div class="Comments-author">
                                <?php if($url) { ?> <a href="<?php echo esc_url( $url ); ?>"> <?php } ?>
                                    <?php comment_author(); ?>
                                <?php if($url) { ?> </a> <?php } ?>
                                <span class="Comments-date"><?php comment_date('M j, Y'); ?> <?php comment_time(); ?></span>
                            </div>

                            <div class="Comments-text_wrapper">
                                <p class="Comments-text"><?php echo $comment->comment_content; ?></p>
                            </div>

                            <div class="Comments-reply_link_wrapper">
                                <p class="Comments-reply_link_text small">
                                    <?php
                                    comment_reply_link( array_merge( $args, array(
                                        'reply_text' => 'Reply',
                                        'depth' => $depth,
                                        'max_depth' => $args['max_depth']
                                    ) ) ); ?>
                                </p>
                            </div>
                        </div>
                    </article>
                </li>
            <?php
            break;
    }
}

function beaver_warrior_Comment_customize_register() {
    global $wp_customize;
    
    $wp_customize->add_section('bw-comments', array(
        "title" => __("Comment Styling", 'skeleton_warrior'),
        "panel" => "fl-content",
        "priority" => 1
    ));
    
    $wp_customize->add_setting("bw-comments-bg-color", array(
        "default" => "#efefef"
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, "bw-comments-bg-color", array(
        "section" => "bw-comments",
        "settings" => "bw-comments-bg-color",
        "label" => __("Comment Background Color", 'skeleton_warrior'),
        "priority" => 1,
    )));
    
    $wp_customize->add_setting("bw-comments-color", array(
        "default" => "#000000"
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, "bw-comments-color", array(
        "section" => "bw-comments",
        "settings" => "bw-comments-color",
        "label" => __("Comment Color", 'skeleton_warrior'),
        "priority" => 2,
    )));
    
    $wp_customize->add_setting("bw-comments-padding", array(
        "default" => 20
    ));
    
    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-comments-padding", array(
        "section" => "bw-comments",
        "settings" => "bw-comments-padding",
        "label" => __("Comment Padding", 'skeleton_warrior'),
        "type" => "slider",
        "priority" => 3,
        'choices' => array(
            'min'  => 0,
            'max'  => 50,
            'step' => 1
        )
    )));
    
    $wp_customize->add_setting("bw-comments-border-radius", array(
        "default" => 10
    ));
    
    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-comments-border-radius", array(
        "section" => "bw-comments",
        "settings" => "bw-comments-border-radius",
        "label" => __("Comment Border Radius", 'skeleton_warrior'),
        "type" => "slider",
        "priority" => 4,
        'choices' => array(
            'min'  => 0,
            'max'  => 30,
            'step' => 1
        )
    )));
    
    $wp_customize->add_setting("bw-comments-link-color", array(
        "default" => "#ED5B32"
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, "bw-comments-link-color", array(
        "section" => "bw-comments",
        "settings" => "bw-comments-link-color",
        "label" => __("Comment Link Color", 'skeleton_warrior'),
        "priority" => 5,
    )));
    
    $wp_customize->add_setting("bw-comments-spacing", array(
        "default" => 10
    ));
    
    $wp_customize->add_control(new FLCustomizerControl($wp_customize, "bw-comments-spacing", array(
        "section" => "bw-comments",
        "settings" => "bw-comments-spacing",
        "label" => __("Comment-to-comment spacing", 'skeleton_warrior'),
        "type" => "slider",
        "priority" => 6,
        'choices' => array(
            'min'  => 0,
            'max'  => 30,
            'step' => 1
        )
    )));
    
    $wp_customize->add_setting("bw-comments-form-hilight-color", array(
        "default" => "#ED5B32"
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, "bw-comments-form-hilight-color", array(
        "section" => "bw-comments",
        "settings" => "bw-comments-form-hilight-color",
        "label" => __("Comment Form Highlight Color", 'skeleton_warrior'),
        "priority" => 7,
    )));
    
    $wp_customize->add_setting("bw-comments-form-bg-color", array(
        "default" => "#ffffff"
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, "bw-comments-form-bg-color", array(
        "section" => "bw-comments",
        "settings" => "bw-comments-form-bg-color",
        "label" => __("Comment Form Background Color", 'skeleton_warrior'),
        "priority" => 7,
    )));
    
    $wp_customize->add_setting("bw-comments-form-color", array(
        "default" => "#000000"
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, "bw-comments-form-color", array(
        "section" => "bw-comments",
        "settings" => "bw-comments-form-color",
        "label" => __("Comment Form Color", 'skeleton_warrior'),
        "priority" => 7,
    )));
}
add_action("customize_register", "beaver_warrior_Comment_customize_register", 11);

function beaver_warrior_Comment_bw_less_vars($vars, $mods) {
    $vars["bw-comments-bg-color"] = FLColor::hex_or_transparent(get_theme_mod("bw-comments-bg-color"));
    $vars["bw-comments-color"] = FLColor::hex_or_transparent(get_theme_mod("bw-comments-color"));
    $vars["bw-comments-padding"] = get_theme_mod("bw-comments-padding", 20) . "px";
    $vars["bw-comments-border-radius"] = get_theme_mod("bw-comments-border-radius", 10) . "px";
    $vars["bw-comments-link-color"] = FLColor::hex_or_transparent(get_theme_mod("bw-comments-link-color"));
    $vars["bw-comments-spacing"] = get_theme_mod("bw-comments-spacing", 10) . "px";
    $vars["bw-comments-form-hilight-color"] = FLColor::hex_or_transparent(get_theme_mod("bw-comments-form-hilight-color"));
    $vars["bw-comments-form-bg-color"] = FLColor::hex_or_transparent(get_theme_mod("bw-comments-form-bg-color"));
    $vars["bw-comments-form-color"] = FLColor::hex_or_transparent(get_theme_mod("bw-comments-form-color"));
    
    return $vars;
}
add_action("bw_less_vars", "beaver_warrior_Comment_bw_less_vars", 10, 2);