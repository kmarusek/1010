<?php
wp_enqueue_script( 'jquery-ui-core' );
wp_enqueue_script( 'youtube-iframe-api', 'https://www.youtube.com/iframe_api' );
?>
<div class="video-source-container video-source-youtube">
    <div class="video-source" data-youtube-embed-id="<?php echo $youtube_id; ?>"></div>
</div>