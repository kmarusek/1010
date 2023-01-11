
<div id="wp-media-grid" class="wrap">
  <script>
    // deterime what browser we are using
    var doc = document.documentElement;
    doc.setAttribute('data-useragent', navigator.userAgent);
  </script>
  
  
  <div class="mlfp-container">
    
    <?php echo $this->display_mlfp_header() ?>
    
    <?php $license_valid = $this->display_experation_notice() ?>    
    
    <h1 id="mlfp-page-title"><?php _e('Folders & Files', 'maxgalleria-media-library' ); ?></h1>
    
    <div class="mlfp-tab-section">
      
 <?php     
//Get the active tab from the $_GET param
  $default_tab = null;
  $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

  ?>
  <!-- Our admin page content should all be inside .wrap -->
  <div class="wrap">
    <nav class="nav-tab-wrapper">
      <a href="?page=mlfp-folders" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Library</a>
      <?php if($this->current_user_manage_options && $this->license_valid) { ?>
       <?php if($this->enable_user_role == 'on') { ?>
        <a href="#" class="nav-tab blank-nav <?php if($tab==='folder-access'):?>nav-tab-active<?php endif; ?>">Folder Access</a>
        <a href="?page=mlfp-folders&tab=folder-access" class="nav-tab active-nav <?php if($tab==='folder-access'):?>nav-tab-active<?php endif; ?>" style="display:none">Folder Access</a>
      <?php } ?>
        <a href="#" class="nav-tab blank-nav <?php if($tab==='maintenance'):?>nav-tab-active<?php endif; ?>">Maintenance</a>
        <a href="?page=mlfp-folders&tab=maintenance" class="nav-tab active-nav <?php if($tab==='maintenance'):?>nav-tab-active<?php endif; ?>" style="display:none">Maintenance</a>
        <a href="#" class="nav-tab blank-nav <?php if($tab==='export'):?>nav-tab-active<?php endif; ?>">Import/Export</a>
        <a href="?page=mlfp-folders&tab=export" class="nav-tab active-nav <?php if($tab==='export'):?>nav-tab-active<?php endif; ?>" style="display:none">Import/Export</a>
      <?php } ?>
      <a id="mlfp-help"><img id="mlfp-help-icon" src="<?php echo MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL ?>/images/mlfp-help.png" alt="help icon" width:="28" height="28"></a>
    </nav>

    <div id="mlfp-help-panel-container">
    <div id="mlfp-help-panel" style="display:none">
      <div id="mlfp-help-panel-inner" >
        
        <?php if($this->current_user_manage_options) { ?>

        <?php switch($tab) :
          case 'folder-access':
            $this->folder_access_help();
            break;

          case 'maintenance':
            $this->maintenance_help();
            break;

          case 'export':
            $this->export_help();
            break;

          default:
            $this->library_help();
            $this->bulk_move_help();
            $this->playlist_help();
            $this->embed_help();
            $this->jp_gallery_help();
            break;
        endswitch; ?>
        <?php } else {  
            echo __('Library', 'maxgalleria-media-library');
         } ?>  
                
      </div>
    </div>
    </div>
    
    <div class="tab-content">
    <?php if($this->current_user_manage_options) { ?>
      
    <?php switch($tab) :
      case 'folder-access':
        $this->mlfp_folder_access();
        break;
      
      case 'maintenance':
        $this->mlfp_maintenance();
        break;
      
      case 'export':
        $this->mlfp_export();
        break;
      
      default:
        $this->media_library();
        break;
    endswitch; ?>
    <?php } else {  
        $this->media_library();
     } ?>  
      
    </div>
  </div>
  
  
      
    </div><!--mlfp-tab-section-->
    
  </div><!--mlfp-container-->
    
</div><!--wp-media-grid-->
<script>
jQuery(document).ready(function(){
  
  //jQuery('.blank-nav').hide();
  //jQuery('.active-nav').show();
    
  var help_id = jQuery('#help-id').val();
  if(help_id == 'bulk-move') {
    jQuery('#bulk-move-help').show();
    jQuery('#library-help').hide();
  } else {
    jQuery('#bulk-move-help').hide();
    jQuery('#library-help').show();
  }

  jQuery(document).on("click", "#mlfp-help-icon", function (e) {
    jQuery("#mlfp-help-panel").animate({
        width: "toggle"
    });

  });  

});  
</script>   


