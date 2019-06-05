jQuery(function($){

    /**
     * Main class for our BWVideoPlayer
     */
     BWVideoPlayer = function( settings ) {
        this.element  = settings.element;
        this.settings = settings;
        this.init();
    };

    BWVideoPlayer.prototype = {

        /**
         * The node id for this instance.
         *
         * @type {String}
         */
         nodeID: '',

        /**
         * Our element
         *
         * @type {Object}
         */
         element: '',

        /**
         * Where to store all settings
         *
         * @type {Object}
         */
         settings : {},

        /**
         * The main method.
         *
         * @return {void}
         */
         init : function(){
            // Declare self outside of block
            var self = this;

            this.nodeID = 'video-node-' + self.settings.id;

            // If this is an inline video, create the player object immediately
            if ( self.settings.videoPlayerType === 'inline' ){
                this.plyrObject = new Plyr( this.element.find( '.video-container video')  );
            }

            // Bind the play button
            this.element
            .find( '.video-play-button' )
            .click(function(){
                // Handle our action
                switch( self.settings.videoPlayerType ){

                    // For modals
                    case 'modal':
                    self._playVideoModal();
                    break;

                    // For inline
                    case 'inline':
                    self._playVideoInline();
                    break;
                }
            });
        },

        /**
         * Method used to handle playing the inline video. 
         *
         * @return {void}
         */
         _playVideoInline: function(){
            // Declare self outside of block
            var self = this;
            // Curtains up
            this.element
            .find( '.video-inline-container' )
            .addClass( 'video-active' );
            // Add our event listening. We need to reset this once the video is completed.
            self.plyrObject.on( 'ended', $.proxy(self._resetVideoPlayerInline, self ) );
            // Get the content
            setTimeout(function(){
                // Play the video
                self.plyrObject.play();
            }, 1000 );
        },

        /**
         * Method to handle resetting the video player when set to inline.
         *
         * @return {void}
         */
         _resetVideoPlayerInline: function(){
            // Declare self outside of block
            var self = this;
            // Reset the cutains
            this.element
            .find( '.video-inline-container.video-active' )
            .removeClass( 'video-active' );
            setTimeout(function(){
                // Rewind the video
                self.plyrObject.restart();
            }, 300 );
        },

        /**
         * Method used to show the video modal. 
         *
         * @return {void}
         */
         _playVideoModal: function(){
            // Declare self outside of block
            var self = this,
            // Get the content
            content = this.element.find( '.video-content' ).html();
            // Init the featherlight object
            this.videoModalObject = $.featherlight( content, {
                variant    : self.nodeID + ' bw-video-player-modal',
                openSpeed  : 0,
                closeSpeed : 0,
                beforeOpen : function(){
                    $('body')
                    .addClass( 'bw-video-modal-open' )
                    .addClass( 'bw-video-modal-open-' + self.nodeID );
                },
                afterOpen : function(){
                    $('.featherlight.' + self.nodeID )
                    .addClass( 'featherlight-visible' );
                },
                afterClose : function(){
                    $( 'body')
                    .removeClass( 'bw-video-modal-open' )
                    .removeClass( 'bw-video-modal-open-' + self.nodeID );
                },
                afterContent : function(){
                    // Find the video
                    self.plyrObject = new Plyr( $('.featherlight.' + self.nodeID + ' video')  );
                    // Play after a brief pause so we don't startle the user
                    setTimeout(function(){
                        self.plyrObject.play();
                    }, 500);
                }
            });
        }
    };
});