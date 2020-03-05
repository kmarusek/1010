(function (root, factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        define("ContentSlider", ["jquery", "Behaviors"], factory);
    } else {
        root.ContentSlider = factory(root.jQuery, root.Behaviors);
    }
}(window, function ($, Behaviors) {
    //TODO: Move this file out of `frontend.prebuilt.js` and into the global script pack
    "use strict";
    var module = {};
    
    function ContentSlider() {
        Behaviors.init(ContentSlider, this, arguments);
        this.element = this.$elem[0];
        this.left_arrow = this.$elem.data("contentslider-leftarrow");
        this.right_arrow = this.$elem.data("contentslider-rightarrow");
        this.dots = this.$elem.data("contentslider-dots");
        this.dots_icon = this.$elem.data("contentslider-dotsicon");
        this.autoplay_timeout = this.$elem.data("contentslider-autoplay");
        
        this.has_loop = this.$elem.data("contentslider-loop") !== undefined;
        this.has_autoplay = this.autoplay_timeout !== undefined;
        this.has_hoverpause = this.$elem.data("contentslider-hoverpause") !== undefined;
        this.has_dots = this.dots !== "none";
        this.has_nav = false;
        if (this.left_arrow) {
            this.has_nav = true;
        }
        
        if (this.right_arrow) {
            this.has_nav = true;
        }
        this.initOwlCarousel();
    };
    
    Behaviors.inherit(ContentSlider, Behaviors.Behavior);
    
    ContentSlider.QUERY = "[data-contentslider]";
    
    ContentSlider.ELEMENTS = {
        heightContainer: ".scrollContainerHeight",
        widthContainer: ".scrollContainerWidth",
        sliderContents: ".horizontalContents",
        section: ".section",
        progressBar: ".progressBarFill",
        progressBall: ".progressBall",
        carousel: ".owl-carousel"
    };
    
    ContentSlider.prototype.dots_class = function () {
        return "owl-dots ContentSlider-dots ContentSlider-dots--style_" + this.dots;
    };
    
    ContentSlider.prototype.dot_class = function () {
        if (this.dots_icon) {
            return "ContentSlider-dot ContentSlider-dot--style_" + this.dots + " " + this.dots_icon;
        } else {
            return "ContentSlider-dot ContentSlider-dot--style_" + this.dots;
        }
    };
    
    ContentSlider.prototype.initOwlCarousel = function() {
        this.carousel = this.$elem.find(ContentSlider.ELEMENTS.carousel).owlCarousel({
            loop: this.has_loop,
            center: true,
            dots: this.has_dots,
            dotsClass: this.dots_class(),
            dotClass: this.dot_class(),
            //margin: 50,
            items: 1, 
            nav: this.has_nav,
            navClass: ["owl-prev " + this.left_arrow, "owl-next " + this.right_arrow],
            //onDragged: self.updatePagination.bind(self),
            autoplay: this.has_autoplay,
            autoplayTimeout: this.autoplay_timeout,
            autoplayHoverPause: this.has_hoverpause
        });
        //self.updateNav(0);
    };

    
    
    ContentSlider.prototype.init = function () {
        this.fixed = false;
        //this.setHeight();
        //this.handleScroll();
        //this.handleResize();
        this.initMobileSlider();
        //this.handleDrag();
        //this.element.querySelector(".currentSlide").innerHTML = "01";
        //this.element.querySelector(".maxSlide").innerHTML = "0".concat(this.sectionNumber);
        //this.setTitlePos();
    };
    
    ContentSlider.prototype.setHeight = function () {
        this.elementHeight = this.element.querySelector(ContentSlider.ELEMENTS.section).offsetHeight;
        this.sectionNumber = this.element.querySelectorAll(ContentSlider.ELEMENTS.section).length;
        var width = this.element.querySelector(ContentSlider.ELEMENTS.section).offsetWidth;
        this.height = (this.sectionNumber - 1) * (width * 3) + this.elementHeight;
        this.element.querySelector(ContentSlider.ELEMENTS.heightContainer).style.height = "".concat(this.height, "px");
    };
    
    ContentSlider.prototype.handleScroll = function () {
        var self = this;
        wContainer = self.element.querySelector(ContentSlider.ELEMENTS.widthContainer), sContents = self.element.querySelector(ContentSlider.ELEMENTS.sliderContents), pBar = self.element.querySelector(ContentSlider.ELEMENTS.progressBar);
        pBall = self.element.querySelector(ContentSlider.ELEMENTS.progressBall);
        window.addEventListener("scroll", function () {
            self.scrollHandler.call(self, wContainer, sContents, pBar, pBall);
        });
    };
    
    ContentSlider.prototype.scrollHandler = function(wContainer, sContents, pBar, pBall) {
        var self = this;
        var s = self.getOffsetTop(self.element) - window.scrollY;
        self.currentScroll = window.scrollY;
        var pFill = s * -1 / (self.height - self.elementHeight) * 100;

        if (s <= 0 && s >= (self.height - self.elementHeight) * -1 && self.fixed === false) {
            document.body.style.overscrollBehaviorX = 'none';
            wContainer.style.position = "fixed";
            self.fixed = true;
        } else if (s > 0) {
            wContainer.style.position = "absolute";
            wContainer.classList.add('top');
            wContainer.classList.remove('bottom');
            sContents.style.transform = "translate3d(0px, 0px, 0px)";
            pBar.style.width = "0%";
            pBall.style.left = "0%";
            self.fixed = false;
        } else if (s <= (self.height - self.elementHeight) * -1) {
            var height = (self.height - self.elementHeight) * -1 / 3;
            wContainer.style.position = "absolute";
            wContainer.classList.add('bottom');
            wContainer.classList.remove('top');
            sContents.style.transform = "translate3d(".concat(height, "px, 0px, 0px)");
            pBar.style.width = "100%";
            pBall.style.left = "100%";
            document.body.style.overscrollBehaviorX = 'auto';
            self.fixed = false;
        }

        if (self.fixed) {
            var ns = s / 3;
            self.ns = ns;
            var wScroll = s * -1;

            var _height = (self.height - self.elementHeight) * -1 / 3;

            if (ns) sContents.style.transform = "translate3d(".concat(ns, "px, 0px, 0px)");
            pBar.style.width = "".concat(pFill, "%");
            pBall.style.left = "".concat(pFill, "%");
            var newIndex = Math.floor(self.sectionNumber * (pFill / 100)) + 1;

            if (newIndex < 10) {
                newIndex = "0".concat(newIndex);
            }

            var maxIndex = self.sectionNumber;

            if (maxIndex < 10) {
                maxIndex = "0".concat(maxIndex);
            }

            self.element.querySelector(".currentSlide").innerHTML = newIndex;
            self.element.querySelector(".maxSlide").innerHTML = maxIndex; // if(pFill >= 90 && !self.circleOpen){
            //         self.toggleProgressCircle('open');
            // }else if(pFill < 90 && self.circleOpen){
            //         self.toggleProgressCircle('close');
            // }
            // if(newIndex > self.sectionNum - 1){
            //         return;
            // }else if(newIndex !== self.currentIndex){
            //         self.currentIndex = newIndex;
            // }
            // self.changeImageOpacity();
        }

        self.scrollPos = s;
    };
    
    ContentSlider.prototype.handleDrag = function() {
        var self = this;
        var wee = self.element.querySelector(ContentSlider.ELEMENTS.sliderContents);
        wee.addEventListener('mousedown', function (e) {
            self.mousedown = true;
            self.x = e.clientX;
        });
        wee.addEventListener('mouseup', function (e) {
            self.mousedown = false;
        });
        wee.addEventListener('mousemove', function (e) {
            if (self.mousedown === true && self.scrollPos < +30 && self.scrollPos > (self.height - self.elementHeight) * -1 - 30) {
                var ws = window.scrollY,
                        change = (self.x - e.clientX) * 3,
                        newPos = ws += change;
                window.scrollTo(0, newPos);
                self.x = e.clientX;
            }
        });
    };
    
    ContentSlider.prototype.getOffsetTop = function(ele) {
        var offsetTop = 0;

        while (ele) {
            offsetTop += ele.offsetTop;
            ele = ele.offsetParent;
        }

        return offsetTop;
    };
    
    ContentSlider.prototype.setTitlePos = function() {
        var width = this.element.querySelector(".fl-row-content").offsetWidth,
                title = this.element.querySelector(".sliderLabel"),
                prog = this.element.querySelector(".progressBarContainer");
        var leftPos = (window.innerWidth - width) / 2;
        title.style.left = "".concat(leftPos, "px");
        prog.style.left = "".concat(leftPos, "px");
    };
    
    ContentSlider.prototype.handleResize = function() {
        var self = this,
                wContainer = self.element.querySelector(ContentSlider.ELEMENTS.widthContainer),
                sContents = self.element.querySelector(ContentSlider.ELEMENTS.sliderContents),
                pBar = self.element.querySelector(ContentSlider.ELEMENTS.progressBar),
                pBall = self.element.querySelector(ContentSlider.ELEMENTS.progressBall);
        window.addEventListener("resize", function () {
            self.setHeight();
            self.setTitlePos();
            self.scrollHandler.call(self, wContainer, sContents, pBar, pBall);
            this.setTimeout(function () {
                self.setMobileOffset();
            }, 500);
        });
    };
    
    ContentSlider.prototype.setMobileOffset = function() {
        var offset = $(".owl-stage").offset().left;
        $(".sliderLabel").css("margin-left", "".concat(offset, "px"));
        $(".progressBarContainerMobile").css("margin-left", "".concat(offset, "px"));
    };
    
    ContentSlider.prototype.updatePagination = function(event) {
        var index = event.item.index;
        this.updateNav(index);
    };
    
    ContentSlider.prototype.updateNav = function(index) {
        var current = this.element.querySelector(".currentSlideMobile");
        var slideMax = this.element.querySelector(".maxSlideMobile");
        var max = this.element.querySelectorAll(".mobile-slider-item").length;
        index++;

        if (index < 10) {
            index = "0".concat(index);
        }

        if (max < 10) {
            max = "0".concat(max);
        }

        current.innerHTML = index;
        slideMax.innerHTML = max;
        var percent = index / max * 100;
        var ball = this.element.querySelector(".progressBallMobile");
        var fill = this.element.querySelector(".progressBarFillMobile");
        ball.style.left = "".concat(percent, "%");
        fill.style.width = "".concat(percent, "%");
    };
    
    Behaviors.register_behavior(ContentSlider);
    
    module.ContentSlider = ContentSlider;
    
    return module;
}));