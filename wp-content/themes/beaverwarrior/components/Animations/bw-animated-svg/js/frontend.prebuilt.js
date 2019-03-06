/**
 * Main class for our BWAnimatedSVG
 */
 BWAnimatedSVG = function( settings ) {
    // Set the settings
    this.elementContainerID         = settings.elementContainerID;
    this.lottieParams.loop          = settings.lottieParams.loop;
    this.lottieParams.animationData = settings.lottieParams.animationData;
    this.animateOnScroll            = settings.animateOnScroll;
    // Fire 'er up
    this.init();
};

BWAnimatedSVG.prototype = {
    /**
     * The element id of the container
     *
     * @type string
     */
     elementContainerID : null,

    /**
     * Whether or not to animation after scrolling to the element
     *
     * @type {Boolean}
     */
     animateOnScroll : false,

    /**
     * Whether or not the element is animated
     *
     * @type {Boolean}
     */
     elementHasAnimated : false,

    /**
     * The params we use with Lottie
     *
     * @type {Object}
     */
     lottieParams : {
        renderer      : 'svg',
        loop          : null,
        animationData : null
    },

    /**
     * Main method to init the object
     *
     * @return {void} 
     */
     init: function(){

        // First, create the animation object
        this.lotteAnimationObject = this._getLottieAnimationObject();
        // Remove the height atribute (it causes an issue on IE11)
        this._removeHeightAttribute();
        // If we're animating on scroll, then bind an observer
        if ( this.animateOnScroll ){
            this._bindIntersectionObserver();
        }
        // Otherwise, just animate onload
        else {
            this._playAnimation();
        }
    },

    /**
     * Method to create the animation via Lottie.
     *
     * @return object The Lottie animation object
     */
     _getLottieAnimationObject: function(){
        // Default params
        var params = {
            container     : document.getElementById( this.elementContainerID ),
            renderer      : this.lottieParams.renderer,
            loop          : this.lottieParams.loop,
            // We'll fine-tune control when it plays
            autoplay      : false,
            animationData : this.lottieParams.animationData
        };
        //  Bind the animation object
        return lottie.loadAnimation( params );
    },

     /**
      * Method to remove the height attribute from the lottie SVG (otherwise,
      * it won't scale on IE11).
      *
      * @return {void}
      */
     _removeHeightAttribute: function(){
        // Get the element container
        var container =   document.getElementById( this.elementContainerID ),
        // Get the SVG
        svg           = container.querySelectorAll( 'svg' )[0];
        // Unset the height attribute
        svg.removeAttribute( 'height' );
    },

    /**
     * This method actually plays the animation. This may be called at any time.
     *
     * @return {void}
     */
     _playAnimation: function(){
        // Here we go!
        this.lotteAnimationObject.play();
        // Mark the animation as complete
        this.elementHasAnimated = true;
    },

    /**
     * Initializes the intersection observer to update the odometers on scroll
     * 
     * @returns {void}
     */
     _bindIntersectionObserver: function(){
        // Declare self outside of block
        var self            = this,
        target              = document.getElementById( this.elementContainerID + '-observer' ),
        options             = {
            threshold: 1
        };
        var observer = new IntersectionObserver(function(entries){
            if ( entries[0].intersectionRatio > 0 && !self.elementHasAnimated ){
                self._playAnimation();
            }
        }, options);
        // Observe
        observer.observe( target );
    }
};
