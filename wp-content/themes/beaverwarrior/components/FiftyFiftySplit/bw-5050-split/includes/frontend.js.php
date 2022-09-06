
(function($){
    $(function(){

        const animations = document.querySelectorAll('.FiftyFiftySplit-animation');

        observer = new IntersectionObserver((entries) =>{
            entries.forEach( entry => {
                if(entry.intersectionRatio > 0 ){
                    entry.target.classList.add('is-visible');
                } else {
                    entry.target.classList.remove('is-visible');
                }
            })


        });

        animations.forEach(animation => {
            observer.observe(animation)
        })
      







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