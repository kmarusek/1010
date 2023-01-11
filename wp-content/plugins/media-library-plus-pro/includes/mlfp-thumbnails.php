
<div id="wp-media-grid" class="wrap">
  
  <div class="mlfp-container">
    
    <?php echo $this->display_mlfp_header() ?>
    
    <h1 id="mlfp-page-title"><?php _e('Thumbnails', 'maxgalleria-media-library' ); ?></h1>
    <div class="wrap">
            
    <?php     
    //Get the active tab from the $_GET param
     $default_tab = null;
     $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

     ?>
            

      <nav class="nav-tab-wrapper">
        <a href="?page=mlfp-thumbnails" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>"><?php _e('Size Management', 'maxgalleria-media-library' ); ?></a>
        <a href="?page=mlfp-thumbnails&tab=regenerate" class="nav-tab <?php if($tab==='regenerate'):?>nav-tab-active<?php endif; ?>"><?php _e('Regenerate', 'maxgalleria-media-library' ); ?></a>
        <a id="mlfp-help"><img id="mlfp-help-icon" src="<?php echo MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL ?>/images/mlfp-help.png" alt="help icon" width:="28" height="28"></a>
      </nav>
    
      <div id="mlfp-help-panel" style="display:none">
        <div id="mlfp-help-panel-inner" >
          
          <?php switch($tab) :
            case 'regenerate':
              $this->regen_thumbnails_help();
              break;
            default:
              $this->thumbnails_management_help();
              break;
            endswitch; ?>
          
        </div>
      </div>  
      
      <div class="tab-content">                      
        
        <?php switch($tab) :
          case 'regenerate':
            $this->regenerate_interface();
            break;
          default:
            ?> <p><?php _e("If you would like to remove thumbnails from your site, we recommend that your first backup the files in your uploads folder. If Media Library Folders Pro is deactivated, any deactivated thumbnail sizes will become activate again and will be automatically generated when new images are uploaded. Any links to thumbnail image that are deleted are not removed from your posts or pages.","maxgalleria-media-library")?></p> <?php
            $this->refresh_thumbnail_table();
            $this->display_thumbnail_table();
            break;
        endswitch; ?>
                
      </div><!--tab-content-->
    
    </div><!--wrap-->
    
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
