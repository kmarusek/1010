<?php

$photos = $module->get_photos();

?>
<div class="pp-3d-slider pp-user-agent-<?php echo pp_get_user_agent(); ?>">
    <div class="pp-slider-wrapper">
        <?php if ( $photos ) : $target = ' target="' . $settings->link_target . '"'; ?>
            <?php foreach ( $photos as $photo ) :
                $url = $photo->url;
                $tag = 'div';
                $href = '';
                if ( $url && !empty( $url ) || 'yes' == $settings->lightbox ) {
					$tag = 'a';
					if ( 'yes' == $settings->lightbox ) {
						$href = ' href="'.$photo->link.'"';
					} else {
						$href = ' href="'.$url.'"';
					}
                }
				?>
                <<?php echo $tag . $href . $target; ?> class="pp-slide" data-caption="<?php echo $photo->caption; ?>">
                    <img class="pp-slider-img" src="<?php echo $photo->src; ?>" alt="<?php echo $photo->alt; ?>" />
                    <?php if ( 'yes' == $settings->show_captions ) { ?>
                        <div class="pp-slider-img-caption"><?php echo $photo->caption; ?></div>
                    <?php } ?>
                </<?php echo $tag; ?>>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php if ( 'no' == $settings->autoplay ) { ?>
        <nav class="pp-slider-nav">
    		<span class="pp-slider-nav-button pp-slider-prev"><svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512"><path fill="currentColor" d="M25.1 247.5l117.8-116c4.7-4.7 12.3-4.7 17 0l7.1 7.1c4.7 4.7 4.7 12.3 0 17L64.7 256l102.2 100.4c4.7 4.7 4.7 12.3 0 17l-7.1 7.1c-4.7 4.7-12.3 4.7-17 0L25 264.5c-4.6-4.7-4.6-12.3.1-17z" class=""></path></svg><span class="sr-only"><?php echo __( 'Previous', 'bb-powerpack' ); ?></span></span>
    		<span class="pp-slider-nav-button pp-slider-next"><svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512"><path fill="currentColor" d="M166.9 264.5l-117.8 116c-4.7 4.7-12.3 4.7-17 0l-7.1-7.1c-4.7-4.7-4.7-12.3 0-17L127.3 256 25.1 155.6c-4.7-4.7-4.7-12.3 0-17l7.1-7.1c4.7-4.7 12.3-4.7 17 0l117.8 116c4.6 4.7 4.6 12.3-.1 17z" class=""></path></svg><span class="sr-only"><?php echo __( 'Next', 'bb-powerpack' ); ?></span></span>
    	</nav>
    <?php } ?>
</div>
