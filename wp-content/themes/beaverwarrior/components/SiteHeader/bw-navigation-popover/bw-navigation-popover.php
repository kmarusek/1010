<?php
/**
 * @class BWNavigationPopover
 */
class BWNavigationPopover extends BeaverWarriorFLModule {

    /**
     * Parent class constructor.
     * 
     * @method __construct
     */
    public function __construct(){
        FLBuilderModule::__construct(
            array(
                'name'          => __( 'Popover Navigation', 'skeleton-warrior'),
                'description'   => __( 'A menu with popovers that appear when hovering over ancestor items.', 'skeleton-warrior'),
                'category'      => __( 'Navigation', 'skeleton-warrior'),
                'dir'           => $this->getModuleDirectory( __DIR__ ),
                'url'           => $this->getModuleDirectoryURI( __DIR__ ),
                'editor_export' => true,
                'enabled'       => true
            )
        );
    }

    /**
     * Method to retieve the menu ID for this navigation.
     *
     * @return int The menu ID
     */
    public function getMenuID(){
        // Get the menu we're using
        return $this->settings->menu;
    }

    /**
     * Method to return whether or not menu icons are enabled.
     *
     * @return bool True if menu icons are enabled
     */
    public function menuIconsAreEnabled(){
        return $this->settings->show_menu_icon === 'enabled';
    }

    /**
     * Method to return whether or not popover section titles are enabled.
     *
     * @return bool True if popover section titles are enabled
     */
    public function popoverSectionTitlesAreEnabled(){
        return $this->settings->popover_section_titles_enabled === 'enabled';
    }

    /**
     * Method to return whether or not top-level menu icons are enabled.
     *
     * @return bool True if top-level menu icons are enabled
     */
    public function topLevelMenuIconEnabled(){
        return $this->settings->show_top_level_menu_icon === 'enabled';
    }

    /**
     * Method to return whether or not the popover pointer is enabled.
     *
     * @return bool True if the popover pointer is enabled
     */
    public function popoverPointerIsEnabled(){
        return $this->settings->include_popover_pointer === 'enabled';
    }

    /**
     * Method to get the top-level menu icon.
     *
     * @return string The top-level menu with the icon
     */
    public function getTopLevelMenuIconElement(){
        $return_element = '';

        if ( $this->topLevelMenuIconEnabled() ){
            // Add the icon
            $return_element .= sprintf(
                '<span class="top-level-item-icon %s"><i class="%s icon-primary"></i>%s</span>',
                // If we need to have hover behavior
                $this->settings->top_level_menu_icon_hover ? 'top-level-item-has-hover-icon' : '',
                // The icon
                $this->settings->top_level_menu_icon,
                // If we need to have hover behavior
                $this->settings->top_level_menu_icon_hover ? '<i class="' . $this->settings->top_level_menu_icon_hover . ' icon-hover"></i>' : ''
            );
        }

        return $return_element;
    }
}

/**
 * Our menu walker
 */
class BWNavigationPopoverMenuWalker extends Walker_Nav_Menu {

    const CLASS_MENU_HAS_CHILDREN = 'menu-item-has-children';

    public function __construct( object $module ){
        $this->module = $module;
    }

    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        // Get the menu parent
        $menu_parent = $item->menu_item_parent;
        // Get the menu parent
        if ( $menu_parent == 0 ){
            // Add to the top level items
            $output .= $this->startMenuItemLevelOne( $output, $item, $depth, $args, $id );
        }
        else {
            $output .= $this->startMenuItemLevelTwo( $output, $item, $depth, $args, $id );
        }
    }

    public function end_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        // Get the menu parent
        $menu_parent = $item->menu_item_parent;
        // For main items
        if ( $menu_parent == 0 ){
            $output .= $this->endMenuItemLevelOne( $output, $item, $depth, $args, $id );
        }
        else {
            $output .= $this->endMenuItemLevelTwo( $output, $item, $depth, $args, $id );
        }
    }

    private function startMenuItemLevelOne( &$output, $item, $depth, $args, $id ){

        if ( $this->menuItemHasChildren( $item )){
            $mega_menu_title = $item->description !== ' ' && $item->description !== '' && $item->description ? $item->description : $item->title;
            $output .= sprintf(
                '<li class="%s" data-mega-menu-section-title="%s">
                <a href="%s" target="%s" title="%s">%s%s</a>
                <div class="mega-menu-contents">',
                // The classes
                implode( ' ', $item->classes ),
                // The menu title
                $mega_menu_title,
                // The menu url
                $item->url,
                // The menu url target
                $item->target,
                // The menu url title
                $item->attr_title,
                // The menu item name
                $item->title,
                // Get the top level menu icon element (if needed)
                $this->module->getTopLevelMenuIconElement()
            );
        }
        else {
            $output .= sprintf(
                '<li class="%s">
                <a href="%s" target="%s" title="%s">%s</a>',
                // The classes
                implode( ' ', $item->classes ),
                // The menu url
                $item->url,
                // The menu url target
                $item->target,
                // The menu url title
                $item->attr_title,
                // The menu item name
                $item->title
            );

        }
    }

    private function endMenuItemLevelOne( &$output, $item, $depth, $args, $id ){
        if ( $this->menuItemHasChildren( $item )){
            $output .= sprintf(
                '</div>
                </li>'
            );
        }
        else {
            $output .= sprintf(
                '</li>'
            );
        }
    }

    private function startMenuItemLevelTwo( &$output, $item, $depth, $args, $id ){
        if ( $item->description === '' || $item->description === ' ' ){
            $output .= sprintf(
                '<li class="%s"><a href="%s" target="%s" title="%s"><i class="icon"></i><span>%s</span></a></li>',
                implode(' ', $item->classes ),
                // The menu url
                $item->url,
                // The menu url target
                $item->target,
                // The menu url title
                $item->attr_title,
                // The menu item name
                $item->title
            );
        }
        else {
            $output .= sprintf(
                '<li class="contains-description %s"><a href="%s" target="%s" title="%s"><i class="icon"></i><ul><li class="term">%s</li><li class="description">%s</li></ul></a></li>',
                implode(' ', $item->classes ),
                // The menu url
                $item->url,
                // The menu url target
                $item->target,
                // The menu url title
                $item->attr_title,
                // The menu item name
                $item->title,
                // The menu description
                $item->description
            );

        }
    }

    private function endMenuItemLevelTwo( &$output, $item, $depth, $args, $id ){

    }

    private function menuItemHasChildren( object $item ){
        return in_array( self::CLASS_MENU_HAS_CHILDREN, $item->classes );
    }
}


/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
    'BWNavigationPopover', array(
        'general'       => array( 
            'title'         => __('General', 'skeleton-warrior'),
            'sections'      => array(
                'general'       => array(
                    'title'    => __( '', 'skeleton-warrior'),
                    'fields'        => array(
                        'menu' => array(
                            'type'    => 'select',
                            'label'   => __( 'Menu', 'skeleton-warrior' ),
                            'options' => BWNavigationPopover::_get_menus()
                        ),
                        'show_top_level_menu_icon' => array(
                            'type'    => 'select',
                            'label'   => __( 'Top-level icons', 'skeleton-warrior' ),
                            'default' => 'disabled',
                            'help'    => 'Choose whether you\'d like to have icons to the right of top-level items that have children.',
                            'options' => array(
                                'disabled' => 'Disabled',
                                'enabled'  => 'Enabled'
                            ),
                            'toggle' => array(
                                'enabled' => array(
                                    'tabs'=> array(
                                        'menu_icons'
                                    )
                                )
                            )
                        )
                    )
                ),
                'general_submenu' => array(
                    'title'    => __( 'Submenu', 'skeleton-warrior'),
                    'fields' => array(
                        'popover_section_titles_enabled' => array(
                            'type'    => 'select',
                            'label'   => __( 'Popover titles', 'skeleton-warrior' ),
                            'default' => 'disabled',
                            'options' => array(
                                'disabled' => 'Disabled',
                                'enabled'  => 'Enabled'
                            ),
                            'toggle' => array(
                                'enabled' => array(
                                    'fields'=> array(
                                        'typography_popover_header',
                                        'color_popover_header'
                                    )
                                )
                            )
                        ),
                        'show_menu_icon' => array(
                            'type'    => 'select',
                            'label'   => __( 'Submenu icons', 'skeleton-warrior' ),
                            'default' => 'disabled',
                            'help'    => 'Submenu icons are small icons that will be placed to the left of your menu name. If enabled, you can specify all your icons and name your classes that you\'ll attach to the menu items in the WordPress menu editor',
                            'options' => array(
                                'disabled' => 'Disabled',
                                'enabled'  => 'Enabled'
                            ),
                            'toggle' => array(
                                'enabled' => array(
                                    'tabs'=> array(
                                        'submenu_icons'
                                    )
                                )
                            )
                        )
                    )
                )
            )
        ),  //
        'menu_icons' => array(  //
            'title'    => __( 'Menu icons', 'skeleton-warrior'),
            'sections' => array(
                'style_top_level' => array(
                    'title'  => __('', 'skeleton-warrior'),
                    'fields' => array(
                        'top_level_menu_icon' => array(
                            'type'    => 'icon',
                            'label'   => __( 'Icon', 'skeleton-warrior' ),
                            'help'    => 'This icon will show up next to top-level items with children.'
                        ),
                        'font_size_top_level_menu_icon' => array(
                            'type'    => 'unit',
                            'label'   => __( 'Icon size', 'skeleton-warrior' ),
                            'units'   => array( 'px' ),
                            'default' => 15,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.mega-menu-container > li a .top-level-item-icon .icon-primary',
                                'property' => 'font-size'
                            )
                        ),
                        'top_level_menu_icon_hover' => array(
                            'type'    => 'icon',
                            'label'   => __( 'Icon (hover)', 'skeleton-warrior' ),
                            'help'    => 'Optionally, you may choose an icon to replace the regular top-level icon when hovered over.'
                        ),
                        'font_size_top_level_menu_icon_hover' => array(
                            'type'    => 'unit',
                            'label'   => __( 'Icon size (hover)', 'skeleton-warrior' ),
                            'units'   => array( 'px' ),
                            'default' => 15,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '.mega-menu-container > li a .top-level-item-icon .icon-hover',
                                'property' => 'font-size'
                            )
                        )
                    )
                )
            )
        ),
        'submenu_icons' => array(  //
            'title'    => __( 'Submenu icons', 'skeleton-warrior'),
            'sections' => array(
                'style_top_level' => array(
                    'title'  => __('', 'skeleton-warrior'),
                    'fields' => array(
                        'menu_icons_repeater' => array(
                            'type'         => 'form',
                            'label'        => __( 'Submenu icon class', 'skeleton-warrior' ),
                            'multiple'     => true,
                            'form'         => 'bw_navigation_popover_menu_icon_classes', 
                            'preview_text' => 'menu_icon_class_name',
                        )
                    )
                )
            )
        ),
        'typography' => array( 
            'title'    => __('Typography', 'skeleton-warrior'),
            'sections' => array(
                'typography_top_level' => array(
                    'title'    => __('Top-Level items', 'skeleton-warrior'),
                    'fields' => BWNavigationPopover::getSettingsFromFile( 'typography-top-level.php' )
                ),
                'typography_popover' => array(
                    'title'    => __('Popover', 'skeleton-warrior'),
                    'fields' => BWNavigationPopover::getSettingsFromFile( 'typography-popover.php' )
                )
            )
        ),
        'style' => array( 
            'title' => __('Style', 'skeleton-warrior'),
            'sections' => array(
                'style_top_level' => array(
                    'title'    => __('Top-Level items', 'skeleton-warrior'),
                    'fields' => BWNavigationPopover::getSettingsFromFile( 'style-top-level.php' )
                ),
                'style_popover' => array(
                    'title'    => __('Popover', 'skeleton-warrior'),
                    'fields' => BWNavigationPopover::getSettingsFromFile( 'style-popover.php' )
                )
            )
        )
    )
);

/*
 * Register the settings for each of the slides in the slider
 */
FLBuilder::register_settings_form(
    'bw_navigation_popover_menu_icon_classes', 
    array(
        'title' => __( 'Add class', 'fl-builder' ),
        'tabs'  => array(
            'general'      => array(
                'title' => __( 'General', 'fl-builder' ),
                'sections'      => array(
                    'general' => array(
                        'fields' => array(
                            'menu_icon_class_name' => array(
                                'type'  => 'text',
                                'label' => __('Submenu icon class', 'fl-builder')
                            ),
                            'menu_icon_image' => array(
                                'type'  => 'photo',
                                'label' => __('Submenu icon', 'fl-builder')
                            ),
                        )
                    )
                )
            )
        )
    )
);
