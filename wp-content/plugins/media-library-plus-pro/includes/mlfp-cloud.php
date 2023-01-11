
<div id="wp-media-grid" class="wrap">
  
  <div class="mlfp-container">
    
    <?php echo $this->display_mlfp_header() ?>
    
    <h1 id="mlfp-page-title"><?php _e('Cloud Storage', 'maxgalleria-media-library' ); ?></h1>
    <div class="mlfp-tab-section">
      
 <?php     
//Get the active tab from the $_GET param
  $default_tab = null;
  $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

  ?>
  <!-- Our admin page content should all be inside .wrap -->
  <div class="wrap">
    <nav class="nav-tab-wrapper">
      <a href="?page=mlfp-cloud" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Cloud Setup</a>
      <!--<a href="?page=mlfp-cloud&tab=cloud-setup" class="nav-tab < ?php if($tab==='cloud-setup'):?>nav-tab-active< ?php endif; ?>">Cloud Setup</a>-->
      <a href="?page=mlfp-cloud&tab=cloud-files" class="nav-tab <?php if($tab==='cloud-files'):?>nav-tab-active<?php endif; ?>">Cloud Files</a>
      <a href="?page=mlfp-cloud&tab=cloud-sync" class="nav-tab <?php if($tab==='cloud-sync'):?>nav-tab-active<?php endif; ?>">Cloud Sync</a>
      <a href="?page=mlfp-cloud&tab=cloud-license" class="nav-tab <?php if($tab==='cloud-license'):?>nav-tab-active<?php endif; ?>">License</a>
      <a id="mlfp-help"><img id="mlfp-help-icon" src="<?php echo MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL ?>/images/mlfp-help.png" alt="help icon" width:="28" height="28"></a>
    </nav>
    
    <div id="mlfp-help-panel" style="display:none">
      <div id="mlfp-help-panel-inner" >
        <div>Help Panel</div>
        
        <?php switch($tab) :
          case 'cloud-license':
            echo 'Cloud License';
            break;

          case 'cloud-files':
            echo 'Cloud Files';
            break;

          case 'cloud-sync':
            echo 'Cloud Sync';
            break;

          default:
            echo 'Cloud Setup';
            break;
        endswitch; ?>
                
      </div>
    </div>
    
    <div class="tab-content">
    <?php switch($tab) :
      
      case 'cloud-license':
        echo 'License';
        break;

      case 'cloud-files':
        echo 'Cloud Files';
        break;
      
      case 'cloud-sync':
        echo 'Cloud Sync';
        break;
      
      default:
        echo 'Cloud Setup';
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
