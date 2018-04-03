/*jslint continue: true, es5: true*/
/*global detectZoom, console, jQuery, define, Float32Array, Uint16Array*/
(function (root, factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        define("UTM", ["jquery", "Behaviors"], factory);
    } else {
        root.UTM = factory(root.jQuery, root.Behaviors);
    }
}(this, function ($, Behaviors) {
    "use strict";
    var module = {},
        utm_variables = {},
        wanted_vars = ["utm_source", "utm_medium", "utm_campaign", "utm_term", "utm_content"];

    function getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split('&');
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split('=');
            if (decodeURIComponent(pair[0]) == variable) {
                return decodeURIComponent(pair[1]);
            }
        }
    }

    /* Given a query string, return an object whose keys are matched UTM vars.
     */
    function look_for_utm_variables() {
        var new_utm_variables = {}, i;

        for (i = 0; i < wanted_vars.length; i += 1) {
            new_utm_variables[wanted_vars[i]] = getQueryVariable(wanted_vars[i]);
        }

        return new_utm_variables;
    }

    function do_utm_replace($context) {
        utm_variables = look_for_utm_variables();

        $context.find("a[href]").each(function (index, elem) {
            var k, $elem = $(elem),
                old_href = $elem.attr("href");

            for (k in utm_variables) {
                if (utm_variables.hasOwnProperty(k) && utm_variables[k] !== undefined) {
                    if (old_href.indexOf("?") !== -1) {
                        old_href = old_href + "&" + k + "=" + utm_variables[k];
                    } else {
                        old_href = old_href + "?" + k + "=" + utm_variables[k];
                    }
                }
            }

            $elem.attr("href", old_href);
        })
    }
    
    function do_gform_insertion(evt, form_id, current_page) {
        var $form = $("#gform_" + form_id), i, k,
            old_action = $form.attr("action");
        
        utm_variables = look_for_utm_variables();
        
        //Remove any existing query vars.
        //TODO: should we bother preserving old vars that aren't UTMs?
        old_action = old_action.split("?")[0];
        
        //Add UTM variables as seen by the client.
        for (k in utm_variables) {
            if (utm_variables.hasOwnProperty(k) && utm_variables[k] !== undefined) {
                if (old_action.indexOf("?") !== -1) {
                    old_action = old_action + "&" + k + "=" + utm_variables[k];
                } else {
                    old_action = old_action + "?" + k + "=" + utm_variables[k];
                }
            }
        }
        
        $form.attr("action", old_action);
        
        //TODO: Can we auto-insert UTM variables into hidden fields?
    }
    
    $(document).bind("gform_post_render", do_gform_insertion);
    
    Behaviors.register_content_listener(do_utm_replace);
    
    module.do_utm_replace = do_utm_replace;
    module.look_for_utm_variables = look_for_utm_variables;
    module.getQueryVariable = getQueryVariable;
    
    return module;
}));
