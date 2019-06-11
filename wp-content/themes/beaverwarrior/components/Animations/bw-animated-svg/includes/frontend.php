<?php

// Lottie fuels these animations (duh)
$module->enqueueLottie();
// IF we're using the scroll based option, then add the Intersection Observer polyfill
if ( $module->getModuleSettingScrollBasedAnimation() ){
    wp_enqueue_script( 'intersection-observer-polyfill' );
    // Add our observer element
    echo sprintf(
        '<div id="%s-observer"></div>',
        $module->getLottieContainerUniqueID()
    );
}
// Get the JSON for the below call
$json = $module->getModuleSettingJSON();
// IF the JSON is invalid and we're editing it, toss an error to the user
if ( !$module->jsonIsValid( $json ) && $module->isViewingInEditor() ) {
    echo "<p>Psst...it looks like your JSON is invalid:</p>";
    echo "<pre>$json</pre>";
}

if ( $module->isLink() ){
    // Create the anchor
    echo sprintf(
        '<a href="%s" %s target="%s">',
        // The URL
        $settings->link_url,
        // If we have no follow specified
        $settings->link_url_nofollow === 'yes' ? 'rel="nofollow"' : '',
        // The link target
        $settings->link_url_target
    );
}
?>
<div id="<?php echo $module->getLottieContainerUniqueID();?>"></div>
<?php
if ( $module->isLink() ){
    echo "</a>";
}