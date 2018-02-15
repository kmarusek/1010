<?php

function skeletonwarrior_gform_pre_render($form) {
    foreach ($form["fields"] as $k => $field) {
        $form["fields"][$k]->cssClass .= " FormItem FormItem--stacked";
    }
    
    return $form;
}
add_filter('gform_pre_render', 'skeletonwarrior_gform_pre_render');

function skeletonwarrior_gform_submit_button($button, $form) {
    //return "<button class='gform_button button FormItem-action' id='gform_submit_button_{$form['id']}'><span>Submit</span></button>";

    $dom = new DOMDocument();
    $dom->loadHTML( $button );
    $input = $dom->getElementsByTagName( 'input' )->item(0);

    if ($input->getAttribute("type") == "image") {
        $new_button = $dom->createElement( 'button' );
        $new_btnimage = $dom->createElement('img');
        $new_btnimage->setAttribute('src', $input->getAttribute('src'));
        $new_btnimage->setAttribute('alt', $input->getAttribute('alt'));
        $new_button->appendChild($new_btnimage);

        $input->removeAttribute( 'type' );
        $input->removeAttribute( 'src' );
        $input->removeAttribute( 'alt' );

        foreach( $input->attributes as $attribute ) {
            $new_button->setAttribute( $attribute->name, $attribute->value );
        }
        $input->parentNode->replaceChild( $new_button, $input );
    } else {
        $new_button = $dom->createElement( 'button' );
        $new_button->appendChild( $dom->createTextNode( $input->getAttribute( 'value' ) ) );
        $input->removeAttribute( 'value' );
        foreach( $input->attributes as $attribute ) {
            $new_button->setAttribute( $attribute->name, $attribute->value );
        }
        $input->parentNode->replaceChild( $new_button, $input );
    }

    //Ensure FormItem-action is present.
    if ($new_button->hasAttribute("class")) {
        $new_button->setAttribute("class", $new_button->getAttribute("class") . " FormItem-action");
    } else {
        $new_button->setAttribute("class", "FormItem-action");
    }

    return $dom->saveHtml( $new_button );
}
add_filter('gform_submit_button', 'skeletonwarrior_gform_submit_button', 10, 2);
