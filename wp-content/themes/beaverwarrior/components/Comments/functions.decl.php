<?php

function skeletonwarrior_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    $url = get_comment_link($comment->comment_ID);
    switch ( $comment->comment_type ) {
        case 'pingback':
        case 'trackback':
        ?>
        <li class="post pingback">
            <p><?php _e( 'Pingback:', 'skeleton_warrior' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'skeleton_warrior' ), ' ' ); ?></p>
        </li>
        <?php
            break;
        default:
            ?>
                <li <?php comment_class('Comments-list_item'); ?> id="li-comment-<?php comment_ID(); ?>">
                    <article id="comment-<?php comment_ID(); ?>" class="Comments-comment">
                        <div class="Comments-info">
                            <img src="<?php echo get_avatar_url($comment->user_id); ?>" class="Comments-image">

                            <div class="Comments-author">
                                <?php if($url) { ?> <a href="<?php echo esc_url( $url ); ?>"> <?php } ?>
                                    <?php comment_author(); ?>
                                <?php if($url) { ?> </a> <?php } ?>
                                <span class="Comments-date"><?php comment_date('M j, Y'); ?> <?php comment_time(); ?></span>
                            </div>
                        </div>

                        <div class="Comments-text_wrapper">
                            <p class="Comments-text"><?php echo $comment->comment_content; ?></p>
                        </div>
                        
                        <div class="Comments-reply_link_wrapper">
                            <p class="Comments-reply_link_text small">
                                <?php
                                comment_reply_link( array_merge( $args, array(
                                    'reply_text' => 'Reply',
                                    'depth' => $depth,
                                    'max_depth' => $args['max_depth']
                                ) ) ); ?>
                            </p>
                        </div>
                    </article>
                </li>
            <?php
            break;
    }
}