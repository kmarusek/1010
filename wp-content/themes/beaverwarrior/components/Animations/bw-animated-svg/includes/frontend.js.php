<?php
// Get the JSON
$formatted_json = $module->getFormattedJSON();
?>
document.addEventListener( "DOMContentLoaded", function(){
    new BWAnimatedSVG({
        animateOnScroll    : true,
        elementContainerID : '<?php echo $module->getLottieContainerUniqueID(); ?>',
        lottieParams       : {
            loop          : <?php echo $module->getModuleSettingLoopAnimation(); ?>,
            animationData : <?php echo json_encode( $formatted_json ); ?>
        }
    });
});