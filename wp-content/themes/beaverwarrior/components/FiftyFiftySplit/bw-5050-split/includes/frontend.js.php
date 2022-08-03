
(function($){
    $(function(){

//Lens and Content Animation function
    function isElementInViewport(el) {
   
        if (typeof jQuery === "function" && el instanceof jQuery) {
        el = el[0];
        }
        var rect = el.getBoundingClientRect();
        return (
        (rect.top <= 1000
            && rect.bottom0 >= 1000)
        ||
        (rect.bottom >= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.top <= (window.innerHeight || document.documentElement.clientHeight))
        ||
        (rect.top >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight))
        );
    }

    var scroll = window.requestAnimationFrame
    ||
    function(callback){ window.setTimeout(callback, 1000/60)};
    var animate = document.querySelectorAll('.animation');
    function animationLoop(){
        animate.forEach(function (element){
            if( isElementInViewport(element)){
                element.classList.add('is-visible');
            }else{
                element.classList.remove('is-visible');
            }
        });
        scroll(animationLoop);
    }

    animationLoop();





//Mobile Accordion Hide/Show onCLick (jquery)


$(document).ready(function(){
        $("#FiftyFiftySplit-accordion-<?php echo esc_attr($id);?>").click(function(){

            $("#FiftyFiftySplit-<?php echo esc_attr($id); ?>").toggleClass("toggle");
            $("#FiftyFiftySplit-toggle-icon-<?php echo esc_attr($id);?>").text(function(i, v){
               return v === '+' ? '-' : '+';
            })
        });
    });



//end
});
})(jQuery);