
<div id="wp-media-grid" class="wrap">
  
  <div class="mlfp-container">
    
    <?php echo $this->display_mlfp_header() ?>
    
    <h1 id="mlfp-page-title"><?php _e('Support', 'maxgalleria-media-library' ); ?></h1>
    <div class="mlfp-tab-section">
      
    <?php     
   //Get the active tab from the $_GET param
     $default_tab = null;
     $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

     ?>
     <!-- Our admin page content should all be inside .wrap -->
     <div class="wrap">
       <nav class="nav-tab-wrapper">
         <a href="?page=mlfp-support" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>"><?php _e('Troubleshooting Tips', 'maxgalleria-media-library' ); ?></a>
         <a href="?page=mlfp-support&tab=artilces" class="nav-tab <?php if($tab==='artilces'):?>nav-tab-active<?php endif; ?>"><?php _e('Helpful Articles', 'maxgalleria-media-library' ); ?></a>
         <a href="?page=mlfp-support&tab=system-info" class="nav-tab <?php if($tab==='system-info'):?>nav-tab-active<?php endif; ?>"><?php _e('System Info', 'maxgalleria-media-library' ); ?></a>
       </nav>

       <div class="tab-content">
       <?php switch($tab) :

         case 'artilces':
           $this->support_articles();           
           break;

         case 'system-info':
           $this->support_sys_info();
           break;

         default:
           $this->support_tips();           
           break;

       endswitch; ?>
       </div>
       
     </div>
  
  
      
    </div><!--mlfp-tab-section-->
    
  </div><!--mlfp-container-->
    
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
