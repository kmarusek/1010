(function($){

    FLBuilder.registerModuleHelper('bw-navigation-popover', {

        init: function() {
            this.form                      = $('.fl-builder-settings');
            this.formButton                = this.form.find('.fl-builder-button'); 
            this.nodeID                    = this.form.attr('data-node');
            this.BWNavigationPopoverObject = $('.fl-module-bw-navigation-popover.fl-node-' + this.nodeID ).data( 'BWNavigationPopoverObject' );
            this._openPopoverDemo();
            this.formButton
            .on('click', $.proxy( this._closePopover, this ) );
        },

        _openPopoverDemo: function(){

            this.BWNavigationPopoverObject
            ._demoShowFirstPopover();
        },

        _closePopover: function(){
            this.BWNavigationPopoverObject
            ._demoHideFirstPopover();
        }
    });

})(jQuery);
