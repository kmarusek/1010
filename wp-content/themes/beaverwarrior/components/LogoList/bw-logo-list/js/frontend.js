"use strict";

(function ($) {
  LogoList = function LogoList(settings) {
    this.element = settings.element;
    this.init();
  };

  LogoList.prototype = {
    elements: {
      logo: ".logo_block"
    },
    init: function init() {
      var self = this;
      this.handleHover();
      this.enableMarquee();
    },
    handleHover: function handleHover() {
      $(".logo_block").hover(function () {
        $(this).addClass("logo-focus");
      }, function () {
        $(this).removeClass("logo-focus");
      });
    },
    enableMarquee: function enableMarquee() {
      $('.row.enable').addClass("marquee");
    }
  };
})(jQuery);
//# sourceMappingURL=frontend.js.map
