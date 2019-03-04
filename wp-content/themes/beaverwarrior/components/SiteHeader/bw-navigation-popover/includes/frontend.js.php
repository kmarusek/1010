<?php

?>
(function($){
    $(function(){
        new BWNavigationPopover({
            element               : $('.fl-module-bw-navigation-popover.fl-node-<?php echo $id; ?>'),
            popoverHeadersEnabled : '<?php echo $module->popoverSectionTilesAreEnabled(); ?>'
        });
    });
})(jQuery);
