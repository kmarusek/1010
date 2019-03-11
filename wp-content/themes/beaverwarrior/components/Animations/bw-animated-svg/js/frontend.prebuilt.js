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
      * Remove the height style attribute if we're on these browsers.
      *
      * @type {Array}
      */
      unsupportedBrowsers : [
      'internet-explorer11'
      ],

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
        // If we're on an older browser...
        if ( this._onUnsupportedBrowser() ){
            // Remove the height style (it causes an issue on IE11)
            this._removeHeightStyle();
        }
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
     * Method for determinding if we're on an oldie of a browser
     *
     * @return {boolean} True if on an older browser
     */
     _onUnsupportedBrowser: function(){
        var html_classes = document.documentElement.className.split( ' ' ),
        on_older_browser = false,
        i = 0;
        while ( !on_older_browser && i < html_classes.length ){
            if ( this.unsupportedBrowsers.includes( html_classes[i] ) ){
                on_older_browser = true;
            }
            i++;
        }
        return on_older_browser;
    },

     /**
      * Method to remove the height attribute from the lottie SVG (otherwise,
      * it won't scale on IE11).
      *
      * @return {void}
      */
      _removeHeightStyle: function(){   
        // Get the element container
        var container            =   document.getElementById( this.elementContainerID ),
        // Get the SVG
        svg                      = container.querySelectorAll( 'svg' )[0],
        // Get the existing styles
        existing_style_attribute = svg.getAttribute( 'style' ),
        // Turn into an array (and remove all empty items)
        existing_style_array     = existing_style_attribute.split(';').filter( Boolean ),
        // Make our new array for the style attributes
        new_style_array          = [];
        // Loop through the styles
        for ( var i = 0; i < existing_style_array.length; i++ ){
            // Get the current style
            var current_style = existing_style_array[i].trim(),
            // Get the key for this style
            style_key = current_style.split(':')[0];
            // If the style key is anything besides height, then add it back
            // to the new style array
            if ( style_key !== 'height' ){
                // Add it
                new_style_array.push( current_style );
            }
        }
        // Get the new style attribute
        var new_style_attribute = new_style_array.join( '; ' ) + ';';
        // Set the new style attribute
        svg.setAttribute( 'style', new_style_attribute );
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
