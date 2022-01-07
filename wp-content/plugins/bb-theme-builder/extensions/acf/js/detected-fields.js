(function($) {
	$(document).on( '_initSettingsFormsComplete', function() {
		$('#fl-field-name_custom select').change(function(){
			str = $( this ).val();
			if ( '' !== str ) {
				text = this.options[this.selectedIndex].text;
				type = text.match( /\[(.*)\]$/ );
				$('#fl-field-name input[name=name]').val(str)
				$('select[name=type]').val(type[1]).trigger('change');
			}
		});
	});
})(jQuery);
