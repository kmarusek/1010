"use strict";jQuery(function(a){BWNavigationPopover=function(a){this.element=a.element,this.popoverHeadersEnabled=a.popoverHeadersEnabled,this.popoverPointerEnabled=a.popoverPointerEnabled,this.init()},BWNavigationPopover.prototype={elements:{menuItemsHasChildren:".menu-item-has-children",menuItemsPrimary:".mega-menu-container > li.menu-item"},classes:{},timing:{},init:function(){this._initPopovers(),this._bindHidePopoversOnHover(),this.element.data("BWNavigationPopoverObject",this)},_initPopovers:function(){var b=this;this.element.find(this.elements.menuItemsHasChildren).each(function(){a(this).uniqueId();var c=a(this).attr("id");a(this).popover({trigger:"hover",html:!0,content:b._getDropDownContent(this),placement:"bottom",container:"#"+c,template:b._getDropDownTemplate(),delay:{show:0,hide:250}})})},_bindHidePopoversOnHover:function(){var b=this;this.element.find(this.elements.menuItemsPrimary).mouseenter(function(){b._hideSiblingPopovers(a(this))})},_demoShowFirstPopover:function(){this.element.find(this.elements.menuItemsHasChildren).eq(0).each(function(){a(this).popover("show")})},_demoHideFirstPopover:function(){this.element.find(this.elements.menuItemsHasChildren).eq(0).each(function(){a(this).popover("hide")})},_hideSiblingPopovers:function(b){this.element.find(this.elements.menuItemsHasChildren).not(b).each(function(){a(this).popover("hide")})},_getDropDownContent:function(b){var c=a(b).data("mega-menu-section-title"),d=a(b).find(".mega-menu-contents").html();return this.popoverHeadersEnabled?'<p class="section-title">'+c+"</p>"+d:d},_getDropDownTemplate:function(){var a=this.popoverPointerEnabled?'<div class="triangle-container"><div class="triangle"></div></div>':"";return'<div class="popover '+(this.popoverPointerEnabled?"has-tooltip":"")+'" role="tooltip">'+a+'<h3 class="popover-title"></h3><div class="popover-content"></div></div>'}}});