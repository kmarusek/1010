jQuery(document).ready(function(){
	new UABBSpacerGap({
		id: '<?php echo $id; ?>',
		desktop_space: '<?php echo $settings->desktop_space; ?>',
		medium_device: '<?php echo $settings->medium_device; ?>',
		small_device: '<?php echo $settings->small_device; ?>',
	});
});
