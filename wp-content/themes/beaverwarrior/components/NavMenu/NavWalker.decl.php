<?php

class DragonfruitNavWalker extends Walker_Nav_Menu {
    private $cpt; // Boolean, is current post a custom post type
    private $archive; // Stores the archive page for current URL
    private $curItem; //http://wordpress.stackexchange.com/questions/62054/custom-walker-how-to-get-id-in-function-start-lvl

    public function __construct() {
        add_filter('nav_menu_css_class', array($this, 'cssClasses'), 10, 2);
        add_filter('nav_menu_item_id', '__return_null');
        $cpt           = get_post_type();
        $this->cpt     = in_array($cpt, get_post_types(array('_builtin' => false)));
        $this->archive = get_post_type_archive_link($cpt);
    }

    public function checkCurrent($classes) {
        return preg_match('/(current[-_])|active|dropdown/', $classes);
    }

    // @codingStandardsIgnoreStart
    function start_lvl(&$output, $depth = 0, $args = []) {
        $dropdown_classes = "Offcanvas SiteHeader-dropdown is-Offcanvas--closed ";

        $offcanvas_lv = $depth + 2;
        $dropdown_classes .= "Offcanvas--lv$offcanvas_lv";
        
        /*if ( $depth == 0 )
            $dropdown_classes .= "dropdown-mega-menu ";*/

        $output .= "\n<div id=\"$args->theme_location$this->curItem\" class=\"$dropdown_classes\">\n";
        $output .= "\n<div class=\"Offcanvas-scroller\">";
        $output .= "\n<ul class=\"NavMenu NavMenu--dropdown\">\n";
    }

    function end_lvl(&$output, $depth = 0, $args = []) {    
        $output .= "\n</ul>\n";
        $output .= "\n</div>\n";
        $output .= "\n</div>\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
        //write_log(print_r($args, true));
       /* write_log('start level args');
        write_log($args->theme_location);*/
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $classes[] = 'NavMenu-link';
        $classes[] = 'NavMenu-link--' . $depth;

        if ($depth === 0) {
            $classes[] = 'NavMenu--main_menu-link';
        } else {
            $classes[] = 'NavMenu--dropdown-link';
        }

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        //http://wordpress.stackexchange.com/questions/13604/get-the-id-of-the-page-a-menu-item-links-to
        $page_id = get_post_meta( $item->ID, '_menu_item_object_id', true );
        //$nav_image = get_field('navigation_image', $page_id);

        $style = '';
        /*if ( $nav_image && $depth === 1 ) {
            $style  = ' style="background-image:url(' . $nav_image . ');"';
        }*/

        $output .= $indent . '<li' . $id . $class_names . '>';

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';

        // If item has_children add atts to a.
        //https://github.com/twittem/wp-bootstrap-navwalker/blob/master/wp_bootstrap_navwalker.php
        if ($args->has_children) {
            $atts['href'] = "#";
            //$atts['data-toggle']    = 'dropdown';
            $atts['data-toggle']    = 'offcanvas';
            $atts['data-target']    = "#$args->theme_location$this->curItem";
            $atts['data-toggle-options']    = '';
            $atts['aria-haspopup']  = 'true';
            $atts['href'] = ! empty( $item->url ) ? $item->url : '';
        } else {
            /*$atts['href'] = ! empty( $item->url ) ? $item->url : '';*/
            $atts['href'] = ! empty( $item->url ) ? $item->url : '';
        }

        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;

        $item_output .= '<a'. $attributes .'> <span>';
        /** This filter is documented in wp-includes/post-template.php */
        
        

        if ( $nav_image && $depth === 1 ) {
           // $item_output .= '<div class="HoverBlock-cover HoverBlock-cover--curt">
           // <div class="ProductsGrid-productContents">
           // <h4 class="ProductsGrid-productTitle">';
        }

        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        
        if ( $nav_image && $depth === 1 ) {
           // $item_output .= '</h4></div></div>';

        }

        $item_output .= '</span> </a>';

        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
        //write_log(print_r($element, true));
        $element->is_dropdown = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 2))));

        if ($element->is_dropdown) {
            $element->classes[] = "dropdown-list-item dropdown--lv$depth";
            $this->curItem++;

            /*if ($depth == 0) {
                 $element->classes[] .= ' yamm-fw';
            }*/

            foreach ($children_elements[$element->ID] as $child) {
                if ($child->current_item_parent || dragonfruit_url_compare($this->archive, $child->url)) {
                    $element->classes[] = 'NavMenu-link--active';
                }
            }
        }

        $element->is_active = strpos($this->archive, $element->url);

        if ($element->is_active) {
            $element->classes[] = 'NavMenu-link--active';
        }

        //http://wordpress.stackexchange.com/questions/16818/add-has-children-class-to-parent-li-when-modifying-walker-nav-menu
        $id_field = $this->db_fields['id'];
        if ( is_object( $args[0] ) ) {
            $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
        }

        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
    // @codingStandardsIgnoreEnd

    public function cssClasses($classes, $item) {
        $slug = sanitize_title($item->title);

        if ($this->cpt) {
            $classes = str_replace('current_page_parent', '', $classes);

            if (dragonfruit_url_compare($this->archive, $item->url)) {
                $classes[] = 'NavMenu-link--active';
            }
        }

        $classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'NavMenu-link--active', $classes);
        $classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);

        $classes[] = 'menu-' . $slug;

        $classes = array_unique($classes);

        return array_filter($classes, 'dragonfruit_is_element_empty');
    }
}

/**
 * Make a URL relative
 */
function dragonfruit_root_relative_url($input) {
  $url = parse_url($input);
  if (!isset($url['host']) || !isset($url['path'])) {
    return $input;
  }
  if (is_multisite()) {
    $network_url = parse_url(network_site_url(), PHP_URL_HOST);
  }
  if (($url['host'] === $_SERVER['SERVER_NAME']) || $url['host'] === $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] || (isset($network_url) && $url['host'] === $network_url)) {
    return wp_make_link_relative($input);
  }
  return $input;
}

function dragonfruit_url_compare($url, $rel) {
  $url = trailingslashit($url);
  $rel = trailingslashit($rel);
  return ((strcasecmp($url, $rel) === 0) || dragonfruit_root_relative_url($url) == $rel);
}

function dragonfruit_is_element_empty($element) {
  $element = trim($element);
  return !empty($element);
}

/**
 * Clean up wp_nav_menu_args
 *
 * Remove the container
 * Remove the id="" on nav menu items
 */
function dragonfruit_nav_menu_args($args = '') {
  $nav_menu_args = [];
  $nav_menu_args['container'] = false;

  if (!$args['items_wrap']) {
    $nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
  }

  if (!$args['depth']) {
    $nav_menu_args['depth'] = 3;
  }

  return array_merge($args, $nav_menu_args);
}


add_filter('wp_nav_menu_args', 'dragonfruit_nav_menu_args');
add_filter('nav_menu_item_id', '__return_null');



register_nav_menus(array(
  'primary_navigation'    => 'Primary Navigation',
));
