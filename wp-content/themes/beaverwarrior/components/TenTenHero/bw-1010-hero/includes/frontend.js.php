
(function($){
    $(function(){
      
//Lens and Mask animation trigger
        const animations = document.querySelectorAll('.TenTenHero-animation');

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

        
        
// Javascript to create crossfade between images in TENTENHero.       
        var slideIndex = 1;
        showSlides(slideIndex);

        // Next/previous controls
        function plusSlides(n) {
          showSlides(slideIndex += n);
        }

        // Thumbnail image controls
        function currentSlide(n) {
          showSlides(slideIndex = n);
        }

        function showSlides(n) {
          var i;
          var slides = document.getElementsByClassName("TenTenHero-slide");
        
          if (n > slides.length) {slideIndex = 1}
          if (n < 1) {slideIndex = slides.length}
          for (i = 0; i < slides.length; i++) {
              slides[i].classList.remove('is-visible');
          }
      
          slides[slideIndex-1].classList.add('is-visible');
      
        }
        setInterval(plusSlides, 8000, 1); // call plusSlider, with 1 as parameter


//JQUERY to select last word of TENTENHero Title and change its color.
        $(".TenTenHero-title-selector").html(function(){
          var text= $(this).text().trim().split(" ");
          var last = text.pop();
          return text.join(" ") + (text.length > 0 ? " <span class='TenTenHero-lastword'>" + last + "</span>" : last);
        });

//end
});
})(jQuery);