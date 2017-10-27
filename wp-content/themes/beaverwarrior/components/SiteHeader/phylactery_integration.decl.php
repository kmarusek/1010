<?php

/* Register menus
*/

function skeleton_warrior_register_menus() {
    register_nav_menu('primary_navigation', __( 'Header Primary Navigation'));
    register_nav_menu('secondary_navigation', __( 'Header Secondary Navigation'));
    register_nav_menu('tertiary_navigation', __( 'Header Tertiary Navigation'));
}
add_action( 'init', 'skeleton_warrior_register_menus' );

function skeleton_warrior_register_logo_support() {
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array(),
    ));
}
add_action('after_setup_theme', 'skeleton_warrior_register_logo_support');

function skeleton_warrior_register_hello_bar($wp_customize) {
    $wp_customize->add_setting('skeleton_warrior_hellobar', array());
    
    $wp_customize->add_control(new WP_Customize_Control(
        $wp_customize,
        'skeleton_warrior_hellobar',
        array(
            'label' => __('Hello Bar Text', 'skeleton_warrior'),
            'section' => 'title_tagline',
            'settings' => 'skeleton_warrior_hellobar',
            'priority' => 1
        )
    ));
}
add_action('customize_register', 'skeleton_warrior_register_hello_bar');

function skeleton_warrior_register_sidebars_site_header() {
    register_sidebar(array(
        'name' => __('Site Header Banner Area'),
        'id' => 'site_header_banner',
        'description' => 'For adding widgets that will appear in the mobile "banner" area.',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    
    register_sidebar(array(
        'name' => __('Site Header Primary Navigation Area'),
        'id' => 'site_header_primary_navigation',
        'description' => 'For adding widgets that will appear in the navigation.',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    
    register_widget('SKW_ModalToggle');
    register_widget('SKW_ContactToast');
}
add_action('widgets_init', 'skeleton_warrior_register_sidebars_site_header');

class SKW_ModalToggle extends WP_Widget {
    function __construct() {
        parent::__construct('SKW_ModalToggle',
                           __('Modal Toggle', 'skeleton_warrior'),
                           array('description' => __('Widget that opens any particular modal on the site with a fixed ID.', 'skeleton_warrior')));
    }
    
    public function widget($args, $instance) {
        $target = $instance["target"];
        $title = $instance["title"];
        
        ?><button type="button" data-toggle="offcanvas" data-target="<?php echo $target; ?>" class="SiteHeader-exposed_button" data-toggle-options="nohover">
            <?php echo $title; ?>
        </button><?php
    }
    
    public function form($instance) {
        $target = "#1234";
        if (isset($instance["target"])) {
            $target = $instance["target"];
        }
        
        $title = "";
        if (isset($instance["title"])) {
            $title = $instance["title"];
        }
        
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('target'); ?>"><?php _e('Target'); ?></label>
                <input class="widefat"
                       id="<?php echo $this->get_field_id('target'); ?>"
                       name="<?php echo $this->get_field_name('target'); ?>"
                       type="text"
                       value="<?php echo esc_attr($target); ?>">
                <span class="description">Must match the ID on the target</span>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label>
                <input class="widefat"
                       id="<?php echo $this->get_field_id('title'); ?>"
                       name="<?php echo $this->get_field_name('title'); ?>"
                       type="text"
                       value="<?php echo esc_attr($title); ?>">
            </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        //This is where options are saved.
        //Return the new settings by pulling from $new_instance or $old_instance.
        //Validate/filter if needed.
        
        $instance = array();
        $instance["target"] = (!empty($new_instance["target"])) ? strip_tags($new_instance["target"]) : "";
        $instance["title"] = (!empty($new_instance["title"])) ? strip_tags($new_instance["title"]) : "";
        
        return $instance;
    }
}

class SKW_ContactToast extends WP_Widget {
    function __construct() {
        parent::__construct('SKW_ContactToast',
                            __('Contact Toast', 'skeleton_warrior'),
                           array('description' => __('Widget that spaws a contact form that pops out of the top of the screen.', 'skeleton_warrior')));
    }
    
    public function load_admin_assets() {
        wp_enqueue_script('editor');
    }
    
    public function widget($args, $instance) {
        $id = "Test111";
        if (isset($instance["id"])) {
            $id = $instance["id"];
        }
        
        if (isset($instance["widget_setting"])) {
            $widget_setting = $instance["widget_setting"];
            
            $info_bg = get_field('contact_toast_info_bg', $widget_setting);
            if ($info_bg === NULL) {
                $info_bg = "";
            } else {
                $info_bg = $info_bg["url"];
            }
            
            $form_bg = get_field('contact_toast_form_bg', $widget_setting);
            if ($form_bg === NULL) {
                $form_bg = "";
            } else {
                $form_bg = $form_bg["url"];
            }
            
            $form = get_field('contact_toast_form', $widget_setting);
            
            ?><aside class="SiteHeader-contact_toast is-Offcanvas--closed" id="<?php echo $id; ?>">
                <div class="Offcanvas-scroller">
                    <div class="SiteHeader-contact_content">
                        <section class="SiteHeader-contact_information" style="background-image: url('<?php echo $info_bg; ?>');">
                            <h2 class="SiteHeader-contact_title"><?php the_field('contact_toast_info_title', $widget_setting); ?></h2>
                            <?php the_field('contact_toast_info_body', $widget_setting); ?>
                        </section>
                        <section class="SiteHeader-contact_form_wrapper" style="background-image: url('<?php echo $form_bg; ?>');">
                            <div class="SiteHeader-contact_form">
                                <h2 class="SiteHeader-contact_form_title"><?php the_field('contact_toast_form_title', $widget_setting); ?></h2>
                                <?php if ($form !== false && $form !== null) {
                                    //Apparantly, when Gforms says "Form -Object-" they don't really mean it...
                                    //This code is here to support multiple forms just in case someone turns that on
                                    if (isset($form["title"])) {
                                        $form = array($form);
                                    }
                                    
                                    foreach ($form as $delta => $form_indiv) {
                                        gravity_form_enqueue_scripts($form_indiv["id"], true);
                                        gravity_form($form_indiv["id"], false, true, false, '', true, 1);
                                    }
                                } ?>
                            </div>
                        </section>
                    </div>
                </div>
            </aside><?php
        }
    }
    
    public function form($instance) {
        $id = "1234";
        if (isset($instance["id"])) {
            $id = $instance["id"];
        }
        
        $widget_setting = 0;
        if (isset($instance["widget_setting"])) {
            $widget_setting = $instance["widget_setting"];
        }
        
        //Search through every Widget Settings post that can apply.
        $ws_query = new WP_Query(array(
            'post_type' => 'skw_widget',
            'meta_key' => 'widget_type',
            'meta_value' => get_class($this),
        ));
        
        $selected_rendered = false;
        
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('ID'); ?></label>
                <input class="widefat"
                       id="<?php echo $this->get_field_id('id'); ?>"
                       name="<?php echo $this->get_field_name('id'); ?>"
                       type="text"
                       value="<?php echo esc_attr($id); ?>">
                <span class="description">Must be a unique ID</span>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('widget_setting'); ?>"><?php _e('Widget Setting'); ?></label>
                <select class="widefat"
                       id="<?php echo $this->get_field_id('widget_setting'); ?>"
                       name="<?php echo $this->get_field_name('widget_setting'); ?>">
                    <?php while ($ws_query->have_posts()) {
                        $ws_query->the_post(); ?>
                        <option value="<?php echo esc_attr($ws_query->post->ID); ?>"<?php if ($widget_setting === $ws_query->post->ID) { $selected_rendered = true; ?>selected<?php } ?>><?php the_title(); ?></option>
                    <?php }
                    wp_reset_postdata(); ?>
                    <?php if (!$selected_rendered) { ?>
                        <option value="<?php echo $widget_setting; ?>" selected><?php __("Current (invalid) setting", 'skeleton_warrior'); ?></option>
                    <?php } ?>
                </select>
                <span class="description">
                    <?php echo __('You can create new sets of widget settings from the "Widget Settings" option on the admin sidebar. This widget will not render without a valid settings page.', "skeleton_warrior"); ?>
                </span>
            </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        //This is where options are saved.
        //Return the new settings by pulling from $new_instance or $old_instance.
        //Validate/filter if needed.
        
        $instance = array();
        $instance["id"] = (!empty($new_instance["id"])) ? strip_tags($new_instance["id"]) : "";
        $instance["widget_setting"] = (!empty($new_instance["widget_setting"])) ? strip_tags($new_instance["widget_setting"]) : "";
        
        return $instance;
    }
}