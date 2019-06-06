(function($){
    $(function(){
        new BWVideoPlayer({
            id              : '<?php echo $id; ?>',
            element         : $('.fl-module-bw-video-player.fl-node-<?php echo $id; ?>'),
            videoSource     : '<?php echo $module->getVideoSource();?>',
            videoPlayerType : '<?php echo $module->getPlayerType() ?>'
        });
    });
})(jQuery);