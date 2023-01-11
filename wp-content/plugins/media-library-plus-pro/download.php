<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

ignore_user_abort(true);
set_time_limit(0); 

$folder_input = false;
$path_input = false;  
$uploads_input = false;  
$folder = "";
$path = "";
$uploads_folder_name = "";

if(isset($_GET['folder'])) {
  $folder = filter_input (INPUT_GET, 'folder', FILTER_SANITIZE_STRING);  
  $folder_input = true;  
}

if(isset($_GET['path'])) {
  $path = filter_input (INPUT_GET, 'path', FILTER_SANITIZE_STRING);
  $path_input = true;  
}

if(isset($_GET['uploads'])) {
  $uploads_folder_name = filter_input (INPUT_GET, 'uploads', FILTER_SANITIZE_STRING);
  $uploads_input = true;  
  $uploads_folder_name_length = strlen($uploads_folder_name);
}

//echo "folder_input $folder_input $folder  <br>";
//echo "path_input $path_input $path<br>";
//echo "uploads_input $uploads_input $uploads_folder_name<br>";

if($folder_input && $path_input && $uploads_input) {
  
  $fullPath = $path  . DIRECTORY_SEPARATOR . $folder;
  $zip_to_download = $fullPath . ".zip";
  
  if(file_exists($fullPath)) {
  
    //echo $fullPath . "<br>";
    //echo $zip_to_download . "<br>";

    $zip = new ZipArchive;
    if ($zip->open($zip_to_download, ZipArchive::CREATE) === TRUE) {

      $download_files = array('folders.json','mlfp-data.csv','mlfp-data.zip');

      foreach($download_files as $download_file) {

        $file_to_zip = $fullPath . DIRECTORY_SEPARATOR . $download_file;
        //echo $file_to_zip . "<br>";

        $upload_position = strpos($zip_to_download, $uploads_folder_name);

        $relative_path = substr($fullPath, $upload_position + $uploads_folder_name_length);

        // are we on windows?
        if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' || strtoupper(substr(PHP_OS, 0, 13)) == 'MICROSOFT-IIS' ) {
          $file_to_zip = str_replace('/', '\\', $file_to_zip);
        }

        $zip->addFile($file_to_zip, $download_file);

      }        
      $zip->close();
    }      

    ob_clean();

    $fp = @fopen($zip_to_download, 'rb'); 
    //echo "file open";
    $fsize = filesize($zip_to_download);
    $path_parts = pathinfo($zip_to_download);

    if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
      header('Content-Type: "$content_type"');
      header('Content-Disposition: attachment; filename="'.$path_parts["basename"].'"');
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header("Content-Transfer-Encoding: binary");
      header('Pragma: public');
      header("Content-Length: ".filesize(trim($zip_to_download)));
    } else {
      header('Content-Type: "$content_type"');
      header('Content-Disposition: attachment; filename="'.$path_parts["basename"].'"');
      header("Content-Transfer-Encoding: binary");
      header('Expires: 0');
      header('Pragma: no-cache');
      header("Content-Length: ".filesize(trim($zip_to_download)));
    }
    ob_end_clean(); 
    fpassthru($fp);
    fclose($fp);

    unlink($zip_to_download);
    
    header('Location: ' . $_SERVER['HTTP_REFERER']);
        
  } else {
    echo "Backup folder not found. Click the back button to go back to the import/export page.";
  }  
} else {
  echo "Not a valid folder name. Create a backup with a different name. Click the back button to go back to the import/export page.";  
}

exit;