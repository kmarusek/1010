"use strict";!function(a){LogoList=function(a){this.element=a.element,this.init()},LogoList.prototype={elements:{logo:".logo_block"},init:function(){this.handleHover(),this.enableMarquee()},handleHover:function(){a(".logo_block").hover(function(){a(this).addClass("logo-focus")},function(){a(this).removeClass("logo-focus")})},enableMarquee:function(){a(".row.enable").addClass("marquee")}}}(jQuery);