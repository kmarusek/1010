<?php
// Get the JSON
$formatted_json = $module->getFormattedJSON();
?>
(function ($) {
    $(document).ready(function () {
        new BWAnimatedSVG({
            animateOnScroll    : <?php echo $module->getModuleSettingScrollBasedAnimation(); ?>,
            elementContainerID : '<?php echo $module->getLottieContainerUniqueID(); ?>',
            lottieParams       : {
                loop          : <?php echo $module->getModuleSettingLoopAnimation(); ?>,
                animationData : <?php echo json_encode( $formatted_json ); ?>
            }
        });
    });
}(jQuery));