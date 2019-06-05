<?php
// Make sure Bootstrap is loaded
wp_enqueue_script( 'bootstrap' );
// Get the icons
$social_icons = $module->getShareIcons();
?>
<div class="social-icons-container">
    <div class="social-icons-inner">
        <ul class="social-icons-list">
            <?php
            // Loop through the icon
            for ( $i=0; $i<count($social_icons); $i++ ){
                // Get the current icon 
                $current_icon = $social_icons[$i];
                echo sprintf(
                    '<li><a href="%s" target="_blank" title="Share to %s"><i class="%s" aria-hidden="true"></i></a></li>',
                    // The share URL
                    $current_icon['url'],
                    // The platform title
                    $current_icon['title'],
                    // The icon class
                    $current_icon['icon_class']
                );
            }
            ?>
        </ul>
    </div>
</div>
