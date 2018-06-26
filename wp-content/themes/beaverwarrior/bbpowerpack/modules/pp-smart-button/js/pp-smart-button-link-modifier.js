(function($){
    $(function(){
        // Stop evaluation if GA doesn't exist
        if (typeof ga === "undefined") { 
            console.error("Error! No GA accounts set up!");
            return;
        }
        else {
            // Wrap everything in the GA function
            ga(function() {
                // Declare our object for all tracker names
                var tracker_names = [],
                // Get all trackers
                trackers = ga.getAll();
                // Loop through all trackers
                for (var i = 0; i < trackers.length; i++){
                    // Declare this node
                    var node = trackers[i],
                    // Get this tracker name
                    tracker_name = node.get('name');
                    // Add to the tracker_names array
                    tracker_names.push(tracker_name);
                }
                // If we don't have GA set up, warn us
                if (tracker_names.length < 1){
                    console.error("Error! Unable to find GA tracker names!");
                    return;
                }
                // Reformat all links
                $('.' + smart_button_event_object.smart_button_class)
                .each(function(){
                    // Get this link category
                    var link_category = $(this).data('ga-category') ? "'" + $(this).data('ga-category') + "'" : null,
                    // Get this link action
                    link_action = $(this).data('ga-action') ? "'" + $(this).data('ga-action') + "'" : null,
                    // Get this link label
                    link_label = $(this).data('ga-label') ? "'" + $(this).data('ga-label') + "'" : null,
                    // Get this link value
                    link_value = $(this).data('ga-value') ? $(this).data('ga-value') : null,
                    // The new onClick attribute for this element
                    on_click_attribute = '';
                    // Send an event for each tracker name
                    for (var i = 0; i < tracker_names.length; i++){
                        // Get the name
                        var gtm_name = tracker_names[i];
                        // The event
                        on_click_attribute += "ga('" + gtm_name + ".send','event'," + link_category + "," + link_action + "," + link_label + "," + link_value + ");";
                    }
                    // Add the attribute
                    $(this)
                    .attr('onclick', on_click_attribute);
                });
            });
        }
    });
})(jQuery);