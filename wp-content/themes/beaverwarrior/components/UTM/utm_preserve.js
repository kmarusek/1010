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
    
    function utm_preserve_enabled() {
        return $("body").data("utmpreserve-preserve") !== "false";
    }
    
    function utm_forminject_enabled() {
        return $("body").data("utmpreserve-forminject") !== "false";
    }

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
        if (!utm_preserve_enabled()) {
            console.log("UTM preserve is disabled.");
            return;
        }
        
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
        
        if (!utm_forminject_enabled()) {
            console.log("UTM form injection is disabled.");
            return;
        }
        
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
        
        //We can't auto-insert UTM variables into hidden fields, so instead we
        //replace by hidden values.
        $form.find("input[type='hidden']").each(function (index, ielem) {
            var $ielem = $(ielem), old_value = $ielem.attr("value"), new_key;
            
            // Polyfill for IE11/pre-ES5
            if (!String.prototype.startsWith) {
                String.prototype.startsWith = function(search, pos) {
                    return this.substr(!pos || pos < 0 ? 0 : +pos, search.length) === search;
                };
            }

            if (!String.prototype.endsWith) {
                String.prototype.endsWith = function(search, this_len) {
                    if (this_len === undefined || this_len > this.length) {
                        this_len = this.length;
                    }
                    return this.substring(this_len - search.length, this_len) === search;
                };
            }

            if (old_value.startsWith("replace_param[") && old_value.endsWith("]")) {
                new_key = old_value.split("[")[1].split("]")[0];
                $ielem.val(utm_variables[new_key]);
            }
        })
    }
    
    $(document).bind("gform_post_render", do_gform_insertion);
    
    Behaviors.register_content_listener(do_utm_replace);
    
    module.do_utm_replace = do_utm_replace;
    module.look_for_utm_variables = look_for_utm_variables;
    module.getQueryVariable = getQueryVariable;
    
    return module;
}));
