;(function($) {

	$(document).ready(function() {
		if ( $( '#fb-root' ).length === 0 ) {
			$('body').append('<div id="fb-root"></div>');
		}
		
		new PPFacebookEmbed({
			id: '<?php echo $id; ?>',
		});
	});
})(jQuery);
