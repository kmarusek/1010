<?php
// Get the attachent file
$attachment_file = wp_get_attachment_url( $settings->video );

?>
(function($){
    $(function(){
        new BWVideoPlayer({
            id      : '<?php echo $id; ?>',
            element : $('.fl-module-bw-video-player.fl-node-<?php echo $id; ?>'),
            videoPlayerType : '<?php echo $module->getPlayerType() ?>'
        });
    });
})(jQuery);