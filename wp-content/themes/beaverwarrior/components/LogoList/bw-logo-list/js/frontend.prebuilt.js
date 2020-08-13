(($)=>{
    LogoList = function(settings){
        this.element = settings.element;
        this.init();
    };
    LogoList.prototype = {
        elements: {
            logo: ".logo_block"
        },
        init(){
            const self = this;
            this.handleHover();
            this.enableMarquee();
            this.infiniteMarquee();
        },
        handleHover(){
            $(".logo_block").hover(
                  function () {
                    $(this).addClass("logo-focus");
                  },
                  function () {
                    $(this).removeClass("logo-focus");
                  }
            )
        },
        enableMarquee(){
            $('.row.enable').addClass("marquee");
        },
    }
})(jQuery);
