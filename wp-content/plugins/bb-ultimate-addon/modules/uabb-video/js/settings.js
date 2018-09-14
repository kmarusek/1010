(function($){
	FLBuilder.registerModuleHelper('uabb-video', {



		init: function(){
			var form 		= $('.fl-builder-settings'),
			subscribe_bar	= form.find('select[name=yt_subscribe_enable]');

			subscribe_bar.on( 'change', this._subscribeBar);
			 $(this._subscribeBar,this);
		},
		_subscribeBar: function(){
			var form 			= $('.fl-builder-settings');
			subscribe_bar_val	= form.find('select[name=yt_subscribe_enable]').val();
			subscribe_channel	= form.find('select[name=select_options]').val();

			if('no'== subscribe_bar_val){
				form.find('#fl-field-yt_channel_id').hide();
				form.find('#fl-field-yt_channel_name').hide();
			}
			else{
				if('channel_id'==subscribe_channel){
					form.find('#fl-field-yt_channel_id').show();
				}
				else if('channel_name'==subscribe_channel){
					form.find('#fl-field-yt_channel_name').show();
				}
			} 
		}
	});

})(jQuery);