<?php
/**
 * @class DataNavMenu
 *
 */
class DataNavMenu extends BeaverWarriorFLModule
{
   /**
    * Parent class constructor
    *
    * @method __construct
    */
   public function __construct()
   {
      FLBuilderModule::__construct([
         'name'            => __('1010 Data Main Menu', 'fl-builder'),
         'description'     => __('Add custom menu to the site.', 'fl-builder'),
         'category'        => __('Data Menu', 'skeleton-warrior'),
         'dir'             => $this->getModuleDirectory( __DIR__ ),
         'url'             => $this->getModuleDirectoryURI( __DIR__ ),
         'editor_export'   => true,
         'enabled'         => true,
         'partial_refresh' => true
      ]);
   }

   /**
    * Get list of all created WP Menus
    *
    * @return @array   WP menu list, key => value pair
    *                  is menu slug and menu name
    */
   public static function getMenus()
   {
      // array of menus to return
      $menus = [
         '' => 'Pick One',
      ];
      $wpMenus = get_terms('nav_menu');
      foreach($wpMenus as $menu) {
         $menus[$menu->slug] = $menu->name;
      }

      return $menus;
   }
}

FLBuilder::register_module('DataNavMenu', array(
   'general' => array(
      'title' => __( 'General', 'fl-builder' ),
      'sections' => array(
         'site_log' => array(
            'title' => __( 'Top Bar & Site Logo', 'fl-builder' ),
            'fields' => array(
               'logo' => array(
                  'type'  => 'photo',
                  'label' => __( 'Site Logo', 'fl-builder' ),
               ),
                'top_bar_text' => array(
                    'type' => 'text',
                    'label' => __( 'Top Bar text:', 'fl-builder' ),
                ),
                'top_bar_link' => array(
                    'type'          => 'link',
                    'label' => __( 'Top Bar link:', 'fl-builder' ),
                    'show_target'   => true,
                    'show_nofollow' => true,
                )
            ),
         ),
         'choose_menu' => array(
            'title' => __( 'Menu', 'fl-builder' ),
            'fields' => array(
               'active_menu' => array(
                  'type' => 'select',
                  'label' => __( 'Choose the menu:', 'fl-builder' ),
                  'options' => DataNavMenu::getMenus()
               ),
               'mobile_menu' => array(
                  'type' => 'select',
                  'label' => __( 'Choose the menu:', 'fl-builder' ),
                  'options' => DataNavMenu::getMenus()
               ),
                'signin_button' => array(
                    'type' => 'text',
                    'label' => __( 'Sign In Button text:', 'fl-builder' ),
                    'default' => "Sign In"
                ),
                'signin_button_link' => array(
                    'type'          => 'link',
                    'label' => __( 'Sign In Button link:', 'fl-builder' ),
                    'show_target'   => true,
                    'show_nofollow' => true,
                ),
               'right_button' => array(
                  'type' => 'text',
                  'label' => __( 'Button text:', 'fl-builder' ),
                  'default' => "Contact Us"
               ),
               'button_link' => array(
                  'type'          => 'link',
                  'label' => __( 'Button link:', 'fl-builder' ),
                  'show_target'   => true,
                  'show_nofollow' => true,
               )
            )
         ),
         'dark_menu' => array(
            'title' => __( 'Light / Dark Menu', 'fl-builder' ),
            'fields' => array(
               'menu_type' => array(
                  'type' => 'select',
                  'default' => 'light',
                  'options' => array(
                     'light'    => 'Light',
                     'dark'    => 'Dark',
                  ),
               )
            )
         ),
      )
   ),
));

//add_filter( 'show_admin_bar' , '__return_false' );