<?php

class TPnavMenuWalker extends Walker_Nav_Menu
{
   function start_lvl(&$output, $depth = 0, $args = array())
   {
      $depth += 1;
      $indent = str_repeat("\t", $depth);
      $output .= "\n$indent<ul class=\"sub-menu DataNavMenu-depth_$depth\" >\n";
   }

   function end_lvl(&$output, $depth = 0, $args = array())
   {
      $output .= "</ul>";
   }

   function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
   {
   //set variables
      $classes= $item->classes;
      $title = $item->title;
      $description = $item->description;
      $url = $item->url;
      $chevron_right = '<i class="fas fa-chevron-right"></i>';
      $relative = get_field('spacing_classess', $item->ID);
      $subType = get_field('sub-menu_type', $item->ID);
      if ($relative[0] == 'relative'){
         $relative = 'DataNavMenu-' . $relative[0];
      } else {
         $relative = "";
      }


      /**
       * Sub-menu
       */
      if($args->walker->has_children && $depth == 0 && $subType != 'plain') {

         $output .= '<li class="' .  implode(" ", $classes) .' ' . $relative . '"><a class="DataNavMenu-parent_item " href="'.$url.'">'.$title.' <span class="DataNavMenu-expand collapsed"></span></a>';
         $output .= '<div class="DataNavMenu-sub_menu DataNavMenu-type--'.$subType.'">';
      }


      /**
       * First level sub-menu
       */

      elseif($depth == 1) {

         // VARs
         $sub_type = get_field('sub-menu_type', $item->ID);
         $parent_sub_type = get_field('sub-menu_type', $item->menu_item_parent);
         $featureicon = get_field('feature_image', $item->ID);
         $subMenuTitle = get_field('sub_menu_title', $item->ID);
         $url = $item->url;

         if($subMenuTitle == '') {
            $subMenuTitle = $title;
         }

         $output .= '<li class="DataNavMenu-sub_menu_item DataNavMenu-inner_depth_1' .  implode(" ", $classes) .' ' . $relative . ' DataNavMenu-'.$sub_type. '">
                     <a href="'.$url.'">';

         if($featureicon) {
            $output .= '<div class="DataNavMenu-sub_menu_item_icon">';
            $output .= '<img src="'.$featureicon.'">';
            $output .= '</div>';
         }
         if($parent_sub_type == 'simple'){
             $output .=  '<h5 class="DataNavMenu-title">'.$title.'</h5>';
         } else{
             $output .=  '<span class="DataNavMenu-arrow"><i class="fas fa-arrow-left"></i></span>'.$subMenuTitle;

         }

         if ($description) {
            $output .= '<p class="DataNavMenu-description">' . $description . '</p>';
         }

          $output .= '</a>';
      }

      /**
       * Second level sub-menu
       */

      elseif($depth == 2) {
         //var
         $parent_sub_type = get_field('sub-menu_type', $item->menu_item_parent);
         $featureicon = get_field('feature_image', $item->ID);
         $button_text = get_field('button_text', $item->ID);
         $button_link = get_field('button_link', $item->ID);
         $sub_type = get_field('sub-menu_type', $item->ID);
         $extra_class = '';
         if(get_field('spacing_classess', $item->ID)) {

            $extra_class = get_field('spacing_classess', $item->ID);
         }


         $output .= '<li class="DataNavMenu-inner_depth_2' .  implode(" ", $classes) . ' DataNavMenu-'.$sub_type .' '. $extra_class[0]. '">';


         //menu with icon
         if ($sub_type == "with_icon") {
            $output .= '<a href="'.$button_link.'">';
            $output .= '<div class="DataNavMenu-icon">';
            $output .= '<img src="'.$featureicon.'">';
            $output .= '</div>';

            $output .= '<p class="DataNavMenu-icon--title">'.$title.'</p>';
            $output .= '<p class="DataNavMenu-icon--description">' . $description . '</p>';
            $output .= '<p class="DataNavMenu-icon--read_more">'.$button_text.' <span class="DataNavMenu-link_arrow"></span></p>';
            $output .= '</a>';
         } else {
            $output .= '<a href="'.$url.'">'.$title.'</a>';
         }
          if ($description) {
              $output .= '<p class="DataNavMenu-description">' . $description . '</p>';
          }

      }

      elseif($depth == 3) {
         //var
         $sub_type = get_field('sub-menu_type', $item->ID);
         $featureicon = get_field('feature_image', $item->ID);
         $button_text = get_field('button_text', $item->ID);
         $button_link = get_field('button_link', $item->ID);


         $output .= '<li class="DataNavMenu-inner_depth_3' .  implode(" ", $classes) . ' DataNavMenu-'.$sub_type. '">';


         //menu with icon
         if ($sub_type == "with_icon") {
            $output .= '<a href="'.$button_link.'">';
            $output .= '<div class="DataNavMenu-icon">';
            $output .= '<img src="'.$featureicon.'">';
            $output .= '</div>';

            $output .= '<p class="DataNavMenu-icon--title">'.$title.'</p>';
            $output .= '<p class="DataNavMenu-icon--description">' . $description . '</p>';
            $output .= '<p class="DataNavMenu-icon--read_more">'.$button_text.' <span class="DataNavMenu-link_arrow"></span></p>';
            $output .= '</a>';
         }

      }

      /**
       * Single item
       */
      else {
         $output .= '<li class="' .  implode(" ", $classes) . '"><a class="DataNavMenu-parent_item" href="' . $url . '">'.$title;
      }
   }

   function end_el(&$output, $item, $depth=0, $args=null)
   {
      /**
       * Sub-menu
       */
      if($depth == 0 && get_field('sub-menu_type', $item->ID) != 'plain') {
         $subType = get_field('sub-menu_type', $item->ID);

      }

      /**
       * Plain item
       */
      else {
         $output .= '</a></li>';
      }

   }
}


