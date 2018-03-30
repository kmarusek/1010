;(function($) {

	$(document).ready(function() {
		if ( $( '#fb-root' ).length === 0 ) {
			$('body').append('<div id="fb-root"></div>');
		}

		new PPFacebookPage({
			id: '<?php echo $id; ?>',
		});
	});
})(jQuery);
