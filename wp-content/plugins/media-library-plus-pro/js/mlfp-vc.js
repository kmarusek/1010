var mlfp_vc_single = 0;
var mlfp_vc_gallery = 0;
jQuery(document).ready(function(){
  
  console.log('mlfp-vc ready');
    
  jQuery(document).on("click", ".vc-c-icon-mode_edit", function () {						
    //console.log('vc-c-icon-mode_edit click');
    var vc_gallery = jQuery(this).parents('div.wpb_vc_gallery');
    mlfp_vc_gallery = vc_gallery.length;
    var vc_single = jQuery(this).parents('div.wpb_vc_single_image');
    mlfp_vc_single = vc_single.length;
  });  


});