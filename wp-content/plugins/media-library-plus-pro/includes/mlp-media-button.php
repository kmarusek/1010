<?php
global $pagenow, $current_screen;
$ajax_nonce = wp_create_nonce( "media-send-to-editor" );				
  
?>

<?php // Only run in post/page creation and edit screens ?>
<?php if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php')) && $current_screen->post_type != 'acf-field-group' ) { ?>
  <?php if($this->disable_media_ft != 'on') { ?>
<script>
  //set_uploads_folder();
  jQuery(document).ready(function(){
    jQuery.extend( wp.Uploader.prototype, {
      init : function(){
        mlfp_display_folder_tree();
      },
      success : function(){
        wp.media.frame.content.get().collection.props.set({ignore: (+ new Date())});  
      },
      refresh : function(){
        new_folder_check();
        mlfp_display_folder_tree();
      }      
    });          

  });                  
          
  </script>
<?php }
  }