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
function open_positions_ajax_call()
{

    //$url = $_POST['url'];
    //var_dump($_POST);
    //var_dump($_FILES);
    // File name
    $errors = [];
    $postfields = $_POST;
    if (isset($postfields['first_name'])) {
        if (empty($postfields['first_name'])) {
            $errors['first_name'] = 'First name is required field.';
        } else {
            if (preg_match('https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)
', $postfields['first_name'])) {
                $errors['first_name'] = 'First name must not contain an URL.';
            }
        }
    } else {
        $errors['first_name'] = 'First name is required field.';
    }
    if (isset($postfields['last_name'])) {
        if (empty($postfields['last_name'])) {
            $errors['last_name'] = 'Last name is required field.';
        } else {
            if (preg_match('https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)
', $postfields['last_name'])) {
                $errors['last_name'] = 'Last name must not contain an URL.';
            }
        }
    } else {
        $errors['last_name'] = 'Last name is required field.';
    }
    if (isset($postfields['phone'])) {
        if (!empty($postfields['phone'])) {
            if (preg_match('https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)
', $postfields['phone'])) {
                $errors['phone'] = 'Phone must not contain an URL.';
            }
        }
    }
    if (isset($postfields['email'])) {
        if (empty($postfields['email'])) {
            $errors['email'] = 'Email is required field.';
        } else {
            $email = filter_var($postfields['email'], FILTER_VALIDATE_EMAIL);
            if ($email === false) {
                $errors['email'] = 'Please enter valid email address.';
            }
        }
    } else {
        $errors['email'] = 'Email is required field.';
    }
    $url = $postfields['url'];
    unset($postfields['action']);
    unset($postfields['url']);
    $file_names = $_FILES['file']['name'];
    for ($i = 0; $i < count($file_names); $i++) {
        $file_name = $file_names[$i];
        $tmp_name = $_FILES['file']['tmp_name'][$i];
        $type = $_FILES['file']['type'][$i];
        $filesize = $_FILES['file']['size'][$i];
        if ($filesize <= 0) {
            $errors['file_size'] = 'File size must be > 0 bytes';
        }
        $allowed = array('pdf', 'doc', 'docx', 'txt', 'rtf');
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        if (!in_array($ext, $allowed)) {
            $errors['file_type'] = 'Allowed types are pdf, doc, docx, txt and rtf';
        }
        if ($i == 0) {
            if (function_exists('curl_file_create')) {
                $postfields['resume'] = curl_file_create($tmp_name, $type, $file_name);
            } else {
                $postfields['resume'] = '@' . $tmp_name . '/' . $file_name;
            }
        } else if ($i == 1) {
            if (function_exists('curl_file_create')) {
                $postfields['cover_letter'] = curl_file_create($tmp_name, $type, $file_name);
            } else {
                $postfields['cover_letter'] = '@' . $tmp_name . '/' . $file_name;
            }
        }
    }
    if (count($errors) == 0) {
        $headers = array("Content-Type:multipart/form-data", "Authorization: Basic " . base64_encode('62a32faac59b9475d0124ffe62935f7c-1')); // cURL headers for file uploading
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => true,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_INFILESIZE => $filesize,
            CURLOPT_RETURNTRANSFER => true
        ); // cURL options
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        if (!curl_errno($ch)) {
            $info = curl_getinfo($ch);
            if ($info['http_code'] == 200)
                $errmsg = "File uploaded successfully";
        } else {
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
}
add_action('wp_ajax_open_positions_ajax_call', 'open_positions_ajax_call');  // for logged in users only
add_action('wp_ajax_nopriv_open_positions_ajax_call', 'open_positions_ajax_call'); // for ALL users