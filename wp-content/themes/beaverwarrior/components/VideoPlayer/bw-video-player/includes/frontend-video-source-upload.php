<?php
// Get the attachent file
$attachment_file = wp_get_attachment_url( $this->settings->video );
?>
<video playsinline controls>
    <source src="<?php echo $attachment_file; ?>" type="video/mp4" >
</video>