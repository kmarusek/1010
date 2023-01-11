<?php 
  global $wpdb;
  global $pagenow;
  global $post;
  global $current_user;
  $ajax_nonce = wp_create_nonce( "media-send-to-editor" );				

    $post_id = 0;

  // if no folders table, try to create it
  if(is_multisite()) {
    $table_name = $wpdb->prefix . MAXGALLERIA_MEDIA_LIBRARY_FOLDER_TABLE;
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {		
      $this->activate();
    }	
  }

  $sort_order = get_option( MAXGALLERIA_MEDIA_LIBRARY_CAT_SORT_ORDER );    
  $sort_type = trim(get_option( MAXGALLERIA_MLF_SORT_TYPE ));    
  $cat_sort_order = get_option( MAXGALLERIA_MEDIA_LIBRARY_SORT_ORDER );    
  $move_or_copy = get_option( MAXGALLERIA_MEDIA_LIBRARY_MOVE_OR_COPY );    
  $grid_or_list = get_user_meta(get_current_user_id(), MAXGALLERIA_MEDIA_LIBRARY_GRID_OR_LIST, true);    
  //$grid_or_list = ($grid_or_list === 'on') ? true : false;
  //error_log("grid_or_list $grid_or_list");
  $display_info = get_user_meta( $current_user->ID, MAXGALLERIA_MLP_DISPLAY_INFO, true );
  $disable_ft = get_user_meta( $current_user->ID, MAXGALLERIA_MLP_DISABLE_FT, true );
  $image_seo = get_option(MAXGALLERIA_MEDIA_LIBRARY_IMAGE_SEO, 'off');
  $seo_file_title = get_option(MAXGALLERIA_MEDIA_LIBRARY_TITLE_DEFAULT);
  $seo_alt_text = get_option(MAXGALLERIA_MEDIA_LIBRARY_ATL_DEFAULT);
  $search_type = get_option(MAXGALLERIA_MEDIA_LIBRARY_SEARCH_MODE, 'filter'); 

  // check for media folder id in query string
  if ((isset($_GET['media-folder'])) && (strlen(trim($_GET['media-folder'])) > 0)) {
    $current_folder_id = trim(stripslashes(strip_tags($_GET['media-folder'])));
    if(!is_numeric($current_folder_id)) {
      $current_folder = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, "uploads");      
      $current_folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );        
      $this->uploads_folder_name = $current_folder;
      $this->uploads_folder_name_length = strlen($current_folder);
      $this->uploads_folder_ID = $current_folder_id;				
    }
    else {
      $current_folder = $this->get_folder_name($current_folder_id);
    }	
  } else { // get the uploads folder    
    if(get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, "none") !== 'none') { 
      $current_folder = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, "uploads");      
      $current_folder_id = get_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID );
      $this->uploads_folder_name = $current_folder;
      $this->uploads_folder_name_length = strlen($current_folder);
      $this->uploads_folder_ID = $current_folder_id;				
    } else {
      $current_folder_id = $this->fetch_uploads_folder_id();
      update_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_ID, $current_folder_id);
      $current_folder = $this->lookup_uploads_folder_name($current_folder_id);
      update_option(MAXGALLERIA_MEDIA_LIBRARY_UPLOAD_FOLDER_NAME, $current_folder);
      $this->uploads_folder_name = $current_folder;
      $this->uploads_folder_name_length = strlen($current_folder);
      $this->uploads_folder_ID = $current_folder_id;				        
    }
  }  
  
  //$license_valid = $this->display_experation_notice();
  ?>

<noscript>
  <p><?php _e('Media Library Folders has detected that Javascript has been turned off in this browser. It is necessary for Javascript to be running in order for Media Library Folders to function.','maxgalleria-media-library'); ?></p>
</noscript>

<?php
  $phpversion = phpversion();		
  if($phpversion < '7.4')		
    echo "<br><div>" . __('Current PHP version, ','maxgalleria-media-library') . $phpversion . __(', is outdated. Please upgrade to version 7.4.','maxgalleria-media-library') . "</div>";

  //echo "<p>current_folder: $current_folder_id</p>";
  if(empty($current_folder_id) || $current_folder_id == -1) {
    echo "<br><div>" . __('Folder data not found. Please run Media Library Folders Pro database reset','maxgalleria-media-library') . "</div>";
  }

  $folder_location = $this->get_folder_path($current_folder_id);
  
  $folders_path = "";
  $parents = $this->get_parents($current_folder_id);

  $folder_count = count($parents);
  $folder_counter = 0;        
  $content_folder = apply_filters( 'mlfp_content_folder', 'wp-content');
  $current_folder_string = site_url() . "/$content_folder";
  foreach( $parents as $key => $obj) { 
    $folder_counter++;
    if($folder_counter === $folder_count)
      $folders_path .= $obj['name'];      
    else
      $folders_path .= '<a folder="' . $obj['id'] . '" class="media-link">' . $obj['name'] . '</a>/';      
    $current_folder_string .= '/' . $obj['name'];
  }
?>

<div>
  <?php if(class_exists('MGMediaLibraryFoldersProS3') && 
          ($this->s3_addon->license_status == S3_VALID || $this->s3_addon->license_status == S3_FILE_COUNT_WARNING || 
           $this->s3_addon->license_status == S3_NO_LICENSE || $this->s3_addon->license_status == S3_LICENSE_RQUIRED || 
           $this->s3_addon->license_status == S3_FILE_COUNT_EXCEDED)) { ?>
    <?php $file_count = $this->s3_addon->get_total_media_file_counts(); ?>
    <p>Image Limit: <span id="s3-image-limit"><?php echo number_format($this->s3_addon->license_limit) ?></span> &nbsp;&nbsp;Images Used: <span id="s3-images-used"><?php echo number_format($file_count['uploaded_to_s3']) ?></span><br>
      <a href="https://maxgalleria.com/my-account/" target="_blank">Increase your limit</a>
    </p>        
    <?php } ?>
</div> 


<div id="breadcrumbs-wrapper">
  <h3 id='mgmlp-breadcrumbs'> <?php echo __('Location:','maxgalleria-media-library') . " $folders_path" ?> </h3>
</div>
<input type='hidden' id='display_type' value='1'>
<input type="hidden" id="current-folder-id" value="<?php echo esc_attr($current_folder_id) ?>" />
<input type="hidden" id="previous-folder-id" value="<?php echo esc_attr($current_folder_id) ?>" />
<input type="hidden" id="move-or-copy-status" value="<?php echo esc_attr($move_or_copy) ?>" />
<input type="hidden" id="sort-type" value="<?php echo esc_attr($sort_type) ?>" />
<input type='hidden' id='display_type' value='1'>
<?php if($this->license_valid) { ?>
<input type="hidden" id="grid-list-switch-view" value="<?php echo $grid_or_list ?>">
<?php } else {?>
<input type="hidden" id="grid-list-switch-view" value="on">
<?php }?>

<div id="mgmlp-outer-container">
  
  <div id="folder-tree-container">
    
    <div class="mg-folders-tool-bar bottom-border">
      <ul id="mlf-folder-buttons">
        <?php if(current_user_can('administrator') || (!current_user_can('administrator') && $this->disable_non_admins == 'off')) { ?>
        <li>
          <a id="mlf-add-folder" title="<?php _e('Add Folder','maxgalleria-media-library') ?>">
            <i class="fa-solid fa-folder-plus fa-2x"></i>
          </a>
        </li>  
        <?php } ?>
        <li>  
          <a id="add-new_attachment" title="<?php esc_html_e('Upload files','maxgalleria-media-library') ?>" href="javascript:slideonlyone('add-new-area');">
            <i class="fa-solid fa-upload fa-2x"></i>
          </a>
        </li>  
        <li>  
          <a id="mlf-refresh-folders" title="<?php _e('Refresh Folders','maxgalleria-media-library') ?>">
            <i class="fa-solid fa-arrows-rotate fa-2x"></i>
          </a>
        </li> 
      </ul>
    </div>
    
    <div id="alwrapnav">
      <div id="ajaxloadernav" style="display: none;"></div>
    </div>   
    
    <div id="ft-panel">
			<ul id="folder-tree">
      </ul>  
    </div>  
   
  </div><!--folder-tree-container-->
  
  <div id="mgmlp-library-container">
    <a id="mlfp-top"></a>  
    <div class="mg-folders-tool-bar full-border">
    <?php if($this->license_valid) { ?>
      <div id="primary-tb-left">
        <ul id="mlf-view-buttons">
          <li>            
            <a id="mlf-help-info" href="javascript:slideonlyone('icon-help-area');" title="<?php _e('Icon Descriptions','maxgalleria-media-library') ?>">
              <i class="fa-solid fa-info fa-2x"></i>
            </a>
          </li>
          <li>  
            <?php $view_class = ($grid_or_list == 'on') ? 'mlf-active':''; ?>
            <a id="mlf-grid" title="<?php _e('Grid View','maxgalleria-media-library') ?>">
              <i class="fa-solid fa-border-all fa-2x <?php echo esc_attr($view_class) ?>"></i>
            </a>
          </li>          
          <li>  
            <?php $view_class = ($grid_or_list == 'off') ? 'mlf-active':''; ?>
            <a id="mlf-list" title="<?php _e('List View','maxgalleria-media-library') ?>">
              <i class="fa-solid fa-table-list fa-2x <?php echo esc_attr($view_class) ?>"></i>
            </a>
          </li>          
        </ul>  
      <?php } ?>

        <ul id="mlf-mode-buttons">
          <li>  
            <?php $move_class = ($move_or_copy == 'on') ? 'mlf-active':''; ?>
            <a id="mlf-move" title="<?php _e('Move Files','maxgalleria-media-library') ?>">
              <i class="fa-solid fa-file-import fa-2x <?php echo esc_attr($move_class) ?>"></i>
            </a>
          </li>          
          <li>  
            <?php $copy_class = ($move_or_copy == 'off') ? 'mlf-active':''; ?>
            <a id="mlf-copy" title="<?php _e('Copy Files','maxgalleria-media-library') ?>">
              <i class="fa-solid fa-clone fa-2x <?php echo esc_attr($copy_class) ?>"></i>
            </a>
          </li>          
        </ul>  

        <ul id="mlf-sort-buttons">
          <li>  
            <?php $date_class = ($sort_order == '0') ? 'mlf-active':'';   ?>
            <a id="mlf-sort-date" title="<?php _e('Sort by date','maxgalleria-media-library') ?>">
              <i class="fa-solid fa-calendar-days fa-2x <?php echo esc_attr($date_class) ?>"></i>
            </a>
          </li>          
          <li>  
            <?php $title_class = ($sort_order == '1') ? 'mlf-active':'';   ?>
            <a id="mlf-sort-title" title="<?php _e('Sort by title','maxgalleria-media-library') ?>">
              <i class="fa-solid fa-file-image fa-2x <?php echo esc_attr($title_class) ?>"></i>
            </a>
          </li>          
          <li>  
            <a id="mlf-sort-reverse" title="<?php _e('Reverse Order','maxgalleria-media-library') ?>">
              <i class="fa-solid fa-arrows-up-down fa-2x"></i>
            </a>
          </li>                        
        </ul>  

        <ul id="mlf-sync-button">
          <li>  
            <a id="mlf-rename-mf" title="<?php esc_html_e('Rename a File','maxgalleria-media-library') ?>">
              <i class="fa-solid fa-pen fa-2x"></i>               
            </a>
          </li>
          <?php if(!defined("HIDE_SYNC_BUTTON")) { ?>
            <?php if(current_user_can('administrator') || (!current_user_can('administrator') && $this->disable_non_admins == 'off')) { ?>        
          <li>  
            <a id="sync-media" title="<?php esc_html_e('Sync folder contents','maxgalleria-media-library') ?>">
              <i class="fas fa-bolt fa-2x mlf-active"></i>
              <!--<i class="fa-solid fa-file-arrow-up fa-2x mlf-active"></i>-->
            </a>
          </li>
            <?php } ?>
          <?php } ?>
          <?php if(class_exists('MaxGalleria') || class_exists('MaxGalleriaPro')) { ?>
            <li>  
              <a id="add-mg-gallery" title="<?php esc_html_e('Add images to MaxGalleria gallery','maxgalleria-media-library') ?>">
                <i class="fa-solid fa-images fa-2x mlf-active"></i>
              </a>
            </li> 
          <?php } ?>
          <li>  
            <a id="mgmlp-regen-thumbnails" title="<?php esc_html_e('Regenerate thumbnail images','maxgalleria-media-library') ?>">
              <i class="far fa-object-group fa-2x mlf-active"></i>
            </a>
          </li> 
          <?php if(current_user_can('administrator') || (!current_user_can('administrator') && $this->disable_non_admins == 'off')) { ?>
          <li>  
            <a id="delete-media" title="<?php esc_html_e('Delete files','maxgalleria-media-library') ?>">
              <i class="fas fa-trash fa-2x mlf-active"></i>
            </a>
          </li>
          <?php } ?>  

          <?php if($this->license_valid) { ?>
            <?php if(class_exists('C_NextGEN_Bootstrap') && !class_exists('Amazon_S3_And_CloudFront')) { ?>        
          <li>  
            <a id="new-ng-gallery" title="<?php esc_html_e('Create a NextGen gallery','maxgalleria-media-library') ?>">
              <i class="fa-regular fa-image fa-2x mlf-active"></i>
            </a>
          </li>                     
            <?php } ?>        
          <li>  
            <a id="mlfp-file-replace-area" title="<?php esc_html_e('Replace a file','maxgalleria-media-library') ?>" >
              <i class="fa-solid fa-file-arrow-up fa-2x mlf-active"></i>
            </a>
          </li>             
          <li>  
            <a id="mgmlp-select-category" href="javascript:slideonlyone('category-area');" title="<?php esc_html_e('Categories','maxgalleria-media-library') ?>" href="">
              <i class="fa-solid fa-boxes-stacked fa-2x mlf-active"></i>
            </a>
          </li>                     
          <?php } ?>        
        </ul>  
      </div>
      
      <div id="primary-tb-right">
        <ul id="mlf-search-items">
          <?php if($this->license_valid) { ?>
          <li>
            <select id="mlf-search-select" class="gray-blue-link">
              <?php $selected_filter = ($search_type == 'filter') ? 'selected' : ''; ?>
              <?php $selected_search = ($search_type == 'search') ? 'selected' : ''; ?>
              <option value="filter" <?php echo $selected_filter ?>><?php _e('Filter Current Folder','maxgalleria-media-library') ?></option>
              <option value="search" <?php echo $selected_search ?>><?php _e('Search All Media','maxgalleria-media-library') ?></option>
            </select>
          </li>        
          <li>
            <input type="search" placeholder="<?php _e('Filter or Search','maxgalleria-media-library') ?>" id="mgmlp-media-search-input" class="search gray-blue-link">
          </li>
          <?php } else { ?>
          <li>
            <input type="search" placeholder="<?php _e('Search','maxgalleria-media-library') ?>" id="mgmlp-media-search-input" class="search gray-blue-link">
          </li>
          <?php } ?>
            <span id="mlf-search-clear-wraper">
              <a id="mlf-search-clear"><i class="far fa-times-circle"></i></a>
            </span>
          <li>
            <a id="mlfp-media-search" class="gray-blue-link"><?php _e('Find','maxgalleria-media-library') ?></a>
          </li>
        </ul>
      </div>  
      
    </div>
    <div style="clear:both"></div>
    
    <div id="add-new-area" class="input-area" style="display: none">
      <div id="dragandrophandler">
        <div><?php esc_html_e('Drag & Drop Files Here','maxgalleria-media-library') ?></div>
          <div id="upload-text"><?php esc_html_e('or select a file or image to upload:','maxgalleria-media-library') ?></div>
          <input type="file" name="fileToUpload" id="fileToUpload">
          <input type="hidden" name="folder_id" id="folder_id" value="<?php echo esc_attr($current_folder_id) ?>">
          <input type="button" value="<?php esc_html_e('Upload Image','maxgalleria-media-library') ?>" id="mgmlp_ajax_upload" name="submit_image">
      </div>
    <?php if($image_seo === 'on') { ?>
      <div id="seo-container">
        <label class="mlp-seo-label" for="mlp_title_text"><?php esc_html_e('Image Title Text:','maxgalleria-media-library') ?>&nbsp;</label><input class="seo-fields" type="text" name="mlp_title_text" id="mlp_title_text" value="<?php echo esc_attr($seo_file_title) ?>">
        <label class="mlp-seo-label" for="mlp_alt_text"><?php esc_html_e('Image ALT Text:','maxgalleria-media-library') ?>&nbsp;</label><input class="seo-fields" type="text" name="mlp_alt_text" id="mlp_alt_text" value="<?php echo esc_attr($seo_alt_text) ?>">
      </div>
    <?php } ?>
    </div>
    <div class="mlf-clearfix"></div>
    
    <div id="bulk-move-area" class="input-area">
      <div id="bulk-move-title"><strong><?php _e('Bulk Move','maxgalleria-media-library') ?></strong>
        <a id="close-bulk-move-popup" title="<?php esc_html_e('Close without moving','maxgalleria-media-library') ?>">x</a>
      </div>  
            
      <div id="mlfp-bulk-box">
          <div>
            <input type="text" id="bulkmove-destination-folder" value="" readonly="">
            <!--<input type="text" id="bulkmove-destination-folder" value="< ?php esc_html_e('Select a destination folder in the folder tree','maxgalleria-media-library') ?>" readonly="">-->
            <input type="hidden" id="bulkmove-destination-folder-id" value="" >
            <input type="hidden" id="bulkmove-destination-folder-path" value="" >
          </div>
          <div id="mlfp-reset-row">
            <a id="mlfp-reselect-folder" class="gray-blue-link" ><?php _e('Reselect Destination Folder','maxgalleria-media-library') ?></a>
            <button id="mlfp-bulk-move-files" class="gray-blue-link disabled-button" disabled=""><?php _e('Move Selected Files','maxgalleria-media-library') ?></button>
            <a id="mlfp-stop-file-move" class="gray-blue-link" style="display:none" ><?php _e('Stop Moving Files','maxgalleria-media-library') ?></a>
          </div>
      </div>
    </div>
    <div class="clearfix"></div>    
        
    <div id="playlist-area" class="input-area">
      <div id="embed-box">
        <a id="close-mlf-playlist" title="<?php esc_html_e('Close playlist generator','maxgalleria-media-library') ?>">x</a>        
        <div id="playlist-title"><strong><?php _e('Playlist Shortcode Generator','maxgalleria-media-library') ?></strong></div>        

        <p id="ogg-options">						
          <label id="playlist-type"><?php _e('Type: ','maxgalleria-media-library') ?></label>
          <input type="radio" id="audio-playlist" name="list-type" value="audio"  checked="checked"> <label>Audio</label>
          <input type="radio" id="video-playlist" name="list-type" value="video"> <label>Video</label>
        </p>

        <p>						
          <label id="embed-file-label"><?php _e('File IDs: ','maxgalleria-media-library') ?></label>
          <input type="text" id="pl_attachment_ids" value="" />
        </p>
        <p id="playlist-mesage"></p>
        <p>
          <textarea id="playlist-shortcode-container"></textarea>
        </p>
        <p class="center-buttons">
          <a id="generate-pl-shortcode" class="gray-blue-link"><?php _e('Generate Shortcode','maxgalleria-media-library') ?></a>
          <button type="button" id="copy-pl-shortcode" class="gray-blue-link disabled-button" disabled>Copy to Clipboard</button>
        </p>
        <p id="pl-copy-message"></p>


      </div>  
    </div>  
    <div class="clearfix"></div>
    
    <div id="wp-gallery-area" class="input-area">

      <a id="close-mlf-jp-gallery" title="<?php esc_html_e('Close Jetpack shortcode generator','maxgalleria-media-library') ?>">x</a>        
      <div id="gallery-options-title"><strong><?php _e('Gallery Shortcode Options','maxgalleria-media-library') ?></strong></div>

        <div id="insert_wp_gallery">

          <div class="no-wrap-input">
            <div class="mlp_go_label"><?php _e('Type','maxgalleria-media-library') ?></div>
            <div class="mlp_go_selection">
              <select id="mgmlp-gal-type" class="mlp_gallery_options_sel">
                <option value="none" selected><?php _e('No Selection','maxgalleria-media-library') ?></option>
                <option value="thumbnail"><?php _e('Thumbnail','maxgalleria-media-library') ?></option>
                <!--<option value="slideshow">Slideshow</option>-->
                <?php if(class_exists('Jetpack_Gallery_Settings')) { ?>
                  <option value="rectangular"><?php _e('Rectangular','maxgalleria-media-library') ?></option>
                  <option value="square"><?php _e('Square','maxgalleria-media-library') ?></option>
                  <option value="circle"><?php _e('Circle','maxgalleria-media-library') ?></option>		
                <?php } ?> 
              </select>
            </div>
          </div>

          <div class="no-wrap-input">
            <div class="mlp_go_label"><?php _e('Columns','maxgalleria-media-library') ?></div>
            <div class="mlp_go_selection">
              <select id="mgmlp-gal-columns" class="mlp_gallery_options_sel">
                <option value="none" selected><?php _e('No Selection','maxgalleria-media-library') ?></option>
                <option value="1">1</option>		
                <option value="2">2</option>
                <option value="3">3</option>		
                <option value="4">4</option>		
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
              </select>
            </div>
          </div>

          <div class="no-wrap-input">
            <div class="mlp_go_label"><?php _e('Order By','maxgalleria-media-library') ?></div>
            <div class="mlp_go_selection">
              <select id="mgmlp-gal-order-type" class="mlp_gallery_options_sel">
                <option value="none" selected><?php _e('No Selection','maxgalleria-media-library') ?></option>		
                <option value="ID"><?php _e('ID','maxgalleria-media-library') ?></option>		
                <option value="menu_order"><?php _e('Menu Order','maxgalleria-media-library') ?></option>		
                <option value="rand"><?php _e('Random','maxgalleria-media-library') ?></option>		
                <option value="title"><?php _e('Title','maxgalleria-media-library') ?></option>		
              </select>		
            </div>							
          </div>

          <div class="no-wrap-input">
            <div class="mlp_go_label"><?php _e('Order','maxgalleria-media-library') ?></div>
            <div class="mlp_go_selection">
              <select id="mgmlp-gal-order" class="mlp_gallery_options_sel">
                <option value="ASC"><?php _e('Ascending','maxgalleria-media-library') ?></option>		
                <option value="DESC"><?php _e('Descending','maxgalleria-media-library') ?></option>		
              </select>		
            </div>							
          </div>

          <div class="no-wrap-input">
            <div class="mlp_go_label"><?php _e('Size','maxgalleria-media-library') ?></div>
            <div class="mlp_go_selection">
              <select id="mgmlp-gal-size" class="mlp_gallery_options_sel">
                <option value="none" selected><?php _e('No Selection','maxgalleria-media-library') ?></option>	
                <option value="thumbnail"><?php _e('Thumbnail','maxgalleria-media-library') ?></option>		
                <option value="medium"><?php _e('Medium','maxgalleria-media-library') ?></option>		
                <option value="large"><?php _e('Large','maxgalleria-media-library') ?></option>		
                <option value="full"><?php _e('Full','maxgalleria-media-library') ?></option>		
              </select>		
            </div>													
          </div>

          <div class="no-wrap-input">
            <div class="mlp_go_label"><?php _e('Post ID','maxgalleria-media-library') ?></div>
            <input class="mlp_gallery_options_input" type="text" value="" name="gallery_post_id" id="gallery_post_id">
          </div>


        <div style="clear:both"></div>
          <div id="mlfp-galery-toolbar">
            <a id="insert_mlp_pe_images" help="<?php _e('Adds selected images to the gallery box','maxgalleria-media-library') ?>" class="gray-blue-link-small no-margin-left" ><?php _e('Add Selected Images','maxgalleria-media-library') ?></a>						
            <a id="remove_mlp_pe_images" help="<?php _e('Remove selected images from the gallery box','maxgalleria-media-library') ?>" class="gray-blue-link-small" ><?php _e('Remove Selected Images','maxgalleria-media-library') ?></a>						
            <a id="clear_mlp_pe_images" help="<?php _e('Remove all images from the gallery box','maxgalleria-media-library') ?>" class="gray-blue-link-small" ><?php _e('Remove All Images','maxgalleria-media-library') ?></a>						
            <a id="generate-jp-shortcode" class="gray-blue-link-small"><?php _e('Generate Shortcode','maxgalleria-media-library') ?></a>
            <button type="button" id="copy-jp-shortcode" class="gray-blue-link disabled-button" disabled=""><?php _e('Copy to Clipboard','maxgalleria-media-library') ?></button>
      <!--	 <a id="select_all_mlp_wp_gallery" help="< ?php _e('Remove all images from the gallery box','maxgalleria-media-library') ?>" class="gray-blue-link" >' .  __('Clear','maxgalleria-media-library') ?></a>
          <a id="insert_mlf_wp_gallery" help="< ?php _e('Generates and inserts the gallery shortcode into the post.','maxgalleria-media-library') ?>" class="gray-blue-link" >' .  __('Insert Gallery Shortcode','maxgalleria-media-library') ?></a>-->
        </div>

        <p id="jp-copy-message"></p>

        <div id="wpg_selections">
          <p>&nbsp;</p>
          <ul id="mgmlp-gallery-list">

          </ul>
        </div>

      <p id="mlfp-jp-textarea-row">
        <textarea id="jetpack-shortcode-container"></textarea>
      </p>  

    </div>

    </div>
    <div class="clearfix"></div>
    
    <div id="file-replace-area" class="input-area">
      <div id="embed-box">
        <a id="close-mlf-replace" title="<?php esc_html_e('Close replace box','maxgalleria-media-library') ?>">x</a>        
        <div id="replace-file-title"><strong><?php _e('Replace an Existing File','maxgalleria-media-library') ?></strong></div>

        <a id="display-ir-instructions"><?php _e("Click here to view Replace File Instructions", "maxgalleria-media-library") ?></a>
        <a id="display-ir-instructions-close" style="display:none"><?php _e("Click here to close the insturctions", "maxgalleria-media-library") ?></a>
        <div id="ir-instructions" style="display:none">

          <p><?php _e('To replace an image:','maxgalleria-media-library') ?></p>            

          <p><?php _e('1. Select a file to replace by checking it\'s checkbox.','maxgalleria-media-library') ?></p>            
          <p><?php _e('2. Click the Replace File button.','maxgalleria-media-library') ?></p>            
          <p><?php _e('3. Select the type of replacement to preform: only replace the file (without changing the file name or embedded links) or replace and update with new file (includes changing the file name in the media library and in any links embedded in posts and pages. ) <span class="mlp-warning">Note updated embedded links may not point to actual file names due to variations in thumbnail file sizes. After replacing a file it will be necessary to verify that the links are working.<span>','maxgalleria-media-library') ?></p>            
          <p><?php _e('4. Select whether to keep the original date (that the file was add to the media library or update using the current date or enter a custom date).','maxgalleria-media-library') ?></p>            
          <p><?php _e('5. Upload a replacement file. Either drag and drop a single file or click the Browse button to find and select a file and click the Upload Replacement button. Once a file is uploaded, the old file will be replaced and Replace File area will automatically close.','maxgalleria-media-library') ?></p>     

          <hr>

        </div>  

        <div>

        <input type="hidden" id="replace-file-id" value="" >
        <input type="hidden" id="replace-file-url" value="" >
        <input type="hidden" id="replace-mine-type" value="" >            
        <input type="hidden" id="replace-seo-file-title" value="'.$seo_file_title.'" >            
        <input type="hidden" id="replace-seo-alt-text" value="'.$seo_alt_text.'" >                                   
        <input type="hidden" id="replace-ext" value="" >                                   

          <div id="replace-dragandrop-handler">
            <div><?php _e('Drag & Drop a Replacement File Here','maxgalleria-media-library') ?></div>
            <div id="replace-upload-text"><?php _e('or select a file or image to upload:','maxgalleria-media-library') ?></div>
            <input type="file" name="replacment_to_upload" id="replacment_to_upload">
            <input type="button" value="<?php _e('Upload Replacement','maxgalleria-media-library') ?>" id="replace-file-upload" class="disabled-button" name="replace-file-upload" disabled>
            <img class="sf-spiner" id="replace-upload-spinner" alt="loading spinner" src=" <?php echo MAXGALLERIA_MEDIA_LIBRARY_PRO_PLUGIN_URL ?>/images/file-loading.gif" width="16" height="16">
        <p>
          <span id="mlfp-rpl-selected-file"></span>
        <p/>

            <p>
            <label><?php _e('Options:','maxgalleria-media-library') ?> </label>
            <label for="replace-only"><?php _e('Only replace the file','maxgalleria-media-library') ?></label> <input type="radio" id="replace-only" name="replace-type" value="replace-only"  checked="checked">
            <label for="replace-update"><?php _e('Replace and update with new file','maxgalleria-media-library') ?></label> <input type="radio" id="replace-update" name="replace-type" value="replace-update" >
            </p>
            <p><em><?php _e('A replacement file must be the same file type as the original file.','maxgalleria-media-library') ?></em></p>

            <p>
              <label for="mlfp-keep-date"><?php _e('Keep the current file date','maxgalleria-media-library') ?></label> <input type="radio" id="mlfp-keep-date" name="date-options" value="mlfp-keep-date"  checked="checked">
              <label for="mlfp-update-date"><?php _e('Update the date','maxgalleria-media-library') ?></label> <input type="radio" id="mlfp-update-date" name="date-options" value="mlfp-update-date" >
              <label for="mlfp-custom-date"><?php _e('Use custom date','maxgalleria-media-library') ?></label> <input type="radio" id="mlfp-use-custom-date" name="date-options" value="mlfp-custom-date" >
              <label><?php _e('Custom Date:','maxgalleria-media-library') ?><label> <input type="date" id="mlfp-custom-date" value="" disabled="disabled" >
            </p>
            <p><em><?php _e('After replacing an image, it may be necessary to clear your broswer cache to view the new image.','maxgalleria-media-library') ?></em></p>
          </div>
        </div>


      </div>  
    </div>  
    <div class="clearfix"></div>

    <div id="embed-area" class="input-area">
      <div id="embed-box">
        <a id="close-mlfp-embed" title="<?php esc_html_e('Close embed box','maxgalleria-media-library') ?>">x</a>        
        <div id="embed-file-title"><strong><?php _e('Embed PDF/Audio/Video Shortcode Generator: ','maxgalleria-media-library') ?></strong></div>

        <p>						
          <label id="embed-file-label"><?php _e('Embed File: ','maxgalleria-media-library') ?></label>
          <input type="text" id="embed-file-url" value="" />
          <input type="hidden" id="embed-file-type" value="" />
        </p>

        <p id="ogg-options">						
          <label id="embed--ogg-file-type"><?php _e('Ogg File Type: ','maxgalleria-media-library') ?></label>
          <input type="radio" id="ogg-audio" name="ogg-type" value="audio"> <label for="ogg-audio">Audio</label>
          <input type="radio" id="ogg-video" name="ogg-type" value="video" checked="checked"> <label for="ogg-video">Video</label>
        </p>

        <p id="embed-type-row" style="display:none">						
          <label id="embed-file-label"><?php _e('Type: ','maxgalleria-media-library') ?></label>
          <input type="text" id="embed-type", value="application/pdf" />
        </p>

        <p class="mlfp-video-options mlfp-pdf-options">						
          <label id="embed-file-label"><?php _e('Width (px or %): ','maxgalleria-media-library') ?></label>
          <input type="text" id="embed-file-width", value="100%" />
          <label id="embed-file-label"><?php _e('Height (px): ','maxgalleria-media-library') ?></label>
          <input type="text" id="embed-file-height", value="400px" />
          <span class="mlfp-pdf-options"><label id="embed-file-label"><?php _e('Align: ','maxgalleria-media-library') ?></label>
          <select id="embed-align">            
            <option value="none"><?php _e('None','maxgalleria-media-library') ?>:</option>
            <option value="left"><?php _e('Left','maxgalleria-media-library') ?>:</option>
            <option value="right"><?php _e('Right','maxgalleria-media-library') ?>:</option>
          </select></span>
        </p>

        <p class="mlfp-video-options mlfp-audio-options">						
          <span class="embed-option"><input type="checkbox" id="embed-autoplay" > <label><?php _e('Autoplay','maxgalleria-media-library') ?></label></span>
          <span class="embed-option"><input type="checkbox" id="embed-controls" > <label><?php _e('Controls','maxgalleria-media-library') ?></label></span>
          <span class="embed-option"><input type="checkbox" id="embed-loop" > <label><?php _e('Loop','maxgalleria-media-library') ?></label></span>
          <span class="embed-option"><input type="checkbox" id="embed-muted" > <label><?php _e('Muted','maxgalleria-media-library') ?></label></span>
          <span class="embed-option"><label><?php _e('Preload','maxgalleria-media-library') ?></label>&nbsp;<select id="embed-preload" >
            <option value="auto"><?php _e('Auto','maxgalleria-media-library') ?></option>
            <option value="metadata"><?php _e('Metadata','maxgalleria-media-library') ?></option>
            <option value="none"><?php _e('None','maxgalleria-media-library') ?></option>
          <select></span>            
        </p>

        <p id="poster-row" class="mlfp-video-options">						
          <label id="poster-label"><?php _e('Poster (optional)','maxgalleria-media-library') ?></label>	
          <input type="text" id="embed-poster" value="">	
        </p>
        <div class="clearfix"></div>

        <p>
          <textarea id="embed-shortcode-container"></textarea>
        </p>
        <p>
          <a id="generate-shortcode" class="gray-blue-link"><?php _e('Generate Shortcode','maxgalleria-media-library') ?></a>
          <button type="button" id="copy-shortcode" class="gray-blue-link disabled-button" disabled>Copy to Clipboard</button>
        </p>
        <p id="copy-message"></p>

      </div>
    </div>
    <div class="clearfix"></div>
    
    <?php if($this->license_valid) { ?>
      <?php if(class_exists(  'C_NextGEN_Bootstrap') && !class_exists('Amazon_S3_And_CloudFront')) { ?>
        
      <div id="add-to-gallery-area" class="input-area">
        <div id="images-to-gallery-box">
          <a id="close-add-to-ng-gallery" title="<?php esc_html_e('Close embed box','maxgalleria-media-library') ?>">x</a>
          <div id="nextgen-title"><strong><?php _e('Add Images to NextGen Gallery','maxgalleria-media-library') ?></strong></div>  
          
      <?php    
      $sql = "SELECT gid, title FROM {$wpdb->prefix}ngg_gallery ORDER BY name";
      //echo $sql;
      $ng_gallery_list = "";
      $rows = $wpdb->get_results($sql);

      if($rows) {
        foreach ($rows as $row) {
          $ng_gallery_list .='<option value="' . $row->gid . '">' . $row->title . '</option>';
        }
      }
      _e('Galleries: ','maxgalleria-media-library');
      ?>
          <select id="ng-gallery-select">
      <?php      
      echo $ng_gallery_list;
      ?>
          </select>
      <div class="btn-wrap"><a id="mlpp-add-to-ng-gallery" class="gray-blue-link" ><?php _e('Add Images','maxgalleria') ?></a></div>

        </div>                        
      </div>
      <div class="clearfix"></div>
      <?php } ?>
    <?php } ?>
      
    <div id="icon-help-area" class="input-area">
      <div class="icon-column">
        <ul>
          <li><i class="fa-solid fa-folder-plus fa-2x help_info"></i> - <?php _e('New Folder','maxgalleria-media-library') ?></li>
          <li><i class="fa-solid fa-upload fa-2x help_info"></i> - <?php _e('New File','maxgalleria-media-library') ?></li>
          <li><i class="fa-solid fa-arrows-rotate fa-2x help_info"></i> - <?php _e('Refresh Folders','maxgalleria-media-library') ?></li>
          <li><i class="fa-solid fa-border-all fa-2x help_info"></i> - <?php _e('Grid View','maxgalleria-media-library') ?></li>
          <li><i class="fa-solid fa-table-list fa-2x help_info"></i> - <?php _e('List View','maxgalleria-media-library') ?></li>
        </ul>
      </div>  
      <div class="icon-column">
        <ul>
          <li><i class="fa-solid fa-file-import fa-2x help_info"></i> - <?php _e('Move File Mode','maxgalleria-media-library') ?></li>
          <li><i class="fa-solid fa-clone fa-2x help_info"></i> <?php _e('Copy File Mode','maxgalleria-media-library') ?></li>
          <li><i class="fa-solid fa-calendar-days fa-2x help_info"></i> -  <?php _e('Sort by Date','maxgalleria-media-library') ?></li>
          <li><i class="fa-solid fa-file-image fa-2x help_info"></i> - <?php _e('Sort by Title','maxgalleria-media-library') ?></li>
          <li><i class="fa-solid fa-arrows-up-down fa-2x help_info"></i> - <?php _e('Reverse Order','maxgalleria-media-library') ?></li>
        </ul>
      </div>  
      <div class="icon-column">
        <ul>
          <li><i class="fa-solid fa-pen fa-2x help_info"></i> - <?php _e('Rename File','maxgalleria-media-library') ?></li>
          <li><i class="fas fa-bolt fa-2x help_info"></i> - <?php _e('Sync Files','maxgalleria-media-library') ?></li>
          <li><i class="far fa-object-group fa-2x help_info"></i> - <?php _e('Regenerate Thumbnails','maxgalleria-media-library') ?></li>
          <li><i class="fas fa-trash fa-2x help_info"></i> - <?php _e('Delete Files','maxgalleria-media-library') ?></li>
          <li><i class="fa-regular fa-image fa-2x help_info"></i> - <?php _e('Create a NextGen Gallery','maxgalleria-media-library') ?></li>
        </ul>
      </div>  
      <div class="icon-column">
        <ul>
          <li><i class="fa-solid fa-file-arrow-up fa-2x help_info"></i> - <?php _e('Replace a File','maxgalleria-media-library') ?></li>
          <li><i class="fa-solid fa-boxes-stacked fa-2x help_info"></i> - <?php _e('Display the Categories Box','maxgalleria-media-library') ?></li>
          <li><i class="fa-regular fa-circle-question fa-2x help_info"></i> - <?php _e('Display the Help Panel','maxgalleria-media-library') ?></li>
        </ul>
      </div>  
      
      <div style="clear:both"></div>
      
    </div>
      
    <div id="category-area" class="input-area">
      <div id="category-area-buttons">
        <a id="mgmlp-new-category" class="gray-blue-link" ><?php _e('Add New Category','maxgalleria-media-library') ?></a>
        <a id="mgmlp-set_categories" class="gray-blue-link" ><?php _e('Set Categories','maxgalleria-media-library') ?></a>
        <a id="mgmlp-get_categories" class="gray-blue-link" ><?php _e('Get Categories','maxgalleria-media-library') ?></a>
        <a id="mgmlp-view-categories" class="gray-blue-link"  ><?php _e('View by Category','maxgalleria-media-library') ?></a>

        <div id="sort-wrap"><select id="mgmlp-cat-sort-order" class="gray-blue-link">
        <option value="1" <?php ($cat_sort_order === '1' ? 'selected="selected"' : ''  ) ?> ><?php _e('Sort by Title','maxgalleria-media-library') ?></option>
        <option value="0" <?php ($cat_sort_order === '0' ? 'selected="selected"' : ''  ) ?> ><?php _e('Sort by Date','maxgalleria-media-library') ?></option>
      </select></div>

      </div>

    <div id="new-cat-area">
      <div id="category-box">
        <div class="mlf-edit_wrap">						
          <?php _e('Category Name: ','maxgalleria-media-library') ?><input type="text" name="new-category-name" id="new-category-name" value="" />
       </div>
        <div class="btn-wrap"><a id="mgmlp-add-category" class="gray-blue-link" ><?php _e('Add Category','maxgalleria-media-library') ?></a></div>
      </div>
    </div>
    <div class="clearfix"></div>						

    <div id="category-list">
    </div>

    </div>
    <div class="clearfix"></div>    
        
    <div id="mgmlp-file-container">
      <?php $this->display_folder_contents ($current_folder_id); ?>
    </div>
    
  </div><!--mgmlp-library-container-->
    
</div><!--mgmlp-outer-container-->

<div id="mlf-new-folder-popup">
  <div class="mlf-popup-content">
    <h2><?php esc_html_e('New Folder','maxgalleria-media-library') ?></h2>
    <a class="close-popup" title="<?php esc_html_e('Close without saving','maxgalleria-media-library') ?>">x</a> 
    <hr>
    
    <div class="popup-content-bottom">
    <?php esc_html_e('Folder Name: ','maxgalleria-media-library') ?><input type="text" name="new-folder-name" id="new-folder-name" value="" />
    <div class="btn-wrap"><a id="mgmlp-create-new-folder" class="gray-blue-link" ><?php esc_html_e('Create Folder','maxgalleria-media-library') ?></a></div>
    </div>
        
  </div>
</div>  

<div id="mlf-rename-popup">
  <div class="mlf-popup-content">
    <h2><?php esc_html_e('Rename The Selected File','maxgalleria-media-library') ?></h2>
    <a id="close-rename-popup" title="<?php esc_html_e('Close without renaming','maxgalleria-media-library') ?>">x</a> 
    <hr>
    
    <div class="popup-content-bottom">
    <?php esc_html_e('New File Name: ','maxgalleria-media-library') ?><input type="text" name="new-file-name" id="new-file-name" value="" />
    <div class="btn-wrap"><a id="mgmlp-rename-file" class="gray-blue-link" ><?php esc_html_e('Rename File','maxgalleria-media-library') ?></a></div>
    </div>
        
  </div>
</div>  

<?php						            
  if(class_exists('MaxGalleria') || class_exists('MaxGalleriaPro')) {
    $gallery_list = $this->get_maxgalleria_galleries();
    $allowed_html = array(
      'option' => array(
        'value' => array()
      )    
    );
  ?>

<div id="mlf-add-to-gallery-popup">
  <div class="mlf-popup-content">
    <h2><?php esc_html_e('Add images to MaxGalleria Gallery','maxgalleria-media-library') ?></h2>
    <a id="close-gallery-popup" title="<?php esc_html_e('Close without renaming','maxgalleria-media-library') ?>">x</a> 
    <hr>
    
    <div class="popup-content-bottom">
      
      <select id="gallery-select">
        <option disabled ><?php esc_html_e('Select a gallery','maxgalleria-media-library') ?></option>
        <?php echo wp_kses($gallery_list, $allowed_html) ?>
      </select>
      <div class="btn-wrap"><a id="add-to-gallery" class="gray-blue-link" ><?php esc_html_e('Add Images','maxgalleria') ?></a></div>
            
    </div>
        
  </div>
</div>  

<?php if($this->license_valid) { ?>
  <?php if(class_exists('C_NextGEN_Bootstrap') && !class_exists('Amazon_S3_And_CloudFront')) { ?>
    <div id="new-ng-gallery-popup">
      <div class="mlf-popup-content">
        
        <h2><?php esc_html_e('Create New NextGen Gallery','maxgalleria-media-library') ?></h2>
        <a id="close-new-ng-gallery-popup" title="<?php esc_html_e('Close without saving','maxgalleria-media-library') ?>">x</a> 
        <hr>
        <input type="hidden" id="ng-current-folder-id" value="' . $current_folder_id . '" />
        <div>
          <?php _e('Gallery Name: ','maxgalleria-media-library') ?><input type="text" name="new-gallery-name" id="new-gallery-name", value="" />
          <div class="btn-wrap"><a id="mgmlp-create-new-gallery" class="gray-blue-link" ><?php _e('Create NG Gallery','maxgalleria-media-library') ?></a></div>
      </div>        
    </div>
  <?php } ?>	
<?php } ?>	



<?php } ?>

<script>
  
window.onerror = function(msg, url, linenumber) {
  jQuery("#folder-message").html('Javascript error : ' + msg );
  return true;
}
  
jQuery(document).ready(function(){
	var categories_visible = false;
  var search_progress = true;
  var bulk_move_status = false;
  var allow_bulk_move = true;
  var stop_bulk_move = false;
  window.click_to_edit_image = true;
    
//  jQuery('#mlf-help-info').on('click', function (e) {
//    e.stopImmediatePropagation();
//    icon-help-area
//  });

  jQuery('#mlf-add-folder').on('click', function (e) {
    e.stopImmediatePropagation();
    jQuery('#mlf-new-folder-popup').fadeIn(300);
    jQuery('#new-folder-name').focus();    
  });
  
  jQuery('#new-folder-name').keydown(function (e){
    e.stopImmediatePropagation();
    console.log('enter key press');
    if(e.keyCode == 13){                
      var new_folder_name = jQuery('#new-folder-name').val();
      console.log('new_folder_name',new_folder_name);
      create_new_folder(new_folder_name);
    }  else if(e.keyCode == 27) {
      jQuery('#mlf-new-folder-popup').fadeOut(300);      
      jQuery('#new-folder-name').val('');
    }
  });    
  
  jQuery('#mgmlp-create-new-folder').on('click', function (e) {
    e.stopImmediatePropagation();
    var new_folder_name = jQuery('#new-folder-name').val();
    jQuery('#mlf-new-folder-popup').fadeOut(300);      
    create_new_folder(new_folder_name);
  });    
  
  jQuery('#cancel-button, .close-popup').on('click', function (e) {
    e.stopImmediatePropagation();
    jQuery('#mlf-new-folder-popup').fadeOut(300);
  });
  
  jQuery('#mlf-refresh-folders').on('click', function (e) {
    e.stopImmediatePropagation();        
    jQuery("#ajaxloader").show();
    
    if(jQuery("#current-folder-id").val() === undefined) 
      var parent_folder = sessionStorage.getItem('folder_id');
    else
      var parent_folder = jQuery('#current-folder-id').val();
    
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlf_check_for_new_folders", parent_folder: parent_folder, nonce: mgmlp_ajax.nonce },
      url : mgmlp_ajax.ajaxurl,
      dataType: "json",
      success: function (data) {
        console.log('message',data.message);
        jQuery("#folder-tree").addClass("bound").on("select_node.jstree", show_mlp_node);							
        jQuery("#folder-message").html(data.message);
        jQuery("#ajaxloader").hide();          
        if(data.refresh) {
          jQuery('#folder-tree').jstree(true).settings.core.data = data.folders;						
          jQuery('#folder-tree').jstree(true).refresh();			
          jQuery('#folder-tree').jstree('select_node', '#' + parent_folder, true);
          jQuery('#folder-tree').jstree('toggle_expand', '#' + parent_folder, true );
        }
      },
      error: function (err)
        { alert(err.responseText);}
    });
    
  });
  
  jQuery('#close-refresh-popup').on('click', function (e) {
    e.stopImmediatePropagation();
    jQuery('#mlf-refresh-folders-popup').fadeOut(300);        
  });
  
  jQuery(document).on("click", "#mlf-rename-mf", function (e) {
    e.stopImmediatePropagation();
    console.log('mlf-rename-mf');
    var image_id = 0;
    var file_name = '';
    var found = false;
    
    jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
      // only get the first one
      image_id = jQuery(this).attr("id");
      if(jQuery(this).parent().siblings('.mlfp-list-file').length) {
        file_name = jQuery(this).parent().siblings('.mlfp-list-file').text();
      } else {
        file_name = jQuery(this).closest('.attachment-name').find('.mlf-filename').text();
      }
      // remove the extention
      file_name = file_name.substr(0, file_name.lastIndexOf('.')) || file_name;
      found = true;
      jQuery('#new-file-name').val(file_name);
      return false;
    });
    
    if(!found) {
      alert(mgmlp_ajax.nothing_selected);
      return false;
    }
    
    jQuery('#mlf-rename-popup').fadeIn(300);    
    jQuery('#new-file-name').val(file_name);
    jQuery('#new-file-name').focus();    
  });
  
  jQuery('#close-rename-popup').on('click', function (e) {
    e.stopImmediatePropagation();
    jQuery('#mlf-rename-popup').fadeOut(300);
  });
      
  jQuery('#mgmlp-rename-file').on('click', function (e) {
    e.stopImmediatePropagation();
    
    jQuery("#folder-message").html('');			

    if(jQuery("#current-folder-id").val() === undefined) 
      var current_folder = sessionStorage.getItem('folder_id');
    else
      var current_folder = jQuery('#current-folder-id').val();

    var image_id = 0;
    var new_file_name = jQuery('#new-file-name').val();

    new_file_name = new_file_name.trim();

    jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
      // only get the first one
      //if(image_id === 0)
        image_id = jQuery(this).attr("id");
        return false;
    });

    if(new_file_name == "") {
      alert(mgmlp_ajax.no_blank_filename);
      return false;
    }                 

    if(new_file_name.indexOf(' ') >= 0 || new_file_name === '' ) {
      alert(mgmlp_ajax.valid_file_name);
      return false;
    }       

    jQuery("#ajaxloader").show();

    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "maxgalleria_rename_image", image_id: image_id, new_file_name: new_file_name, nonce: mgmlp_ajax.nonce },
      url : mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) {        
        jQuery('#mlf-rename-popup').fadeOut(300);  
        jQuery("#folder-message").html(data);
        jQuery('#new-file-name').val('');
        jQuery(".mgmlp-media").prop('checked', false);
        mlf_refresh(current_folder);
        jQuery('#mlf-rename-popup').fadeOut(300);
        jQuery("#ajaxloader").hide();
      },
      error: function (err) { 
        jQuery("#ajaxloader").hide();
        alert(err.responseText);
      }
    });
        
  });
  
  jQuery('#mlf-move').on('click', function (e) {
    e.stopImmediatePropagation();
    var move_copy_switch = 'on';
  
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mgmlp_move_copy", move_copy_switch: move_copy_switch, nonce: mgmlp_ajax.nonce },
      url : mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) {
        jQuery('.fa-file-import').addClass('mlf-active');
        jQuery('.fa-clone').removeClass('mlf-active');
        jQuery('#move-or-copy-status').val(move_copy_switch);
        jQuery("#folder-message").html(mgmlp_ajax.move_mode);			        
      },
      error: function (err) { 
        alert(err.responseText);
      }
    });
  });
    
  jQuery('#mlf-copy').on('click', function (e) {
    e.stopImmediatePropagation();
    
    var move_copy_switch = 'off';
  
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mgmlp_move_copy", move_copy_switch: move_copy_switch, nonce: mgmlp_ajax.nonce },
      url : mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) {        
        jQuery('.fa-clone').addClass('mlf-active');
        jQuery('.fa-file-import').removeClass('mlf-active');
        console.log('move_copy_switch',move_copy_switch);
        jQuery('#move-or-copy-status').val(move_copy_switch);
        jQuery("#folder-message").html(mgmlp_ajax.copy_mode);			        
      },
      error: function (err) { 
        alert(err.responseText);
      }
    });                
  });  
  
  
  jQuery('#mlf-sort-date').on('click', function (e) {
    e.stopImmediatePropagation();
  
    console.log('mlf-sort-date');
    var sort_order = '0';
    
    if(jQuery(this).hasClass('mlfp-busy')) {
      return false;
    }
        
    jQuery('#mlf-sort-title').addClass('mlfp-busy');
    jQuery('#mlf-sort-reverse').addClass('mlfp-busy');
    
    if(jQuery("#current-folder-id").val() === undefined) 
      var current_folder = sessionStorage.getItem('folder_id');
    else
      var current_folder = jQuery('#current-folder-id').val();

    jQuery("#ajaxloader").show();

    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "sort_contents", sort_order: sort_order, folder: current_folder, nonce: mgmlp_ajax.nonce },        
      url : mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) {
        jQuery("#mgmlp-file-container").html(data); 
        jQuery('.fa-calendar-days').addClass('mlf-active');
        jQuery('.fa-file-image').removeClass('mlf-active');
        jQuery('#mlf-sort-title').removeClass('mlfp-busy');
        jQuery('#mlf-sort-reverse').removeClass('mlfp-busy');        
        jQuery("#ajaxloader").hide();
      },
      error: function (err) { 
        jQuery("#ajaxloader").hide();
        alert(err.responseText);
      }
    });                
  });     
  
  jQuery('#mlf-sort-title').on('click', function (e) {
    e.stopImmediatePropagation();
    
    console.log('mlf-sort-title');
    
    if(jQuery(this).hasClass('mlfp-busy')) {
      return false;
    }
    
    jQuery('#mlf-sort-date').addClass('mlfp-busy');
    jQuery('#mlf-sort-reverse').addClass('mlfp-busy');
    
    var sort_order = '1';

    if(jQuery("#current-folder-id").val() === undefined) 
      var current_folder = sessionStorage.getItem('folder_id');
    else
      var current_folder = jQuery('#current-folder-id').val();

    jQuery("#ajaxloader").show();

    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "sort_contents", sort_order: sort_order, folder: current_folder, nonce: mgmlp_ajax.nonce },        
      url : mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) {
        jQuery("#mgmlp-file-container").html(data); 
        jQuery('.fa-file-image').addClass('mlf-active');
        jQuery('.fa-calendar-days').removeClass('mlf-active');
        jQuery('#mlf-sort-date').removeClass('mlfp-busy');
        jQuery('#mlf-sort-reverse').removeClass('mlfp-busy');        
        jQuery("#ajaxloader").hide();
      },
      error: function (err) { 
        jQuery("#ajaxloader").hide();
        alert(err.responseText);
      }
    });                
  });    
  
  jQuery('#mlf-sort-reverse').on('click', function (e) {
    e.stopImmediatePropagation();
    
    if(jQuery(this).hasClass('mlfp-busy')) {
      return false;
    }    
    
    jQuery('#mlf-sort-date').addClass('mlfp-busy');
    jQuery('#mlf-sort-title').addClass('mlfp-busy');
        
    if(jQuery("#current-folder-id").val() === undefined) 
      var current_folder = sessionStorage.getItem('folder_id');
    else
      var current_folder = jQuery('#current-folder-id').val();
        
    var sort_type = jQuery('#sort-type').val();
    console.log('sort_type start ', sort_type);
    
    sort_type = (sort_type == 'ASC') ? 'DESC' : 'ASC';    
    console.log('sort_type new ', sort_type);
        
    jQuery("#ajaxloader").show();

    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlf_change_sort_type", sort_type: sort_type, folder: current_folder, nonce: mgmlp_ajax.nonce },        
      url : mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) {
        jQuery("#mgmlp-file-container").html(data); 
        jQuery('#sort-type').val(sort_type);
        //jQuery('.fa-file-image').addClass('mlf-active');
        //jQuery('.fa-calendar-days').removeClass('mlf-active');
        jQuery('#mlf-sort-date').removeClass('mlfp-busy');
        jQuery('#mlf-sort-title').removeClass('mlfp-busy');
        jQuery("#ajaxloader").hide();
      },
      error: function (err) { 
        jQuery("#ajaxloader").hide();
        alert(err.responseText);
      }
    });                


  });    
    
  jQuery('#mgmlp-media-search-input').keydown(function (e){
    e.stopImmediatePropagation();
    if(e.keyCode == 13){                
      do_mlfp_search();
    }  
  })    

  jQuery(document).on("click", "#mlfp-media-search", function (e) {
    e.stopImmediatePropagation();
    do_mlfp_search();
  })    
  
  jQuery(document).on("click", "#mlp-bulk-apply", function (e) {
    e.stopImmediatePropagation();
        
    var bulk_selection = jQuery('#mlf-bulk-select').val();
    
    if(jQuery(this).hasClass("disabled-button")) {
      console.log('disabled-button');
      return false;
    }
    
    console.log('bulk_selection',bulk_selection);
          
    switch(bulk_selection) {
      
      case 'mlf-bulk-delete':
        bulk_delete_files();
        break;
        
      case 'mlf-regen-thumbnails':
        bulk_regenerate_thumbnails();
        break;
        
      case 'mlf-bulk-move':
        prepare_bulk_move();
        break;
        
      case 'mlfp-playlist':
        prepare_play_list();
        break;        
        
      case 'mlfp-jp-gallery':
        display_jp_gallery_area();
        break;
        
      case 'mlfp-embed':  
        display_embed_area();
        break;
        
      case 'mlfp-add-to-ng':  
        display_nextgen_area();
        break;
        
      case 'mlfp-rollback-scaled':
        rollbackp_scaled_images();
        break;
        
      case 'mlfp-s3-upload':  
        upload_to_s3();
        break;
        
      case 'mlfp-s3-download':  
        download_from_s3();
        break;
    }  
    
  })
    
  jQuery(document).on("click", "#mlf-select-all", function (e) {
    e.stopImmediatePropagation();
    jQuery(".media-attachment, .mgmlp-media").prop("checked", !jQuery(".media-attachment").prop("checked"));    
  })
  
//  jQuery(document).on("click", "#mlf-rename-mf", function () {
//    //var attachment_id = jQuery(this).closest('div[id]'); 
//    var attachment_id  = jQuery('.media-attachment').closest('id').data('id')
//    console.log('attachment_id',attachment_id);
//  })
  
  jQuery(document).on("click", "#mlf-delete-mf", function (e) {
    e.stopImmediatePropagation();
    var attachment_id = jQuery(this).closest('div[id]'); 
    console.log('attachment_id',attachment_id);
  })
    
  jQuery(document).on("click", "#sync-media", function (e) {
    e.stopImmediatePropagation();

    if(jQuery("#current-folder-id").val() === undefined) 
      var parent_folder = sessionStorage.getItem('folder_id');
    else
      var parent_folder = jQuery('#current-folder-id').val();

    var mlp_title_text = jQuery('#mlp_title_text').val();

    var mlp_alt_text = jQuery('#mlp_alt_text').val();      

    jQuery("#ajaxloader").show();

    run_sync_process('1', parent_folder, mlp_title_text, mlp_alt_text);

    jQuery("#ajaxloader").hide();

  });
  
  jQuery('#add-mg-gallery').on('click', function (e) {
    e.stopImmediatePropagation();
    console.log('add-mg-gallery');
    jQuery('#mlf-add-to-gallery-popup').fadeIn(300);
  });    
    
  jQuery('#close-gallery-popup').on('click', function (e) {
    e.stopImmediatePropagation();
    jQuery('#mlf-add-to-gallery-popup').fadeOut(300);
  });
  
  
  jQuery(document).on("click", "#add-to-gallery", function (e) {
    e.stopImmediatePropagation();

    jQuery("#folder-message").html('');			

    var gallery_image_ids = new Array();
    jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
      gallery_image_ids[gallery_image_ids.length] = jQuery(this).attr("id");
    });

    if(gallery_image_ids.length > 0) {

      var serial_gallery_image_ids = JSON.stringify(gallery_image_ids.join());
      var gallery_id = jQuery('#gallery-select').val();
      
      console.log('serial_gallery_image_ids',serial_gallery_image_ids);

      jQuery("#ajaxloader").show();
      jQuery("#mlf-add-to-gallery-popup").fadeOut(300);

      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "add_to_max_gallery", gallery_id: gallery_id, serial_gallery_image_ids: serial_gallery_image_ids, nonce: mgmlp_ajax.nonce },
        url : mgmlp_ajax.ajaxurl,
        dataType: "html",
        success: function (data) {
          jQuery("#ajaxloader").hide();
          jQuery("#folder-message").html(data);
          jQuery(".mgmlp-media").prop('checked', false);
        },
        error: function (err) { 
          jQuery("#ajaxloader").hide();
          alert(err.responseText);
        }
      });  
    } else {
      alert(mgmlp_ajax.no_images_selected);
    }
  });	
    
  jQuery(document).on("click", "#mlf-grid", function (e) {
    e.stopImmediatePropagation();
    
    jQuery("#ajaxloader").show();    
    
    var grid_list_switch = 'on';
    
    if(jQuery("#current-folder-id").val() === undefined) 
      var current_folder = sessionStorage.getItem('folder_id');
    else
      var current_folder = jQuery('#current-folder-id').val();
              
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mgmlp_grid_list", grid_list_switch: grid_list_switch, nonce: mgmlp_ajax.nonce },
      url : mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) {
        console.log('hiding list columns');
        jQuery("table.mgmlp-list").hide();
        jQuery('.fa-border-all').addClass('mlf-active');
        jQuery('.fa-table-list').removeClass('mlf-active');
        jQuery('#grid-list-switch-view').val(grid_list_switch);
        mlf_refresh(current_folder);
      },
      error: function (err) { 
        alert(err.responseText);
      }
    });
    
  });	
  
  jQuery(document).on("click", "#mlf-list", function (e) {
    e.stopImmediatePropagation();
    
    jQuery("#ajaxloader").show();
    
    var grid_list_switch = 'off';
    
    if(jQuery("#current-folder-id").val() === undefined) 
      var current_folder = sessionStorage.getItem('folder_id');
    else
      var current_folder = jQuery('#current-folder-id').val();
      
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mgmlp_grid_list", grid_list_switch: grid_list_switch, nonce: mgmlp_ajax.nonce },
      url : mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) {
        console.log('showing list columns');
        jQuery("table.mgmlp-list").show();
        jQuery('.fa-table-list').addClass('mlf-active');
        jQuery('.fa-border-all').removeClass('mlf-active');
        jQuery('#grid-list-switch-view').val(grid_list_switch);
        mlf_refresh(current_folder);
      },
      error: function (err) { 
        alert(err.responseText);
      }
    });
        
  });
  
  jQuery(document).on("click", "#mlf-select-all", function () {
    jQuery(".media-attachment, .mgmlp-media").prop("checked", !jQuery(".media-attachment").prop("checked"));    
  })
    
  jQuery(document).on("change", "#mlf-search-select", function () {			
    
    var search_type = jQuery("select#mlf-search-select option").filter(":selected").val();
    var grid_list_switch = jQuery('#grid-list-switch-view').val();
    
    if(jQuery("#current-folder-id").val() === undefined) 
      var current_folder = sessionStorage.getItem('folder_id');
    else
      var current_folder = jQuery('#current-folder-id').val();
        
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlfp_save_search_type", search_type: search_type, nonce: mgmlp_ajax.nonce },
      url : mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) {
        console.log('showing list columns');
        jQuery("table.mgmlp-list").show();
        jQuery('.fa-table-list').addClass('mlf-active');
        jQuery('.fa-border-all').removeClass('mlf-active');
        jQuery('#grid-list-switch-view').val(grid_list_switch);
        mlf_refresh(current_folder);
      },
      error: function (err) { 
        alert(err.responseText);
      }
    });
    
    
  
  });	
  
  jQuery('#mlf-search-clear').on('click', function () {
    jQuery('#mgmlp-media-search-input').val('');
    
    var select_type = jQuery("select#mlf-search-select option").filter(":selected").val();

    if(select_type == 'filter') {
      
      jQuery("#ajaxloader").show();
      
      if(jQuery("#current-folder-id").val() === undefined) 
        var parent_folder = sessionStorage.getItem('folder_id');
      else
        var parent_folder = jQuery('#current-folder-id').val();

      mlf_refresh(parent_folder);
      
      jQuery("#ajaxloader").hide();
      
    }   

  });	
  
  <?php if($this->license_valid) { ?>

  jQuery("#mgmlp-media-search-input").on("keyup", function(e) {

    //console.log('keyCode',e.keyCode);
        
    var select_type = jQuery("select#mlf-search-select option").filter(":selected").val();
    //console.log("select_type", select_type);

    if(e.keyCode != 12 && 
       e.keyCode != 32 &&
       e.keyCode != 37 &&
       e.keyCode != 38 &&
       e.keyCode != 39 &&
       e.keyCode != 40 && select_type == 'filter') {

      if(jQuery("#current-folder-id").val() === undefined) 
        var parent_folder = sessionStorage.getItem('folder_id');
      else
        var parent_folder = jQuery('#current-folder-id').val();

      var filter = jQuery("#mgmlp-media-search-input").val();
      filter = filter.toLowerCase();
      //console.log('filter', filter);

      if(filter.length < 1) {
        mlf_refresh(parent_folder);
        return false;
      }
      var display_type = jQuery("#display_type").val();

      var grid_list_switch = jQuery('#grid-list-switch-view').val();
      grid_list_switch = (grid_list_switch == 'on') ? 'true' : 'false';

      jQuery("#ajaxloader").show();

      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mgmlp_filter_images", folder_id: parent_folder, filter: filter, display_type: display_type, grid_list_switch: grid_list_switch, nonce: mgmlp_ajax.nonce },
        url : mgmlp_ajax.ajaxurl,
        dataType: "html",
        success: function (data) {
          console.log(data);
          jQuery("#mgmlp-file-container").html(data); 
          jQuery("#ajaxloader").hide();
        },
        error: function (err){
            jQuery("#ajaxloader").hide();
            alert(err.responseText);            
          }
      });


    }
            
  });
  
  <?php } ?>

  jQuery(document).on("click", ".mlf-previous-page, .mlf-next-page", function (e) {
    e.stopImmediatePropagation();
    
    //console.log('mlf-next-page');
    
    if(jQuery(this).hasClass("disabled")) {
      console.log('disabled');
      return false;
    }

    jQuery("#ajaxloader").show();

    var filter_text = '';
    var search_type = jQuery("select#mlf-search-select option").filter(":selected").val();
    if(search_type == 'filter') {      
      filter_text = jQuery("#mgmlp-media-search-input").val();
    }

    if(jQuery("#current-folder-id").val() === undefined) 
      var current_folder_id = sessionStorage.getItem("folder_id");
    else
      var current_folder_id = jQuery("#current-folder-id").val();

    var page_id = jQuery(this).attr("data-page-id");
    var page_type = jQuery(this).attr("data-type");
    
    if(page_type == 'next')
      var current_page = parseInt(page_id) + 1;
    else
      var current_page = parseInt(page_id) - 1;
    
    var grid_list_switch = jQuery('#grid-list-switch-view').val();
    grid_list_switch = (grid_list_switch == 'on') ? 'true' : 'false';

    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlfp_get_next_attachments", current_folder_id: current_folder_id, page_id: page_id, image_link: '1', grid_list_switch: grid_list_switch, nonce: mgmlp_ajax.nonce },
      url: mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) {
        jQuery("#ajaxloader").hide();    
        jQuery("#mgmlp-file-container").html(data);
      },
      error: function (err){
        jQuery("#ajaxloader").hide();
        alert(err.responseText);
      }
    });
  });
  
  jQuery(document).on("click", ".mlf-first-page", function (e) {
    
    e.stopImmediatePropagation();
        
    if(jQuery(this).hasClass("disabled")) {
      return false;
    }

    jQuery("#ajaxloader").show();

    if(jQuery("#current-folder-id").val() === undefined) 
      var current_folder_id = sessionStorage.getItem("folder_id");
    else
      var current_folder_id = jQuery("#current-folder-id").val();

    var page_type = jQuery(this).attr("data-type");
    
    var grid_list_switch = jQuery('#grid-list-switch-view').val();
    grid_list_switch = (grid_list_switch == 'on') ? 'true' : 'false';

    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlfp_get_next_attachments", current_folder_id: current_folder_id, page_id: 0, image_link: '1', grid_list_switch: grid_list_switch, nonce: mgmlp_ajax.nonce },
      url: mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) {
        jQuery("#ajaxloader").hide();    
        jQuery("#mgmlp-file-container").html(data);
      },
      error: function (err){
        jQuery("#ajaxloader").hide();
        alert(err.responseText);
      }
    });
  });
    
  jQuery(document).on("click", ".mlf-last-page", function (e) {
    
    e.stopImmediatePropagation();
        
    if(jQuery(this).hasClass("disabled")) {
      return false;
    }

    jQuery("#ajaxloader").show();

    if(jQuery("#current-folder-id").val() === undefined) 
      var current_folder_id = sessionStorage.getItem("folder_id");
    else
      var current_folder_id = jQuery("#current-folder-id").val();

    var page_type = jQuery(this).attr("data-type");
        
    var last_page = parseInt(jQuery("#mlfp-last-page").val()) - 1;
    
    var grid_list_switch = jQuery('#grid-list-switch-view').val();
    grid_list_switch = (grid_list_switch == 'on') ? 'true' : 'false';
    
    jump_to_page(current_folder_id, last_page, grid_list_switch);
    
  });
    
  jQuery(document).on("keydown", ".mlf-page", function (e) {
  //jQuery('.mlf-page').keydown(function (e){
    e.stopImmediatePropagation();
    console.log('keydown');
    if(e.keyCode == 13){
      
      jQuery("#ajaxloader").show();

      if(jQuery("#current-folder-id").val() === undefined) 
        var current_folder_id = sessionStorage.getItem("folder_id");
      else
        var current_folder_id = jQuery("#current-folder-id").val();

      var last_page = parseInt(jQuery("#mlfp-last-page").val()) - 1;
      
      var grid_list_switch = jQuery('#grid-list-switch-view').val();
      grid_list_switch = (grid_list_switch == 'on') ? 'true' : 'false';      
      
      var new_page_id = parseInt(jQuery(this).val());
      console.log('new_page_id',new_page_id);
      var last_page = parseInt(jQuery("#mlfp-last-page").val());
      console.log('last_page',last_page);
      if(new_page_id > 0 && new_page_id < (last_page+1)) {
        jump_to_page(current_folder_id, (new_page_id - 1), grid_list_switch);        
      } else {
        jQuery("#ajaxloader").hide();        
      }
    }  
  })    
  
  jQuery(document).on("click", "#close-bulk-move-popup", function (e) {    
    e.stopImmediatePropagation();
    jQuery("#bulk-move-area").slideUp(600);                
    jQuery("#mlp-bulk-apply").removeClass("disabled-button");
    jQuery('#library-help').show();
    jQuery('#bulk-move-help').hide();
    
  });
  
  jQuery(document).on("click", "#close-mlf-playlist", function (e) {    
    e.stopImmediatePropagation();
    window.click_to_edit_image = true;
    jQuery("a.media-attachment").css("cursor", "crosshair");    
    jQuery("#playlist-area").slideUp(600);
    jQuery("#pl_attachment_ids").val('');
    jQuery("#playlist-shortcode-container").val('');   
    jQuery("#folder-message").html('');    
    jQuery("#mlp-bulk-apply").removeClass("disabled-button");
    jQuery('#library-help').show();
    jQuery('#playlist-help').hide();  
    
  });  
  
  jQuery(document).on("click", "#mlfp-reselect-folder", function () {
    window.bulk_move_status = true;    
    jQuery("#bulkmove-destination-folder").val(mgmlp_ajax.select_folder);      
    jQuery("#mlfp-bulk-move-files").addClass("disabled-button");   
    jQuery("#mlfp-bulk-move-files").attr('disabled','disabled');        
  });

  jQuery(document).on("click", "#mlfp-stop-file-move", function () {
    window.allow_bulk_move = false;
  });
  
  jQuery(document).on("click", "#mlfp-bulk-move-files", function (e) {
    
    e.stopImmediatePropagation();   
    
		jQuery("#folder-message").html(mgmlp_ajax.moving_files);    
    
    // promise array/counter will call refresh when done
    var promisesArray = [];
    var successCounter = 0;
    var promise;			
    
    console.log("mlfp-bulk-move-files click")
    
    jQuery("#mlfp-bulk-move-files").hide();
    jQuery("#mlfp-stop-file-move").show();    
    
    window.stop_bulk_move = false;
    
    jQuery("#ajaxloader").show();
    
    if(jQuery("#current-folder-id").val() === undefined) 
      var current_folder = parseInt(sessionStorage.getItem('folder_id'));
    else
      var current_folder = parseInt(jQuery('#current-folder-id').val());
    
    var grid_list_switch = jQuery('#grid-list-switch-view').val();
    grid_list_switch = (grid_list_switch == 'on')? true : false;            
            
    var file_count = 0;

    var destination_folder_id = parseInt(jQuery("#bulkmove-destination-folder-id").val());
    
    var destination_folder_path = jQuery("#bulkmove-destination-folder-path").val();
    
    var file_count = 0;
    
    console.log('folder ids: ', destination_folder_id, current_folder);
    if(destination_folder_id == current_folder) {
      window.bulk_move_status = true;
      window.allow_bulk_move = false;
      jQuery("#mlfp-bulk-move-files").show();
      jQuery("#mlfp-stop-file-move").hide();          
      jQuery("#ajaxloader").hide();      
      jQuery('#bulkmove-destination-folder').val(mgmlp_ajax.select_folder);      
      alert(mgmlp_ajax.source_destination_error);
      return false;
    }
    
    jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
      
      file_count++;
      
      if(window.stop_bulk_move == true) {
        //refresh_file_contents(current_folder);
        jQuery('#folder-tree').jstree('select_node', '#' + current_folder, true);        
        return false;
      }
                  
      var file_id = jQuery(this).attr("id");
      
      //mlfp_move_single_file(file_id, destination_folder_id, current_folder, destination_folder_path);
      
			promise = 
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mlfp_move_single_file", file_id: file_id, folder_id: destination_folder_id, current_folder: current_folder, destination_folder_path: destination_folder_path, nonce: mgmlp_ajax.nonce },
        url: mgmlp_ajax.ajaxurl,
        dataType: "html",
        success: function (data) { 
          jQuery("#folder-message").html(data);      
        },
        error: function (err){ 
          alert(err.responseText)
        }
      });  
  
      promise.done(function(msg) {
        successCounter++;
      });

      promise.fail(function(jqXHR) { /* error out... */ });

      promisesArray.push(promise);
      
    });
    
    jQuery.when.apply($, promisesArray).done(function() {
      jQuery("#mlfp-bulk-move-files").show();
      jQuery("#mlfp-stop-file-move").hide();          
      if(file_count < 1) {
        alert(mgmlp_ajax.nothing_selected);
        jQuery("#ajaxloader").hide();
        return false;      
      } else {
        jQuery("#bulk-move-area").slideUp(600);  
        jQuery("#mlp-bulk-apply").removeClass("disabled-button");    
        //refresh_file_contents(current_folder, grid_list_switch);
        jQuery('#folder-tree').jstree(true).deselect_all(true);
        jQuery('#folder-tree').jstree('select_node', '#' + current_folder, true);
        
      }      
    });
    
  });
    
  jQuery(document).on("click", "#mlfp-stop-file-move", function () {    
    window.stop_bulk_move = true;    
    jQuery("#mlfp-stop-file-move").hide();
    jQuery("#mlfp-bulk-move-files").show();    
		jQuery("#folder-message").html(mgmlp_ajax.copying_stopped);
		jQuery("#ajaxloader").hide();    
  });
    
  jQuery(document).on("click", "#audio-playlist, #video-playlist", function (e) {    
    console.log('playlist type');
    //jQuery('#pl_attachment_ids').text('');
    jQuery('#pl_attachment_ids').val('');
  });

  jQuery(document).on("click", ".edit-link", function (e) {
    e.stopImmediatePropagation();       
    console.log('click_to_edit_image',window.click_to_edit_image);
    
    var attachment_id = jQuery(this).attr("id");
    if(window.click_to_edit_image) {
      var new_tab = mgmlp_ajax.site_url + "/wp-admin/post.php?post=" + attachment_id + "&action=edit";
      console.log('new_tab',new_tab);
      newTab(new_tab);
    } else {
      console.log('attachment_id',attachment_id);
      
      var playlist_type = jQuery('input[name="list-type"]:checked').val();
            
      mime_type_test(attachment_id, playlist_type);
               
    }  
    
  });
  
  jQuery(document).on("click", "#generate-pl-shortcode", function (e) {
    var attachment_ids = jQuery("#pl_attachment_ids").val();
    
    if(attachment_ids.length < 1) {
      alert(mgmlp_ajax.no_ids_selected);
      return false;
    }
    
    var playlist_type = jQuery('input[name="list-type"]:checked').val();
    
    if(playlist_type == 'video')    
      var shortcode = '[playlist type="video" ids="' + attachment_ids + '"]';    
    else
      var shortcode = '[playlist ids="' + attachment_ids + '"]';    
    
    jQuery("#playlist-shortcode-container").val(shortcode);
    jQuery("#copy-pl-shortcode").removeAttr('disabled');    
    jQuery("#copy-pl-shortcode").removeClass('disabled-button');    
    
  });
  
  jQuery(document).on("click", "#copy-pl-shortcode", function (e) {
    
    var copy_text = document.getElementById("playlist-shortcode-container");

    copy_text.select();
    copy_text.setSelectionRange(0, 99999);

    document.execCommand("copy");    
    
    jQuery("#pl-copy-message").html(mgmlp_ajax.copy_message);
    
  });
  
  jQuery(document).on("click", ".edit-link", function (e) {
    console.log('click_to_edit_image', window.click_to_edit_image);
    
    var attachment_id = jQuery(this).attr("id");
    if(window.click_to_edit_image) {
      var new_tab = mgmlp_ajax.site_url + "/wp-admin/post.php?post=" + attachment_id + "&action=edit";
      console.log('new_tab',new_tab);
      newTab(new_tab);
    } else {
      console.log('attachment_id',attachment_id);
      
      var playlist_type = jQuery('input[name="list-type"]:checked').val();
            
      mime_type_test(attachment_id, playlist_type);
               
    }  
    
  });
    
  jQuery(document).on("click","#close-mlf-jp-gallery",function(){
    jQuery("#wp-gallery-area").slideUp(600);
    jQuery("#mlp-bulk-apply").removeClass("disabled-button");
    jQuery('#library-help').show();
    jQuery('#jp-gallery-help').hide();      
  });
    
  jQuery(document).on("click","#display-ir-instructions",function(){
    jQuery("#ir-instructions").slideDown(200);
    jQuery("#display-ir-instructions").hide();
    jQuery("#display-ir-instructions-close").show();
  });

  jQuery(document).on("click","#display-ir-instructions-close",function(){
    jQuery("#ir-instructions").slideUp(200);
    jQuery("#display-ir-instructions").show();
    jQuery("#display-ir-instructions-close").hide();
  });
    
  
  jQuery(document).on('click','#insert_mlp_pe_images',function(){
    var output = "";

    if (jQuery("input[type=checkbox].mgmlp-media:checked").length == 0) {
      alert("Nothing was selected. Check the images you want to include in a gallery and then click the Add button.");
    }	

    jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
      var attachment_id = jQuery(this).attr("id");
      var image_html = jQuery(this).closest('div.attachment-name').parent().html();
      if(image_html !== undefined) {
        output += '<li id="' + attachment_id +'">';
        output += image_html;
        output += '</li>';
      }

    });

    jQuery("#mgmlp-gallery-list").append(output);
    jQuery(".mgmlp-media").removeAttr('checked');  

  });

  jQuery(document).on('click','#remove_mlp_pe_images',function(){						
    jQuery('ul#mgmlp-gallery-list li div.attachment-name span.image_select input[type=checkbox].mgmlp-media:checked').each(function() {  
      jQuery(this).parents("li:first").remove();
    });						
  });

  jQuery(document).on('click','#generate-jp-shortcode',function(){
    //var id_list = new Array();
    var id_string = "";
    var first = true;
    var shortcode = "";

    jQuery("#jp-copy-message").html('');

    //ul#mgmlp-gallery-list li div.attachment-name span.image_select input.mgmlp-media						
    jQuery('ul#mgmlp-gallery-list li div.attachment-name span.image_select input.mgmlp-media').each(function() {  
      var nextid = jQuery(this).attr("id");
      //console.log("nextid "+ nextid);
      //id_list[id_list.length] = nextid;
      if(!first) {
        id_string += "," + nextid;
      } else {
        id_string += nextid;
      }	
      first = false;
    });
    var gallery_type = jQuery('#mgmlp-gal-type').val();
    var gallery_columns = jQuery('#mgmlp-gal-columns').val();
    var image_order = jQuery('#mgmlp-gal-order').val();
    var order_type = jQuery('#mgmlp-gal-order-type').val();
    var image_size = jQuery('#mgmlp-gal-size').val();
    var gallery_post_id = jQuery('#gallery_post_id').val();
    var slides_autl_start = jQuery('#slides-autl-start').val();

    shortcode = '[gallery ';

    if(gallery_type !== 'none')
      shortcode += 'type="' + gallery_type + '" ';

    if(gallery_columns !== 'none')
      shortcode += 'columns="' + gallery_columns +'" ';						

    if(order_type !== 'none')
      shortcode += 'orderby="' + order_type + ' ' + image_order + '" ';

    if(image_size !== 'none')
      shortcode += 'size="' + image_size + '" ';						

    if(gallery_post_id.length > 0)
      shortcode += 'id="'+ gallery_post_id +'" ';

    if(gallery_type === 'slideshow') {
      if(slides_autl_start !== 'none') {
        shortcode += 'autostart="' + slides_autl_start + '" ';
      }
    }
    //console.log('id_string ' + id_string);
    if(id_string.length > 0)
    shortcode += 'ids="' + id_string + '" ';

    shortcode += ']';
    //console.log('shortcode ' + shortcode);

    //var win = window.dialogArguments || opener || parent || top;
    //win.send_to_editor(shortcode);
    //console.log(shortcode);            
    jQuery('#jetpack-shortcode-container').val(shortcode);

    jQuery("#copy-jp-shortcode").removeAttr('disabled');    
    jQuery("#copy-jp-shortcode").removeClass('disabled-button');    

  });

  jQuery(document).on("click","#select_all_mlp_wp_gallery",function(){
    jQuery("ul.mg-media-list li div.attachment-name span.image_select input.mgmlp-media").prop("checked", !jQuery("ul.mg-media-list li div.attachment-name span.image_select input.mgmlp-media").prop("checked"));
  });

  jQuery(document).on("click","#clear_mlp_pe_images",function(){
    jQuery("ul#mgmlp-gallery-list").empty();
  });
  
  jQuery(document).on("click","#close-mlf-replace",function(){
    jQuery("#file-replace-area").slideUp(200);    
  });
  
  jQuery(document).on("click", "#mlpp-add-to-ng-gallery", function () {

    jQuery("#folder-message").html('');						
    var gallery_image_ids = new Array();
    jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
      gallery_image_ids[gallery_image_ids.length] = jQuery(this).attr("id");
    });
    if(gallery_image_ids.length > 0) {

      var serial_gallery_image_ids = JSON.stringify(gallery_image_ids.join());
      var gallery_id = jQuery('#ng-gallery-select').val();

      jQuery("#ajaxloader").show();

      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "mg_add_to_ng_gallery", gallery_id: gallery_id, serial_gallery_image_ids: serial_gallery_image_ids, nonce: mgmlp_ajax.nonce },
        url : mgmlp_ajax.ajaxurl,
        dataType: "html",
        success: function (data) {
          jQuery("#ajaxloader").hide();
          jQuery("#folder-message").html(data);
          jQuery(".mgmlp-media").prop('checked', false);
          jQuery(".mgmlp-folder").prop('checked', false);
        },
        error: function (err) { 
          jQuery("#ajaxloader").hide();
          alert(err.responseText);
        }
      });  
    } else {
      alert(mgmlp_ajax.no_images_selected);
    }

  });
  
  jQuery('#close-mlfp-embed').on('click', function (e) {
    e.stopImmediatePropagation();
    jQuery("#embed-area").slideUp(200);  
    jQuery("#copy-shortcode").attr('disabled','disabled');
    jQuery("#copy-shortcode").addClass('disabled-button');    
    jQuery("#embed-shortcode-container").val('');      
    jQuery("#copy-message").val('');
    jQuery("#embed-poster").val('');
    jQuery('#embed-autoplay').prop('checked', false);
    jQuery('#embed-controls').prop('checked', false);
    jQuery('#embed-loop').prop('checked', false);
    jQuery('#embed-muted').prop('checked', false);
    jQuery('select#embed-preload>option:eq(0)').prop('selected', true);
    jQuery(".media-attachment, .mgmlp-media").prop("checked", false);    
    jQuery("#mlp-bulk-apply").removeClass("disabled-button");
    jQuery('#library-help').show();
    jQuery('#embed-file-help').hide();
    
  });
  
  jQuery(document).on("click","#display-instructions",function(){
    jQuery("#embed-instructions").slideDown(200);
    jQuery("#display-instructions").hide();
    jQuery("#display-instructions-close").show();
  });

  jQuery(document).on("click","#display-instructions-close",function(){
    jQuery("#embed-instructions").slideUp(200);
    jQuery("#display-instructions").show();
    jQuery("#display-instructions-close").hide();
  });
  
  jQuery('#new-ng-gallery').on('click', function (e) {
    e.stopImmediatePropagation();
    jQuery('#new-ng-gallery-popup').fadeIn(300);        
  });
      
  jQuery('#close-new-ng-gallery-popup').on('click', function (e) {
    e.stopImmediatePropagation();
    jQuery('#new-ng-gallery-popup').fadeOut(300);    
  });
  
  jQuery(document).on("click", "#mgmlp-create-new-gallery", function (e) {
    e.stopImmediatePropagation();

    jQuery("#folder-message").html('');			

    var new_gallery_name = jQuery('#new-gallery-name').val();
    //var parent_folder = jQuery('#current-folder-id').val();

    if(jQuery("#current-folder-id").val() === undefined) 
      var parent_folder = sessionStorage.getItem('folder_id');
    else
      var parent_folder = jQuery('#current-folder-id').val();


    jQuery("#ajaxloader").show();

    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "mlpp_create_new_ng_gallery", new_gallery_name: new_gallery_name, parent_folder: parent_folder, nonce: mgmlp_ajax.nonce },
      url : mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) {
        jQuery("#ajaxloader").hide();          
        jQuery("#folder-message").html(data);
      },
      error: function (err)
        { alert(err.responseText);}
    });
           	
  });  
  
  jQuery(document).on("click", "#close-add-to-ng-gallery", function (e) {
    e.stopImmediatePropagation();
    jQuery("#add-to-gallery-area").slideUp(200);    
    jQuery("#mlp-bulk-apply").removeClass("disabled-button");       
  });  
  
  jQuery(document).on("click", ".mlfp-previous-cats, .mlfp-next-cats", function (e) {
    
    console.log("mlfp-next-cats");
    e.stopImmediatePropagation();
    
    if(jQuery(this).hasClass("disabled")) {
      return false;
    }
        
		jQuery("#ajaxloader").show(); 
    
    var action_function = 'mlp_load_categories_list';
    
		var cat_ids = new Array();
		jQuery('input[type=checkbox].mlf-cats:checked').each(function() {  
			cat_ids[cat_ids.length] = jQuery(this).attr("id");
		});
		
		if(cat_ids.length === 0) {
			alert(mgmlp_ajax.no_categories_selected);
			return false;
		}
    
    var grid_list_switch = jQuery('#grid-list-switch-view').val();
    grid_list_switch = (grid_list_switch == 'on') ? 'true' : 'false';    
    
    var mif_visible = (jQuery("#mgmlp-media-search-input").is(":visible")) ? false : true;						
    var serial_cat_ids = JSON.stringify(cat_ids.join());
    var page_id = jQuery(this).attr("data-page-id");    
    var image_link = jQuery(this).attr("image_link");
    
    if(grid_list_switch)
      action_function = 'mlp_load_categories';
           
    console.log("grid_list_switch",grid_list_switch);
    console.log('action_function',action_function)
    		
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: action_function, serial_cat_ids: serial_cat_ids, mif_visible: mif_visible, page_id: page_id , nonce: mgmlp_ajax.nonce },
      url: mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) 
      { 
        jQuery("#mgmlp-file-container").html(data); 
        jQuery("#folder-message").html(''); 				
        jQuery("#ajaxloader").hide(); 
      },
        error: function (err)
      { alert(err.responseText)}
    });
         
	});    
  
  jQuery(document).on("click", ".mlfp-first-cats", function (e) {
    
    console.log("mlfp-first-cats");
    e.stopImmediatePropagation();
        
    if(jQuery(this).hasClass("disabled")) {
      return false;
    }        

    jQuery("#ajaxloader").show();
    
    var action_function = 'mlp_load_categories_list';
        
		var cat_ids = new Array();
		jQuery('input[type=checkbox].mlf-cats:checked').each(function() {  
			cat_ids[cat_ids.length] = jQuery(this).attr("id");
		});
		
		if(cat_ids.length === 0) {
			alert(mgmlp_ajax.no_categories_selected);
			return false;
		}    

    if(jQuery("#current-folder-id").val() === undefined) 
      var current_folder_id = sessionStorage.getItem("folder_id");
    else
      var current_folder_id = jQuery("#current-folder-id").val();

    var page_type = jQuery(this).attr("data-type");
    var mif_visible = (jQuery("#mgmlp-media-search-input").is(":visible")) ? false : true;						
    var serial_cat_ids = JSON.stringify(cat_ids.join());    
    
    var grid_list_switch = jQuery('#grid-list-switch-view').val();
    grid_list_switch = (grid_list_switch == 'on') ? 'true' : 'false';
    
    if(grid_list_switch)
      action_function = 'mlp_load_categories';    
    
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: action_function, serial_cat_ids: serial_cat_ids, mif_visible: mif_visible, page_id: 0, nonce: mgmlp_ajax.nonce },
      url: mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) 
      { 
        jQuery("#mgmlp-file-container").html(data); 
        jQuery("#folder-message").html(''); 				
        jQuery("#ajaxloader").hide(); 
      },
        error: function (err)
      { alert(err.responseText)}
    });
            
	});    
  
  
  jQuery(document).on("click", ".mlfp-last-cats", function (e) {
    
    console.log("mlfp-last-cats");
    
    e.stopImmediatePropagation();
        
    if(jQuery(this).hasClass("disabled")) {
      return false;
    }

    jQuery("#ajaxloader").show();
    
    var action_function = 'mlp_load_categories_list';
    
		var cat_ids = new Array();
		jQuery('input[type=checkbox].mlf-cats:checked').each(function() {  
			cat_ids[cat_ids.length] = jQuery(this).attr("id");
		});
		
		if(cat_ids.length === 0) {
			alert(mgmlp_ajax.no_categories_selected);
			return false;
		}    

    if(jQuery("#current-folder-id").val() === undefined) 
      var current_folder_id = sessionStorage.getItem("folder_id");
    else
      var current_folder_id = jQuery("#current-folder-id").val();

    var page_type = jQuery(this).attr("data-type");
    var mif_visible = (jQuery("#mgmlp-media-search-input").is(":visible")) ? false : true;						
    var serial_cat_ids = JSON.stringify(cat_ids.join());           
    var last_page = jQuery(this).attr("data-last");
    
    var grid_list_switch = jQuery('#grid-list-switch-view').val();
    grid_list_switch = (grid_list_switch == 'on') ? 'true' : 'false';
    
    if(grid_list_switch)
      action_function = 'mlp_load_categories';        
    
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: action_function, serial_cat_ids: serial_cat_ids, mif_visible: mif_visible, page_id: last_page, nonce: mgmlp_ajax.nonce },
      url: mgmlp_ajax.ajaxurl,
      dataType: "html",
      success: function (data) 
      { 
        jQuery("#mgmlp-file-container").html(data); 
        jQuery("#folder-message").html(''); 				
        jQuery("#ajaxloader").hide(); 
      },
        error: function (err)
      { alert(err.responseText)}
    });
            
	});    
  
  jQuery(document).on("keydown", ".mlf-cat-page", function (e) {
  
    e.stopImmediatePropagation();
    console.log('keydown');
    if(e.keyCode == 13){
      
      jQuery("#ajaxloader").show();
      
      var cat_ids = new Array();
      jQuery('input[type=checkbox].mlf-cats:checked').each(function() {  
        cat_ids[cat_ids.length] = jQuery(this).attr("id");
      });
      
      var serial_cat_ids = JSON.stringify(cat_ids.join());                 
      var last_page = parseInt(jQuery("#mlfp-last-page").val()) - 1;      
      var grid_list_switch = jQuery('#grid-list-switch-view').val();
      grid_list_switch = (grid_list_switch == 'on') ? 'true' : 'false';      
      
      var new_page_id = parseInt(jQuery(this).val());
      console.log('new_page_id',new_page_id);
      var last_page = parseInt(jQuery(this).attr("data-last"));
      //var last_page = parseInt(jQuery("#mlfp-last-page").val());
      console.log('last_page',last_page);
      if(new_page_id > 0 && new_page_id < (last_page+1)) {
        jump_to_cat_page(serial_cat_ids, (new_page_id - 1), grid_list_switch);        
      } else {
        jQuery("#ajaxloader").hide();        
      }
    }  
  })    
  
    
});

function jump_to_page(current_folder_id, page_id, grid_list_switch) {

  jQuery.ajax({
    type: "POST",
    async: true,
    data: { action: "mlfp_get_next_attachments", current_folder_id: current_folder_id, page_id: page_id, image_link: '1', grid_list_switch: grid_list_switch, nonce: mgmlp_ajax.nonce },
    url: mgmlp_ajax.ajaxurl,
    dataType: "html",
    success: function (data) {
      jQuery("#ajaxloader").hide();    
      jQuery("#mgmlp-file-container").html(data);
    },
    error: function (err){
      jQuery("#ajaxloader").hide();
      alert(err.responseText);
    }
  });
}    

function jump_to_cat_page(serial_cat_ids, page_id, grid_list_switch) {
  
  var action_function = 'mlp_load_categories_list';
  
  var mif_visible = (jQuery("#mgmlp-media-search-input").is(":visible")) ? false : true;						
  var image_link = jQuery(this).attr("image_link");
  
  var grid_list_switch = jQuery('#grid-list-switch-view').val();
  grid_list_switch = (grid_list_switch == 'on') ? 'true' : 'false';

  if(grid_list_switch)
    action_function = 'mlp_load_categories';        
  
  jQuery.ajax({
    type: "POST",
    async: true,
    data: { action: action_function, serial_cat_ids: serial_cat_ids, mif_visible: mif_visible, page_id: page_id , nonce: mgmlp_ajax.nonce },
    url: mgmlp_ajax.ajaxurl,
    dataType: "html",
    success: function (data) 
    { 
      jQuery("#mgmlp-file-container").html(data); 
      jQuery("#folder-message").html(''); 				
      jQuery("#ajaxloader").hide(); 
    },
      error: function (err)
    { alert(err.responseText)}
  });  
  
}

function create_new_folder(new_folder_name) {

  jQuery("#folder-message").html('');			

  if(jQuery("#current-folder-id").val() === undefined) 
    var parent_folder = sessionStorage.getItem('folder_id');
  else
    var parent_folder = jQuery('#current-folder-id').val();

  new_folder_name = new_folder_name.trim();

  if(new_folder_name.indexOf(' ') >= 0) {
    alert(mgmlp_ajax.no_spaces);
    return false;
  }       

  if(new_folder_name.indexOf('"') >= 0) {
    alert(mgmlp_ajax.no_quotes);
    return false;
  } 

  if(new_folder_name.indexOf("'") >= 0) {
    alert(mgmlp_ajax.no_quotes);
    return false;
  } 

  if(new_folder_name == "") {
    alert(mgmlp_ajax.no_blank);
    return false;
  } 

  jQuery("#ajaxloader").show();

  jQuery.ajax({
    type: "POST",
    async: true,
    data: { action: "create_new_folder", parent_folder: parent_folder, new_folder_name: new_folder_name,   nonce: mgmlp_ajax.nonce },
    url : mgmlp_ajax.ajaxurl,
    dataType: "json",
    success: function (data) {
      jQuery("#folder-tree").addClass("bound").on("select_node.jstree", show_mlp_node);							
      jQuery('#new-folder-name').val('');	
      jQuery("#ajaxloader").hide();          
      jQuery("#folder-message").html(data.message);
      jQuery('#mlf-new-folder-popup').fadeOut(300);
      if(data.refresh) {
        jQuery('#folder-tree').jstree(true).settings.core.data = data.folders;						
        jQuery('#folder-tree').jstree(true).refresh();			
        jQuery('#folder-tree').jstree('select_node', '#' + parent_folder, true);
        jQuery('#folder-tree').jstree('toggle_expand', '#' + parent_folder, true );
      }

    },
    error: function (err)
      { alert(err.responseText);}
  });
        
}
  
function do_mlfp_search() {

  var search_value = jQuery('#mgmlp-media-search-input').val();

  search_value = search_value.trim();
  
  var grid_list_switch = jQuery('#grid-list-switch-view').val();  

  if(search_value.length < 1) {
    jQuery("#folder-message").html('<?php esc_html_e('The search text is empty.', 'maxgalleria-media-library' ); ?>');
    return false;
  } 
  jQuery("#folder-message").html('');

  if(grid_list_switch == 'on')
    var search_url = '<?php echo esc_url_raw(site_url() . '/wp-admin/admin.php?page=mlfp-search-library&display=grid&s=') ?>' + search_value;
  else
    var search_url = '<?php echo esc_url_raw(site_url() . '/wp-admin/admin.php?page=mlfp-search-library&display=list&s=') ?>' + search_value;

  window.location.href = search_url;    
        
}

function bulk_delete_files() {
  
  jQuery("#folder-message").html('');			

  if(jQuery("#current-folder-id").val() === undefined) 
    var current_folder = sessionStorage.getItem('folder_id');
  else
    var current_folder = jQuery('#current-folder-id').val();

  jQuery(".mgmlp-folder").prop('disabled', false);

  var delete_ids = new Array();
  jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
    delete_ids[delete_ids.length] = jQuery(this).attr("id");
  });

  if(delete_ids.length === 0) {
    alert(mgmlp_ajax.nothing_selected);
    return false;
  }
  
  if(confirm(mgmlp_ajax.confirm_file_delete)) {
    var serial_delete_ids = JSON.stringify(delete_ids.join());
    jQuery("#ajaxloader").show();
    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "delete_maxgalleria_media", serial_delete_ids: serial_delete_ids, parent_id: current_folder, nonce: mgmlp_ajax.nonce },
      //var delete_data = jQuery.serialize(data);
      url : mgmlp_ajax.ajaxurl,
      dataType: "json",
      success: function (data) {

        jQuery("#folder-message").html(data.message);
        if(data.refresh)
          jQuery("#mgmlp-file-container").html(data.files);						
        jQuery("#ajaxloader").hide();            

      },
      error: function (err)
        { alert(err.responseText);}
    });
  } 

  
}

function bulk_regenerate_thumbnails() {
  
      var image_ids = new Array();
      jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {   
        image_ids[image_ids.length] = jQuery(this).attr("id");
      });
			
			if(image_ids.length < 1) {
        jQuery("#folder-message").html("No files were selected.");
				return false;
			}	
			            
      var serial_image_ids = JSON.stringify(image_ids.join());
      
      console.log('serial_image_ids',serial_image_ids);
      
      jQuery("#ajaxloader").show();
      
      jQuery.ajax({
        type: "POST",
        async: true,
        data: { action: "regen_mlp_thumbnails", serial_image_ids: serial_image_ids, nonce: mgmlp_ajax.nonce },
        url : mgmlp_ajax.ajaxurl,
        dataType: "html",
        success: function (data) {
          console.log('data',data);
          jQuery(".mgmlp-media").prop('checked', false);
          jQuery("#folder-message").html(data);
          jQuery("#ajaxloader").hide();
        },
        error: function (err)
          { 
            jQuery("#ajaxloader").hide();
            alert(err.responseText);
          }
      });                
  
}

function prepare_bulk_move() {
  
//  var move_ids = new Array();
//  jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
//    move_ids[move_ids.length] = jQuery(this).attr("id");
//  });
//
//  if(move_ids.length === 0) {
//    alert(mgmlp_ajax.nothing_selected);
//    return false;
//  }
  
  slideonlyone('bulk-move-area');
  jQuery("#mlp-bulk-apply").addClass("disabled-button");      
  jQuery('#bulkmove-destination-folder').val(mgmlp_ajax.select_folder);
  window.bulk_move_status = true;
  jQuery('#library-help').hide();
  jQuery('#bulk-move-help').show();
  
}        

function prepare_play_list() {

  slideonlyone('playlist-area');
  window.click_to_edit_image = false;
  jQuery("a.media-attachment").css("cursor", "pointer");
  jQuery("#mlp-bulk-apply").addClass("disabled-button");  
  jQuery('#library-help').hide();
  jQuery('#playlist-help').show();  
  
}  

function mime_type_test(attachment_id, playlist_type) {
  
  //jQuery("#folder-message").html('');
  jQuery("#ajaxloader").show();
    
  jQuery.ajax({
    type: "POST",
    async: true,
    data: { action: "mlfp_mime_type_test", attachment_id: attachment_id, playlist_type: playlist_type, nonce: mgmlp_ajax.nonce },
    url: mgmlp_ajax.ajaxurl,
    dataType: "json",
    success: function (data) { 
      jQuery("#ajaxloader").hide();
      console.log(data);
      
      retval = data.type_status;
      if(data.type_status == false)
        alert(data.message);
      else {
        
        var attachment_ids = jQuery("#pl_attachment_ids").val();
        console.log('attachment_ids 1',attachment_ids);
        if(attachment_ids.trim().length == 0)
          attachment_ids = attachment_id;
        else
          attachment_ids = attachment_ids + ',' + attachment_id;
            
        jQuery("#pl_attachment_ids").val(attachment_ids);
        console.log('attachment_ids 2',attachment_ids);
        jQuery("#folder-message").html(data.message);        
      }
    },
    error: function (err){ 
      jQuery("#ajaxloader").hide();
      alert(err.responseText)
    }
  });  
}

function display_jp_gallery_area() {
  slideonlyone('wp-gallery-area');  
  jQuery("#mlp-bulk-apply").addClass("disabled-button");
  jQuery('#library-help').hide();
  jQuery('#jp-gallery-help').show();  
}

function display_embed_area() {
  
  var image_id = 0;
  jQuery("#embed-type-row").hide();   
  jQuery(".mlfp-video-options").hide();   
  jQuery(".mlfp-audio-options").hide();   
  jQuery(".mlfp-pdf-options").hide();   



  jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
    // only get the first one
      image_id = jQuery(this).attr("id");
  });

  //console.log(image_id);

  if(image_id > 0) {

    jQuery("#ajaxloader").show();

    jQuery.ajax({
      type: "POST",
      async: true,
      data: { action: "maxgalleria_get_file_url", image_id: image_id, nonce: mgmlp_ajax.nonce },
      url : mgmlp_ajax.ajaxurl,
      dataType: "json",
      success: function (data) {
        jQuery("#ajaxloader").hide();
        //console.log(data); 
        jQuery("#embed-file-url").val(data.url);
        jQuery("#embed-file-type").val(data.app_type);
        //if(data.app_type == 'pdf') {
        //  jQuery("#embed-type-row").show();            
        //}

        if(data.app_type == 'ogg') {
          jQuery("#ogg-options").show();
        } else {
          jQuery("#ogg-options").hide();              
        }           

        switch(data.app_type) {
          case 'pdf':
            jQuery("#embed-type-row").show();            
            jQuery(".mlfp-pdf-options").show();
            break;

          case 'mpeg':
          case 'mp3':
          case 'oga':
          case 'wav':  
            jQuery(".mlfp-audio-options").show();            
            break;

          case 'mp4':  
          case 'webm':  
          case 'ogg':  
          case 'ogv':  
            jQuery(".mlfp-video-options").show();            
            break;

          default:   
            jQuery("#folder-message").html(data.app_type + mgmlp_ajax.filetype_not_allowed);
            break;
        }
        jQuery("#mlp-bulk-apply").addClass("disabled-button");   
        jQuery('#library-help').hide();
        jQuery('#embed-file-help').show();
        slideonlyone('embed-area');
      },
      error: function (err) { 
        jQuery("#ajaxloader").hide();
        alert(err.responseText);
      }
    });                

  } else {
    alert(mgmlp_ajax.select_to_embed);
  } 
         
}

function display_nextgen_area() {
  slideonlyone('add-to-gallery-area');
  jQuery("#mlp-bulk-apply").addClass("disabled-button");
}

function rollbackp_scaled_images() {

  jQuery("#folder-message").html('<?php _e('Searching for scaled images...', 'maxgalleria-media-library' ) ?>');

  var current_folder_id = jQuery("#current-folder-id").val();

  var file_count = jQuery("#mlfp-file-count").val();

  getNextScaledImage(0, file_count, current_folder_id);

}


function getNextScaledImage(last_file, file_count, current_folder_id){
  console.log(last_file);

  jQuery.ajax({
    type: "POST",
    async: true,
    data: { action: "mlfp_get_next_ml_file", last_file: last_file, file_count: file_count, current_folder_id: current_folder_id, nonce: mgmlp_ajax.nonce },
    url: mgmlp_ajax.ajaxurl,
    dataType: "json",
    success: function (data) { 
      if(data != null && data.last_file != null) {
        console.log(data.percentage);
        jQuery("#folder-message").html(data.message);
        getNextScaledImage(data.last_file, file_count, current_folder_id);
      } else {
        jQuery("#folder-message").html(data.message);
        mlf_refresh(current_folder_id);
        return false;
      }	
    },
    error: function (err){ 
      alert(err.responseText)
    }
  });																							
}        

function upload_to_s3() {

  if(jQuery("#current-folder-id").val() === undefined) 
    var current_folder = sessionStorage.getItem('folder_id');
  else
    var current_folder = jQuery('#current-folder-id').val();

  jQuery('.input-area').each(function(index) {
    jQuery(this).slideUp(600);
  });

  var upload_ids = new Array();
  jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
    upload_ids[upload_ids.length] = jQuery(this).attr("id");
  });

  if(upload_ids.length === 0) {
    alert('No items were selected.');
    return false;
  }

  // save the array
  var serial_upload_ids = JSON.stringify(upload_ids.join());
  save_id_array(serial_upload_ids);

  if(confirm("Are you sure you want to upload the selected files?")) {

    jQuery("#ajaxloader").show();
    jQuery("#folder-message").html('Starting upload');
    run_selective_upload();

  }
  
}

function run_selective_upload() {

	jQuery.ajax({
		type: "POST",
		async: true,
		data: { action: "mlfp_selective_upload", nonce: mgmlp_ajax.nonce },
		url: mgmlp_ajax.ajaxurl,
		dataType: "json",
		success: function (data) { 
			console.log('data '+ data);
			//if(data.continue) {
			if(data != null) {
			  jQuery("#folder-message").html(data.message);
				if(jQuery("#current-folder-id").val() === undefined) 
					var current_folder = sessionStorage.getItem('folder_id');
				else
					var current_folder = jQuery('#current-folder-id').val();				
				mlf_refresh_folders(current_folder);
			}	
			if(data != null && data.continue != false) {	
				run_selective_upload();
			} else {
		    jQuery("#ajaxloader").hide();
				return false;
			}	
		},
		error: function (err){ 
		  jQuery("#ajaxloader").hide();
			alert(err.responseText)
		}
	});																											
	
}

function download_from_s3() {
  
  console.log('download_from_s3');
  
  if(jQuery("#current-folder-id").val() === undefined) 
    var current_folder = sessionStorage.getItem('folder_id');
  else
    var current_folder = jQuery('#current-folder-id').val();

  jQuery('.input-area').each(function(index) {
    jQuery(this).slideUp(600);
  });

  var download_ids = new Array();
  jQuery('input[type=checkbox].mgmlp-media:checked').each(function() {  
    download_ids[download_ids.length] = jQuery(this).attr("id");
  });

  if(download_ids.length === 0) {
    alert('No items were selected.');
    return false;
  }

  // save the array
  var serial_download_ids = JSON.stringify(download_ids.join());
  save_id_array(serial_download_ids);

  if(confirm("Are you sure you want to download the selected files?")) {

    jQuery("#ajaxloader").show();
    jQuery("#folder-message").html('Starting download');
    run_selective_download();

  }
  
}

function run_selective_download() {
	
	jQuery.ajax({
		type: "POST",
		async: true,
		data: { action: "mlfp_selective_download", nonce: mgmlp_ajax.nonce },
		url: mgmlp_ajax.ajaxurl,
		dataType: "json",
		success: function (data) { 
			console.log('data '+ data);
			//if(data.continue) {
			if(data != null) {
			  jQuery("#folder-message").html(data.message);
				if(jQuery("#current-folder-id").val() === undefined) 
					var current_folder = sessionStorage.getItem('folder_id');
				else
					var current_folder = jQuery('#current-folder-id').val();				
				mlf_refresh_folders(current_folder);
			}	
			if(data != null && data.continue != false) {	
				run_selective_download();
			} else {
		    jQuery("#ajaxloader").hide();
				return false;
			}	
		},
		error: function (err){ 
		  jQuery("#ajaxloader").hide();
			alert(err.responseText)
		}
	});																											
	
}

function save_id_array(serial_ids_array) {
	
	jQuery.ajax({
		type: "POST",
		async: true,
		data: { action: "mlfp_save_id_array", serial_ids_array: serial_ids_array, nonce: mgmlp_ajax.nonce },
		url: mgmlp_ajax.ajaxurl,
		dataType: "html",
		success: function (data) { 
		},
		error: function (err){ 
			alert(err.responseText)
		}
  });
	
}

function mlf_refresh_folders(folder_id, show) {
  //jQuery("#folder-message").html('Refreshing folders...');
	
	if(jQuery("#current-folder-id").val() === undefined) 
		var folder_id = sessionStorage.getItem('folder_id');
	else
		var folder_id = jQuery('#current-folder-id').val();
	
	jQuery.ajax({
		type: "POST",
		async: true,
		//data: { action: "display_folder_nav_ajax", folder: folder_id, nonce: mgmlp_ajax.nonce },
		data: { action: "mlp_get_folder_data", current_folder_id: folder_id, nonce: mgmlp_ajax.nonce },
		url: mgmlp_ajax.ajaxurl,
		dataType: "json",
		success: function (data) { 
			jQuery('#folder-tree').jstree(true).settings.core.data = data;
			jQuery('#folder-tree').jstree(true).refresh();			
			//jQuery('#folder-tree').jstree(true).redraw(true);
			
      //jQuery("#folder-message").html('');
		},
		error: function (err){ 
			alert(err.responseText)
		}
	});
	
}

//function get_upload_extension(filename) {
//  return filename.substring(filename.lastIndexOf('.')+1, filename.length) || filename;
//}
//
//function handleFileReplace(files,obj) {
//  
//  var folder_id = jQuery('#folder_id').val();
//  var replace_file_id = jQuery('#replace-file-id').val();
//  //var replace_file_url = jQuery('#replace-file-url').val();
//  var replace_mine_type = jQuery('#replace-mine-type').val();  
//  var replace_type = jQuery('input[name="replace-type"]:checked').val();
//  //var replace_type = 'replace-only';
//  var date_options = jQuery('input[name="date-options"]:checked').val();      												
//  var mlp_title_text = jQuery('#replace-seo-file-title').val();      
//  var mlp_alt_text = jQuery('#replace-seo-alt-text').val();      
//  var custom_date = jQuery('#mlfp-custom-date').val();
//  var replace_ext = jQuery('#replace-ext').val();        
//  
//  var upload_ext = get_upload_extension(files[0].name);
//  
//  if(upload_ext != replace_ext) {
//    alert(mgmlp_ajax.mime_mismatch);    
//    return false;
//  }
//  
//  jQuery("#ajaxloader").show();
//    
//  //for (var i = 0; i < files.length; i++) {
//    var fd = new FormData();
//    fd.append('file', files[0]);
//    fd.append('action', 'mlfp_replace_attachment');
//    fd.append('folder_id', folder_id);      
//    fd.append('replace_file_id', replace_file_id);
//    fd.append('replace_type', replace_type);
//    fd.append('date_options', date_options);      
//    fd.append('custom_date', custom_date);
//    //fd.append('replace_file_url', replace_file_url);
//    fd.append('replace_mine_type', replace_mine_type);
//    fd.append('title_text', mlp_title_text);
//    fd.append('alt_text', mlp_alt_text);      
//    fd.append('nonce', mgmlp_ajax.nonce);
//
//    var status = new createStatusbar(obj); //Using this we can set progress.
//    status.setFileNameSize(files[0].name,files[0].size);
//    jQuery('#file-replace-area').slideUp(600);
//    jQuery("#ir-instructions").slideUp(200);
//    jQuery("#display-ir-instructions-close").hide();    
//    jQuery('#mlfp-custom-date').val("");
//    jQuery('#replace-only').prop('checked',true);
//    jQuery('#mlfp-keep-date').prop('checked',true);
//    sendFileToServer(fd,status);
//    //break; // only allow one file upload
//
//  //}
//}


</script>   
