;(function($) {

	$(document).ready(function() {
		if ( $( '#fb-root' ).length === 0 ) {
			$('body').append('<div id="fb-root"></div>');
		}

		new PPFacebookComments({
			id: '<?php echo $id; ?>',
		});
	});
})(jQuery);
