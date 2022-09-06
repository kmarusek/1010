
(function($){
    $(function(){

        const FiftyFiftySplitAnimations = document.querySelectorAll('.FiftyFiftySplit-animation');

        FiftyFiftySplitObserver = new IntersectionObserver((entries) =>{
            entries.forEach( entry => {
                if(entry.intersectionRatio > 0 ){
                    entry.target.classList.add('is-visible');
                } else {
                    entry.target.classList.remove('is-visible');
                }
            })


        });

        FiftyFiftySplitAnimations.forEach(FiftyFiftSplitAnimation => {
            FiftyFiftySplitObserver.observe(FiftyFiftSplitAnimation)
        });
      







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