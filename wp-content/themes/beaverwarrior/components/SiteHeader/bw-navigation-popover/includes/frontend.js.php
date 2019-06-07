(function($){
    $(function(){
        new BWNavigationPopover({
            element               : $('.fl-module-bw-navigation-popover.fl-node-<?php echo $id; ?>'),
            popoverHeadersEnabled : <?php echo $module->getBooleanStringValue( $module->popoverSectionTitlesAreEnabled() ); ?>,
            popoverPointerEnabled : <?php echo $module->getBooleanStringValue( $module->popoverPointerIsEnabled() ); ?>,
            popoverCustomContent  : '<?php echo apply_filters( 'navigation_popover_custom_content', '' ); ?>'
        });
    });
})(jQuery);