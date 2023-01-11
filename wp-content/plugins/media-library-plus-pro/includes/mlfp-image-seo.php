
<div id="wp-media-grid" class="wrap">
  
  <div class="mlfp-container">
    
    <?php echo $this->display_mlfp_header() ?>
    
    <h1 id="mlfp-page-title"><?php _e('Image SEO', 'maxgalleria-media-library' ); ?></h1>
    <div class="wrap">
      <nav class="nav-tab-wrapper">
        <a id="mlfp-help"><img id="mlfp-help-icon" src="<?php echo MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL ?>/images/mlfp-help.png" alt="help icon" width:="28" height="28"></a>
      </nav>
      
      <div id="mlfp-help-panel" style="display:none">
        <div id="mlfp-help-panel-inner" >
          <?php $this->image_seo_help(); ?>
        </div>
      </div>  
          
    <div class="tab-content">
      
      <?php $this->image_seo() ?>
      
    </div><!--tab-content-->
    
  </div><!--wrap-->
    
</div><!--wp-media-grid-->
<script>
jQuery(document).ready(function(){

  jQuery(document).on("click", "#mlfp-help-icon", function (e) {
    jQuery("#mlfp-help-panel").animate({
        width: "toggle"
    });

  });  

});  
</script>   
