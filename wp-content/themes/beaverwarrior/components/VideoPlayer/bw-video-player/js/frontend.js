"use strict";

jQuery(function ($) {
  /**
   * Main class for our BWVideoPlayer
   */
  BWVideoPlayer = function BWVideoPlayer(settings) {
    this.element = settings.element;
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
    settings: {},

    /**
     * The main method.
     *
     * @return {void}
     */
    init: function init() {
      // Declare self outside of block
      var self = this;
      this.nodeID = 'video-node-' + self.settings.id; // If this is an inline video, create the player object immediately

      if (self.settings.videoPlayerType === 'inline') {
        this.plyrObject = new Plyr(this.element.find('.video-container video'));
      } // Bind the play button


      this.element.find('.video-play-button').click(function () {
        // Handle our action
        switch (self.settings.videoPlayerType) {
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
    _playVideoInline: function _playVideoInline() {
      // Curtains up
      this.element.find('.video-inline-container').addClass('video-active'); // Figure out how to handle playing the video

      switch (this.settings.videoSource) {
        case 'youtube':
          this._playVideoInlineYouTube();

          break;

        default:
          this._playVideoInlineUpload();

          break;
      }
    },

    /**
     * Method used to play an inline video that is uploaded.
     *
     * @return {void}
     */
    _playVideoInlineUpload: function _playVideoInlineUpload() {
      var self = this; // Add our event listening. We need to reset this once the video is completed.

      this.plyrObject.on('ended', $.proxy(this._resetVideoPlayerInline, this)); // Get the content

      setTimeout(function () {
        // Play the video
        self.plyrObject.play();
      }, 1000);
    },

    /**
     * Method use to handle playing an inline video that is linked from YouTube.
     *
     * @return {void}
     */
    _playVideoInlineYouTube: function _playVideoInlineYouTube() {
      this.element.find('.video-source').uniqueId();
      var unique_id = this.element.find('.video-source').attr('id');
      var youtube_id = $('#' + unique_id).data('youtube-embed-id');

      if (!this.youTubePlayer) {
        this._initYouTubeVideo(unique_id, youtube_id);
      } else {
        this.youTubePlayer.playVideo();
      }
    },

    /**
     * Method used to init the YouTube video from any type of view.
     *
     * @param  {string} element_id The element ID to place the video
     * @param  {string} youtube_id The YouTube ID of the video to use
     *
     * @return {void}
     */
    _initYouTubeVideo: function _initYouTubeVideo(element_id, youtube_id) {
      this.youTubePlayer = new YT.Player(element_id, {
        videoId: youtube_id,
        events: {
          onReady: this._onYouTubePlayerReady.bind(this),
          onStateChange: this._onYouTubePlayerStateChange.bind(this)
        },
        playerVars: {
          enablejsapi: true
        }
      });
    },

    /**
     * Method to handle resetting the video player when set to inline.
     *
     * @return {void}
     */
    _resetVideoPlayerInline: function _resetVideoPlayerInline() {
      // Reset the cutains
      this.element.find('.video-inline-container.video-active').removeClass('video-active');

      switch (this.settings.videoSource) {
        default:
          this._resetVideoPlayerUpload();

          break;
      }
    },

    /**
     * Method used to add any logic required to cleanup the video player
     * for uploaded videos after it's finished running.
     *
     * @return {void}
     */
    _resetVideoPlayerUpload: function _resetVideoPlayerUpload() {
      // Declare self outside of block
      var self = this;
      setTimeout(function () {
        // Rewind the video
        self.plyrObject.restart();
      }, 300);
    },

    /**
     * Callback used to actually play the YouTube video.
     *
     * @param  {object} event The event sent from the YouTube API
     *
     * @return {void}     
     */
    _onYouTubePlayerReady: function _onYouTubePlayerReady(event) {
      event.target.playVideo();
    },

    /**
     * A callback for when the YouTube state changes. This is particularly useful
     * for checking the progress of the video.
     *
     * @param  {object} event The event sent from the YouTube API
     *
     * @return {void}     
     */
    _onYouTubePlayerStateChange: function _onYouTubePlayerStateChange(event) {
      if (event.data === 0) {
        if (this.settings.videoPlayerType === 'inline') {
          this._resetVideoPlayerInline();
        }
      }
    },

    /**
     * This method is called for creating the YouTube iFrame on the modal option
     *
     * @return {void}
     */
    _initModalVideoYouTube: function _initModalVideoYouTube() {
      $('.featherlight.' + this.nodeID + ' .video-source').uniqueId();
      var unique_id = $('.featherlight.' + this.nodeID + ' .video-source').attr('id');
      var youtube_id = $('#' + unique_id).data('youtube-embed-id');

      this._initYouTubeVideo(unique_id, youtube_id);
    },

    /**
     * Method used to init the modal when it contains an 
     *
     * @return {void}
     */
    _initModalVideoUpload: function _initModalVideoUpload() {
      // Declare self outside of block
      var self = this; // Find the video

      self.plyrObject = new Plyr($('.featherlight.' + self.nodeID + ' video')); // Play after a brief pause so we don't startle the user

      setTimeout(function () {
        self.plyrObject.play();
      }, 500);
    },

    /**
     * Method used to show the video modal. 
     *
     * @return {void}
     */
    _playVideoModal: function _playVideoModal() {
      // Declare self outside of block
      var self = this,
          // Get the content
      content = this.element.find('.video-content').html(); // Init the featherlight object

      this.videoModalObject = $.featherlight(content, {
        variant: self.nodeID + ' bw-video-player-modal',
        openSpeed: 0,
        closeSpeed: 0,
        beforeOpen: function beforeOpen() {
          $('body').addClass('bw-video-modal-open').addClass('bw-video-modal-open-' + self.nodeID);
        },
        afterOpen: function afterOpen() {
          $('.featherlight.' + self.nodeID).addClass('featherlight-visible');
        },
        afterClose: function afterClose() {
          $('body').removeClass('bw-video-modal-open').removeClass('bw-video-modal-open-' + self.nodeID);
        },
        afterContent: function afterContent() {
          switch (self.settings.videoSource) {
            case 'youtube':
              self._initModalVideoYouTube();

              break;

            default:
              self._initModalVideoUpload();

              break;
          }
        }
      });
    }
  };
});
//# sourceMappingURL=frontend.js.map
