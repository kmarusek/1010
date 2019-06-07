jQuery(function($){

    /**
     * Main class for our BWNavigationPopover
     */
     BWNavigationPopover = function( settings ) {
        // The element we're working with
        this.element               = settings.element;
        this.popoverHeadersEnabled = settings.popoverHeadersEnabled;
        this.popoverPointerEnabled = settings.popoverPointerEnabled;
        this.popoverCustomContent  = settings.popoverCustomContent;
        // Init the navigation 
        this.init();
    };

    BWNavigationPopover.prototype = {

        elements: {

            menuItemsHasChildren : '.menu-item-has-children',
            
            menuItemsPrimary     : '.mega-menu-container > li.menu-item'

        },

        classes : {

        },

        timing : {
        },

        init: function(){
            this._initPopovers();

            this._bindHidePopoversOnHover();

            this.element
            .data('BWNavigationPopoverObject', this );

        },

        _initPopovers: function(){  

            // Declare self outside of block
            var self = this;

            this.element
            .find( this.elements.menuItemsHasChildren )
            .each(function(){

                $( this )
                .uniqueId();
                // Get the unique ID
                var unique_id = $( this ).attr( 'id' ); 

                $( this )
                .popover({
                    trigger   : 'hover',
                    html      : true,
                    content   : self._getDropDownContent( this ),
                    placement : 'bottom',
                    container : '#' + unique_id,
                    template  : self._getDropDownTemplate(),
                    delay     : { 
                        show: 0,
                        hide: 250 
                    }
                });
            });
        },

        _bindHidePopoversOnHover: function(){

            // Declare self outside of block
            var self = this;

            this.element
            .find( this.elements.menuItemsPrimary )
            .mouseenter(function(){
                self._hideSiblingPopovers( $(this) );
            });
        },

        _demoShowFirstPopover: function(){
            this.element
            .find( this.elements.menuItemsHasChildren )
            .eq(0)
            .each(function(){
                $(this)
                .popover('show');
            });
        },

        _demoHideFirstPopover: function(){
            this.element
            .find( this.elements.menuItemsHasChildren )
            .eq(0)
            .each(function(){
                $(this)
                .popover('hide');
            });
        },

        _hideSiblingPopovers: function( current_element ){

            this.element
            .find( this.elements.menuItemsHasChildren )
            .not( current_element )
            .each(function(){
                $( this )
                .popover('hide');
            });
        },

        _getDropDownContent : function( element ){

            var section_title = $( element ).data( 'mega-menu-section-title' ),
            drop_down_menu    = $( element ).find( '.mega-menu-contents' ).html();
            if ( this.popoverHeadersEnabled ){
                return '<p class="section-title">' + section_title + '</p>' + drop_down_menu;
            }
            else {
                return drop_down_menu;
            }
        },

        _getDropDownTemplate : function( ){

            // Our tooltip
            var tooltip_string = this.popoverPointerEnabled ? "<div class=\"triangle-container\"><div class=\"triangle\"></div></div>" : '',
            has_tooltip_class  = this.popoverPointerEnabled ? 'has-tooltip' : '';

            return "<div class=\"popover " + has_tooltip_class + "\" role=\"tooltip\">" + tooltip_string + "<h3 class=\"popover-title\"></h3><div class=\"popover-content\"></div>" + this.popoverCustomContent + "</div>";
        }
    };
});