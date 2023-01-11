              
  <h4><?php esc_html_e('Activating Media Library Folders Pro causes a Fatal Error', 'maxgalleria-media-library' ); ?></h4>

  <p><?php esc_html_e('This happens because the free version of Media Library Folders is running on the site. First deactivate Media Library Folders and then activate Media Library Folders Pro. With the Pro version installed, you no longer need the free version. It can be deleted from the site.', 'maxgalleria-media-library' ); ?></p>

  <h4><?php esc_html_e('Folder Tree Not Loading', 'maxgalleria-media-library' ); ?></h4>

  <p><?php esc_html_e('Usually a Java Script error is displayed when there is a problem loading the folder tree. If this is not the case, then try a different browser, such as Chrome, as some browsers cannot handle a large number of folders in the folder tree. When there is a Java Script error, users who report this issue can usually fix it by running the Media Library Folders Pro Reset plugin that comes with Media Library Folders Pro.', 'maxgalleria-media-library' ); ?></p>

  <ul>
    <li><?php esc_html_e('1. Deactivate Media Library Folders Pro and activate Media Library Folders Pro Reset. Open the Media Library Folders Data Reset page and click the Reset Folder Data button.', 'maxgalleria-media-library' ); ?></li>
    <li><?php esc_html_e('2. After the process has finished, reactivate Media Library Folders Pro. This is a necessary step. It will do a fresh scan of your media library database and no changes will be made to the files or folders on your site.', 'maxgalleria-media-library' ); ?></li>
  </ul>

  <p><?php esc_html_e('Note that resetting the folder data is not a cure for all Media Library Folders problems; it is specifically used when the folder tree does not load.', 'maxgalleria-media-library' ); ?></p>

  <h4><?php esc_html_e('Unable to Update Media Library Folders Pro', 'maxgalleria-media-library' ); ?></h4>
  <p><?php esc_html_e('Often when an update fails it is due to having your license key registered on a different site than on the one where the plugin is currently running. If you activated the license key on a development site and then moved the database and files to a production site, then you are likely to get an error message ‘unauthorized’. You may also receive this message if you have you Wordpress files are in a different folder than the main folder of the site.', 'maxgalleria-media-library' ); ?></p>
  <p><?php esc_html_e('To fix this one need to deactivate the license at the development site and then reactivate it at the current site. If you have configured your site with your Wordpress files in a different location, contact us at support and let us know the actual URL of the site and we can adjust the license URL.', 'maxgalleria-media-library' ); ?></p>
  <p><?php esc_html_e('If the above remedy does not solve the problem, the plugin can be manually updated by going to your account page and download the plugin. You will find it under its original name, Media Library Plus Pro. If you want to upload it by FTP, unzip the file and overwrite the existing folder in the plugins directory. If you want to upload it through the WordPress Plugins Add New page, you first have to deactivate and delete the old plugin and then upload the zip file. This does not affect any files and folders added to the site through Media Library Folders Pro.', 'maxgalleria-media-library' ); ?></p>

  <h4><?php esc_html_e('How to Unhide a Hidden Folder', 'maxgalleria-media-library' ); ?></h4>

  <ul>
    <li><?php esc_html_e('Go to the hidden folder via your cPanel or FTP and remove the file ‘mlpp-hidden’.', 'maxgalleria-media-library' ); ?></li>
    <li><?php esc_html_e('In the Media Library Folders Menu, click the Check for New folders link. This will add the folder back into Media Library Folders', 'maxgalleria-media-library' ); ?></li>
    <li><?php esc_html_e('Visit the unhidden folder in Media Library Folders and click the Sync button to add contents of the folder. Before doing this, check to see that there are no thumbnail images in the current folder since these will be regenerated automatically; these usually have file names such as image-name-150×150.jpg, etc.', 'maxgalleria-media-library' ); ?></li>
    <li><?php esc_html_e('Repeat step 3 for each sub folder.', 'maxgalleria-media-library' ); ?></li>
  </ul>

  <h4><?php esc_html_e('How to Delete a Folder?', 'maxgalleria-media-library' ); ?></h4>

  <p><?php esc_html_e('To delete a folder, right click (Ctrl-click with Macs) on a folder. A popup menu will appear with the options, ‘Delete this folder?’ and ‘Hide this folder?’. Click the delete option. The folder has to be empty in order to delete it. If you receive a message that the folder is not empty, use the sync function to display files that are still present in the folder.', 'maxgalleria-media-library' ); ?></p`>
  <p><?php esc_html_e('In some cases if that the folder is empty and Media Library Folders is unable to delete it, delete the folder from the server either by the site’s file manager or by FTP and then delete it in Media Library Folders.', 'maxgalleria-media-library' ); ?></p>

  <h4><?php esc_html_e('Activation Error After Update', 'maxgalleria-media-library' ); ?></h4>

  <p><?php esc_html_e('This issue is usually accompanied with a messaged that the plugin file is empty.', 'maxgalleria-media-library' ); ?></p>

  <p><?php esc_html_e('The solution would be to manually install the update:', 'maxgalleria-media-library' ); ?></p>

  <ul>
    <li><?php esc_html_e('Delete your current version of Media Library Folders Pro. This does not affect the files or the media library on your site.', 'maxgalleria-media-library' ); ?></li>
    <li><?php esc_html_e('Download the new version of Media Library Folders Pro from your account page at MaxGalleria.com.', 'maxgalleria-media-library' ); ?></li>
    <li><?php esc_html_e('Upload the plugin from your site’s Plugins->Add New page. Click the Upload Plugin button at the top of the page. Select the Zip file you downloaded.', 'maxgalleria-media-library' ); ?></li>
    <li><?php esc_html_e('Activate the uploaded plugin.', 'maxgalleria-media-library' ); ?></li>
  </ul>

  <h4><?php esc_html_e('Folders and images added to the site by FTP are not showing up in Media Library Folders', 'maxgalleria-media-library' ); ?></h4>

  <p><?php esc_html_e('Media Library Folders does not work like the file manager on your computer. It only display images and folders that have been added to the Media Library database. To display new folders that have not been added through the Media Library Folders you can click the Check for new folders option in the Media Library Folders submenu in the WordPress Dashboard. If you allow WordPress to store images by year and month folders, then you should click the option once each month to add these auto-generated folders.', 'maxgalleria-media-library' ); ?></p>

  <p><?php esc_html_e('To add images that were upload to the site via the cPanel or by FTP, navigate to the folder containing the images in Media Library Folders and click the Sync button. This will scan the folder and any sub folders it contains looking images not currently found in the Media Library for that folder.', 'maxgalleria-media-library' ); ?></p>

  <h4><?php esc_html_e('Unable to Update Media Library Folders Reset', 'maxgalleria-media-library' ); ?></h4>

  <p><?php esc_html_e('Media Library Folders Reset is maintenance and diagnostic plugin that is included with Media Library Folders. It automatically updates when Media Library Folders is updated. There is no need to updated it separately. Users should leave the reset plugin deactivated until it is needed in order to avoid accidentally deleting your site’s folder data.', 'maxgalleria-media-library' ); ?></p>

  <h4><?php esc_html_e('Files not showing up in the the Wordpress media library or media popup', 'maxgalleria-media-library' ); ?></h4>

  <p><?php esc_html_e('Some plugins and themes use a custom media popup which is not compatible with Media Library Folders Pro integration with Wordpress. When this happens, the media library will not load the contents of a folder when a new folder is selected. In this case, the display of the folder tree can be turned off by checking the option, ‘Remove folder tree from Media page & popups’ in Media Library Folders Pro settings. This will allow you to use the functionality of the custom popup without having to deactivate Media Library Folders Pro.', 'maxgalleria-media-library' ); ?></p>

  <h4><?php esc_html_e('Difficulties Uploading or Dragging and Dropping a Large Number of Files', 'maxgalleria-media-library' ); ?></h4>

  <p><?php esc_html_e('There is a limit to the number of files that can be uploaded with dragged and dropped at one time into media library folders. You can either reduce the number of files that your are trying to simultaneously upload or you can try uploading to a folder through the Wordpress Media page.', 'maxgalleria-media-library' ); ?></p>

  <h4><?php esc_html_e('How to Upload Multiple Files', 'maxgalleria-media-library' ); ?></h4>
  <p><?php esc_html_e('Users can upload multiple files by using drag and drop. When the Add Files button is click it revels the file upload area either single or multiple files can be highlight can be dragged from you computer’s file manager and dropped into the file uploads areas.', 'maxgalleria-media-library' ); ?></p>

  <h4><?php esc_html_e('Cannot Rename or Move a Folder', 'maxgalleria-media-library' ); ?></h4>
  <p><?php esc_html_e('Because most images and files in the media library have corresponding links embedded in the site’s posts and pages, Media Library Folders Pro does not allow folders to be renamed or moved in order to prevent breaking these links. Rather, to rename or move a folder, one needs to create a new folder and move the files from the old folder to the new. During the move process, Media Library Folders Pro will scan the site\'s standard posts and pages for any links matching the old address of the images or files and update them to the new address.', 'maxgalleria-media-library' ); ?></p>

  <h4><?php esc_html_e('License Expired Message Still Displayed After Renewal', 'maxgalleria-media-library' ); ?></h4>
  <p><?php esc_html_e('After renewing your license, please visit the Media Library Folders Pro Settings page. This action should update your license information and after viewing the page, the expiration message will not longer be displayed.', 'maxgalleria-media-library' ); ?></p>
  
  <h4><?php esc_html_e('Fatal error: Maximum execution time exceeded ', 'maxgalleria-media-library' ); ?></h4>                
  <p><?php esc_html_e('The Maximum execution time error takes place when moving, syncing or uploading too many files at one time. The web site’s server has a setting for how long it can be busy with a task. Depending on your server, size of files and the transmission speed of your internet, you may need to reduce the number of files you upload or move at one time.', 'maxgalleria-media-library' ); ?></p>
  <p><?php esc_html_e('It is possible to change the maximum execution time either with a plugin such as ', 'maxgalleria-media-library' ); ?><a href=“http://wordpress.org/plugins/wp-maximum-execution-time-exceeded/” target=“_blank”>WP Maximum Execution Time Exceeded</a><?php esc_html_e(' or by editing your site’s .htaccess file and adding this line:', 'maxgalleria-media-library' ); ?></p>
  <p><?php esc_html_e('php_value max_execution_time 300', 'maxgalleria-media-library' ); ?></p>
  <p><?php esc_html_e('Which will raise the maximum execution time to five minutes.', 'maxgalleria-media-library' ); ?></p>

<?php