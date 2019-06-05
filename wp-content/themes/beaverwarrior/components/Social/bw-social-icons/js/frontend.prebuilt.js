/* jshint ignore:start */
@import '../../../../assets/js/class-bw-module-frontend.js';
/* jshint ignore:end */

/**
 * The main class used for the social icons.
 */
 class BWSocialIcons extends BWModuleFrontend {

    /**
     * Method automatically called by the superclass
     *
     * @return {void} 
     */
     init(){

        this.elements.socialIconsContainerElement = this.element.querySelector( '.social-icons-container' );

        // If we need to affix the social share icon container, do that now.
        if ( this.settings.isSticky ){
            this._bindAffixToShareIconsContainer();
            // Recalculate on resize
            bind_callback_to_window_resize( this._reinitAffix, this );
        }
    }

    /**
     * Callback used to reinit affix. Used after a window is resized
     *
     * @return {void}
     */
     _reinitAffix(){
        // Destroy affix
        jQuery(window).off('.affix');
        jQuery( this.elements.socialIconsContainerElement )
        .removeClass("affix affix-top affix-bottom")
        .removeData("bs.affix");
        // Reinit
        this._bindAffixToShareIconsContainer();
    }

    /**
     * Method to handle binding the affix action to the share icons container.
     *
     * @return {void}
     */
     _bindAffixToShareIconsContainer(){
        let $ = jQuery,
        self = this,
        // Get the offset of the element
        module_offset              =  this.elements.socialIconsContainerElement.getBoundingClientRect().top,
        // The WordPress admin bar height
        wordpress_admin_bar_height = get_wp_admin_bar_height(),
        // Get the header height
        header_height              = get_header_height(),
        // The total offset is the header height plus the admin bar
        // height
        total_offset = window.scrollY + module_offset - wordpress_admin_bar_height - header_height;

        this.log( 'Calculated values:', {'Offset values:':
            [{'Module offset'      : module_offset}, 
            {'WP Admin bar height' : wordpress_admin_bar_height}, 
            {'Height height'       : header_height}, 
            {'Total offset'        : total_offset}]
        });

        $( this.elements.socialIconsContainerElement )
        .on( 'affix.bs.affix', function(){
            $( this )
            .css({
                top: ( header_height + wordpress_admin_bar_height ) + 'px'
            });
        })
        .on( 'affix-top.bs.affix', function(){
            $( this )
            .removeAttr( 'style' );
        })
        .affix({
            offset: {
                top: total_offset
            }
        });

        this.log( 'Affixing share icon container' );
    }
}