jQuery(function($){

    /**
     * Main class for our BWPostsGrid
     */
     BWPostsGrid = function( settings ) {
        this.element  = settings.element;
        this.settings = settings;
        this.init();
    };

    BWPostsGrid.prototype = {

        timing: {
            transitionOpacityInMilliseconds : 350,
            transitionHeightInMilliseconds : 500
        },

        init: function(){

            // Init the pagination
            this._initPagination();
        },

        _initPagination: function(){
            // Scope
            var self = this;
            // Init the pagination if enabled
            if ( this.settings.paginationEnabled ){
                // Paginate everything
                this.paginationObject = this.element
                .find( '.posts-pagination-container')
                .pagination({
                    dataSource: this.settings.dataSource,
                    pageSize: this.settings.pageSize,
                    prevText: 'Prev',
                    nextText: 'Next',
                    afterPageOnClick: function(){
                        self._navigateToNewPage();
                    },
                    afterNextOnClick: function(){
                        self._navigateToNewPage();
                    },
                    afterPreviousOnClick: function(){
                        self._navigateToNewPage();
                    }
                });
            }
        },

        /**
         * Method used as the callback for navigating to a new page.
         *
         * @return {void}
         */
         _navigateToNewPage: function(){
            // Scope
            var self = this;
            self._scrollToPostsContainer();
            self._hidePostsContainer();
            // We need to evaluate this after the posts are done fading out
            setTimeout(function(){
                self._evaluateActivePosts();
            }, self.timing.transitionOpacityInMilliseconds );
            setTimeout(function(){
                self._showPostsContainer();
            }, self.timing.transitionOpacityInMilliseconds + self.timing.transitionHeightInMilliseconds );
        },

        /**
         * Method to scrool to the top of the posts container
         *
         * @return {void}
         */
         _scrollToPostsContainer: function(){
            // Before we do the smooth scroll, we need to calculate the offset. By default, that offset is 0
            var offset = 0;
            // If we have a WP Admin bar, add that in
            if ( $( '#wpadminbar').length > 0 ){
                offset += $( '#wpadminbar').outerHeight();
            }
            // If the header is fixed, then we need to take that into account to
            if ( $('header').hasClass( 'fl-theme-builder-header-sticky' ) ){
                // Get the header height and add that to the offset
                offset += $( 'header' ).outerHeight();
            }

            // Do the smooth scroll
            $.smoothScroll({
                scrollTarget: this.element,
                offset: -1 * offset,
                speed: 'auto',
                autoCoefficient: 1.75
            });
        },

        /**
         * Method used to hide the posts container.
         *
         * @return {void}
         */
         _hidePostsContainer: function(){
            // Fade out the container
            this.element
            .find( '.posts-container' )
            .addClass( 'fade-out' );
        },

        /**
         * Method used to show the posts container.
         *
         * @return {void}
         */
         _showPostsContainer: function(){
            // Fade out the container
            this.element
            .find( '.posts-container.fade-out' )
            .removeClass( 'fade-out' );
        },

        /**
         * Method used to show the active psots based on the current page the user is on in the pagination.
         *
         * @return {void}
         */
         _evaluateActivePosts: function(){
            var self = this,
            // Start by getting the IDs of all newly active items
            new_active_items = this.paginationObject.pagination('getSelectedPageData'),
            // Get the current height of the posts container
            post_container_current_height = this.element.find( '.posts-container' ).outerHeight();
            // Hide all posts by default
            this.element
            .find( '.posts-container' )
            // Keep the height for a moment
            .css({
                'min-height' : post_container_current_height + 'px'
            })
            .find( '> li.post-active' )
            .removeClass( 'post-active' );
            // Now that everything is hidden, go though the newly active items and unhide them
            for ( var i=0; i<new_active_items.length; i++ ){
                // Get the ID
                var post_id = new_active_items[i];
                // Show the post
                this.element
                .find( '.posts-container > li[data-post-id=' + post_id + ']' )
                .addClass( 'post-active' );
            }
            // Remember how we persisted the height? Yeah, now we should adjust it
            var cloned_posts_container = this.element.find('.posts-container').clone();
            // Make sure this thing is invisible
            cloned_posts_container.addClass( 'invisible' );
            // And remove the height attribute
            cloned_posts_container.removeAttr( 'style' );
            // Add it to the DOM
            this.element.find('.posts-container').after( cloned_posts_container );
            // Now get the height of the element
            var post_container_new_height = this.element.find('.posts-container.invisible').outerHeight(),
            // Our little buffer
            height_animation_delay = 100;
            // This should match up with the current element. Remove our temp comtainer after a pause
            setTimeout(function(){
                self.element
                .find('.posts-container.invisible').remove()
                .end()
                .find( '.posts-container' )
                // Add our new height
                .css({
                    'min-height' : post_container_new_height + 'px'
                });
            }, height_animation_delay );
            // Remove the height attribute
            setTimeout(function(){
                self.element
                .find( '.posts-container' )
                .removeAttr( 'style' );
            }, height_animation_delay );
        }
    };
});