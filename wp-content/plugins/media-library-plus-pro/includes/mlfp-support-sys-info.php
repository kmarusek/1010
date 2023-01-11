<?php
  $theme = wp_get_theme();
  $browser = $this->get_browser();		
?>

<div id="support-info">
  <h4><?php _e('You may be asked to provide the information below to help troubleshoot your issue.', 'maxgalleria-media-library') ?></h4>
  <textarea class="system-info" readonly="readonly" wrap="off">
    ----- Begin System Info -----

    WordPress Version:      <?php echo get_bloginfo('version') . "\n"; ?>
    PHP Version:            <?php echo PHP_VERSION . "\n"; ?>
    PHP OS:                 <?php echo PHP_OS . "\n"; ?>
    MySQL Version:          <?php 
                                global $wpdb;
                                $mysql_version = $wpdb->db_version();

                                echo $mysql_version . "\n"; 
    ?>
    Web Server:             <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?>

    WordPress URL:          <?php echo get_bloginfo('wpurl') . "\n"; ?>
    Home URL:               <?php echo get_bloginfo('url') . "\n"; ?>
    WP-contents folder:     <?php echo WP_CONTENT_DIR . "\n\n";  ?>
    <?php 
      $upload_dir = wp_upload_dir();          
    ?>
Uploads Path:           <?php echo $upload_dir['path'] . "\n"; ?>
    Uploads URL:            <?php echo $upload_dir['url'] . "\n"; ?>
    Uploads Sub Directory:  <?php echo $upload_dir['subdir'] . "\n"; ?>
    Uploads Base Directory: <?php echo $upload_dir['basedir'] . "\n"; ?>
    Uploads Base URL:       <?php echo $upload_dir['baseurl'] . "\n"; ?>

    PHP cURL Support:       <?php echo (function_exists('curl_init')) ? 'Yes' . "\n" : 'No' . "\n"; ?>
    PHP GD Support:         <?php echo (function_exists('gd_info')) ? 'Yes' . "\n" : 'No' . "\n"; ?>
    PHP Memory Limit:       <?php echo ini_get('memory_limit') . "\n"; ?>
    PHP Post Max Size:      <?php echo ini_get('post_max_size') . "\n"; ?>
    PHP Upload Max Size:    <?php echo ini_get('upload_max_filesize') . "\n"; ?>

    WP_DEBUG:               <?php echo defined('WP_DEBUG') ? WP_DEBUG ? 'Enabled' . "\n" : 'Disabled' . "\n" : 'Not set' . "\n" ?>
    Multi-Site Active:      <?php echo is_multisite() ? 'Yes' . "\n" : 'No' . "\n" ?>

    Operating System:       <?php echo $browser['platform'] . "\n"; ?>
    Browser:                <?php echo $browser['name'] . ' ' . $browser['version'] . "\n"; ?>
    User Agent:             <?php echo $browser['user_agent'] . "\n"; ?>

    Active Theme:
    - <?php echo $theme->get('Name') ?> <?php echo $theme->get('Version') . "\n"; ?>
      <?php echo $theme->get('ThemeURI') . "\n"; ?>

    Active Plugins:
    <?php
    $plugins = get_plugins();
    $active_plugins = get_option('active_plugins', array());

    foreach ($plugins as $plugin_path => $plugin) {

      // Only show active plugins
      if (in_array($plugin_path, $active_plugins)) {
        echo '- ' . $plugin['Name'] . ' ' . $plugin['Version'] . "\n";

        if (isset($plugin['PluginURI'])) {
          echo '  ' . $plugin['PluginURI'] . "\n";
        }

        echo "\n";
      }
    }
    ?>
    ----- End System Info -----
  </textarea>

</div>						
