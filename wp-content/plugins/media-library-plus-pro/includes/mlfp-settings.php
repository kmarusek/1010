
<div id="wp-media-grid" class="wrap">
  
  <div class="mlfp-container">
    
    <?php echo $this->display_mlfp_header() ?>
    
    <h1 id="mlfp-page-title"><?php _e('Settings', 'maxgalleria-media-library' ); ?></h1>
    <div class="mlfp-tab-section">
      
 <?php     
//Get the active tab from the $_GET param
  $default_tab = null;
  $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

  ?>
  <!-- Our admin page content should all be inside .wrap -->
  <div class="wrap">
    <nav class="nav-tab-wrapper">
      <a href="?page=mlfp-settings8" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>"><?php _e('Options', 'maxgalleria-media-library' ); ?></a>
      <a href="?page=mlfp-settings8&tab=license" class="nav-tab <?php if($tab==='license'):?>nav-tab-active<?php endif; ?>"><?php _e('License', 'maxgalleria-media-library' ); ?></a>
      <a id="mlfp-help"><img id="mlfp-help-icon" src="<?php echo MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL ?>/images/mlfp-help.png" alt="help icon" width:="28" height="28"></a>
    </nav>
    
    <div id="mlfp-help-panel" style="display:none">
      <div id="mlfp-help-panel-inner" >
        
        <?php switch($tab) :
          case 'license':
            $this->license_help();
            break;
          default:
            $this->options_help();
            break;
          endswitch; ?>
                
      </div>
    </div>
    
    <div class="tab-content">
    <?php switch($tab) :
      case 'license':
        $this->license();
        break;
      default:
        $this->mlpp_settings();
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

