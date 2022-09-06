(function($){
    $(function(){
      
      const TenTenAnimations = document.querySelectorAll('.TenTenHero-animation');

      TenTenHeroObserver = new IntersectionObserver((entries) =>{
            entries.forEach( entry => {
                if(entry.intersectionRatio > 0 ){
                    entry.target.classList.add('is-visible');
                } else {
                    entry.target.classList.remove('is-visible');
                }
            })
        


        });

        TenTenAnimations.forEach(TenTenAnimation => {
          TenTenHeroObserver.observe(TenTenAnimation)
        });

        
        


//end
});
})(jQuery);