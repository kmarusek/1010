<?php
/**
 * Plugin Name: open positions
 * Plugin URI: https://www.1010data.com/
 * Description: Plugin to submit job applications to GreenHouse HR board.
 * Version: 0.1
 * Author: smitrevski
 * Author URI: https://www.your-site.com/
 **/
add_action( 'wp_enqueue_scripts', 'so_enqueue_scripts' );
function so_enqueue_scripts(){
    wp_register_script(
        'ajaxHandle',
        plugins_url('js/open-positions.js', __FILE__),
        array(),
        false,
        true
    );
    wp_enqueue_script( 'ajaxHandle' );
    wp_localize_script(
        'ajaxHandle',
        'my_ajax_object',
        array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
    );
}
function open_positions_ajax_call(){

    //$url = $_POST['url'];
    var_dump($_POST);
    var_dump($_FILES);
    // File name
    $file_name = $_FILES['file']['name'];
    $tmp_name = $_FILES['file']['tmp_name'];
    $type = $_FILES['file']['type'];
// File extension
    $file_type = pathinfo($file_name, PATHINFO_EXTENSION);
    $postfields = $_POST;
    $url = $postfields['url'];
    unset($postfields['action']);
    unset($postfields['url']);
    $curl = new \CURLFile($file_name, $type, $file_name);
    $headers = array("Content-Type:multipart/form-data", "Authorization: Basic ". base64_encode('62a32faac59b9475d0124ffe62935f7c-1')); // cURL headers for file uploading
    //$postfields['resume'] = curl_file_create($postfields['resume']);
    var_dump($postfields);
    $ch = curl_init();
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_HEADER => true,
        CURLOPT_POST => 1,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $postfields, ['resume' => $curl],
        //CURLOPT_INFILESIZE => $filesize,
        CURLOPT_RETURNTRANSFER => true
    ); // cURL options
    curl_setopt_array($ch, $options);
    curl_exec($ch);
    if(!curl_errno($ch))
    {
        $info = curl_getinfo($ch);
        if ($info['http_code'] == 200)
            $errmsg = "File uploaded successfully";
    }
    else
    {
        $errmsg = curl_error($ch);
        echo $errmsg;
    }
    $response = curl_exec($ch);
    echo $errmsg;
    $info = curl_getinfo($ch);
echo "code: ${info['http_code']}";

print_r($info['request_header']);

    var_dump($response);
    $err = curl_error($ch);

    echo "error";
    var_dump(curl_errno($ch));
    var_dump($err);
    curl_close($ch);
    wp_die();
}
add_action('wp_ajax_open_positions_ajax_call', 'open_positions_ajax_call');  // for logged in users only
add_action('wp_ajax_nopriv_open_positions_ajax_call', 'open_positions_ajax_call'); // for ALL users