<?php

function skeletonwarrior_gform_pre_render($form) {
    foreach ($form["fields"] as $k => $field) {
        $form["fields"][$k]->cssClass .= " FormItem FormItem--stacked";
    }
    
    return $form;
}
add_filter('gform_pre_render', 'skeletonwarrior_gform_pre_render');

function skeletonwarrior_gform_submit_button($button, $form) {
    return "<button class='button FormItem-action' id='gform_submit_button_{$form['id']}'><span>Submit</span></button>";
}
add_filter('gform_submit_button', 'skeletonwarrior_gform_submit_button', 10, 2);