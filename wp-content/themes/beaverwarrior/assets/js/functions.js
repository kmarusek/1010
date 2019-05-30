/**
 * Function used to get the height of the admin bar in pixels.
 *
 * @return {int} The height of the admin bar (in pixels)
 */
 function get_wp_admin_bar_height(){
    // Start by getting the admin bar
    var admin_bar = document.getElementById( 'wpadminbar' );
    // If the admin bar doesn't exist, just return zero
    if ( !admin_bar ){
        return 0;
    }
    // Otherwise, get the current height
    else {
        return admin_bar.offsetHeight;
    }
}

/**
 * Function for registering callbacks based on a window resize.
 *
 * @param     {Function}    callback                 The callback
 * @param     {object}      context                  The context for the callback
 * @param     {int}         window_resize_timeout    The timeout for the window resize
 *
 * @return    {void}        
 */
 function bind_callback_to_window_resize(callback, context, window_resize_timeout){
    if ( !window_resize_timeout ){
        window_resize_timeout = 500;
    }
    // To allow for the timeout
    var id;
    window.addEventListener("resize", function(){
        // Clear the timeout
        clearTimeout(id);
        // Create the function and callback
        id = setTimeout(function(){
            callback.call(context);
        }, window_resize_timeout);
    });
}

/**
 * Function used to get the height of the header in pixels.
 *
 * @return {int} The height of the header (in pixels)
 */
 function get_header_height(){
    // Start by getting the header
    var header = document.querySelector( 'header' );
    return header.offsetHeight;
}

/**
 * Function used to determine if the header is sticky or not.
 *
 * @return {bool} True if the header is sticky
 */
 function header_is_sticky(){
    // Start by getting the header
    var header = document.querySelector( 'header' );
    // Get the classes attached to the header
    header_classes = header.classList;
    return header_classes.contains( 'fl-theme-builder-header-sticky' );
}

function product_url_is_set_to_regular_pricing(){
    var window_hash = window.location.hash
    return window.location.hash.substr(1) !== 'autoship'
}