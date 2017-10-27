<?php
//Get only the approved comments
if (!isset($post_id)) {
    $post_id = get_the_ID();
}

$args = array(
    'status' => 'approve',
    'post_id' => $post_id,
);

$comment_args = array(
    'callback' => 'skeletonwarrior_comment',
    'reply_text' => 'Reply',
    'style' => 'ul',
);

// The comment Query
$comments_query = new WP_Comment_Query;
$comments = $comments_query->query( $args );

// Comment Loop
if ( $comments ) { ?>
    <section class="Comments">
        <h2 class="Comments-count">
            <?php echo get_comments_number(); ?> Comment<?php if (get_comments_number() > 1) { ?>s<?php } ?>
        </h2>
        <ul class="Comments-list">
            <?php wp_list_comments( $comment_args, $comments ); ?>
        </ul>
    </section>
<?php } else { ?>
    <section class="Comments Comments--empty"><p>No comments found</p></section>
<?php }

$commenter = wp_get_current_commenter();
$req = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );
$required_text = sprintf( ' ' . __('Required fields are marked %s'), '<span class="required">*</span>' );

$comment_form_args = array(
    'class_form' => 'Comments-form_inner',
    'class_submit' => 'FormItem-action FormItem-action--primary',
    'submit_field' => '<div class="FormItem--actions">%1$s %2$s</div>',
    'title_reply_before'   => '<h3 id="reply-title" class="Comments-form_title">',
    'comment_notes_before' => '<p class="Comments-form_notes"><span id="email-notes">' . __( 'Your email address will not be published.' ) . '</span>'. ( $req ? $required_text : '' ) . '</p>',
    'comment_field' =>
        '<div class="Comments-form_comment FormItem">
            <label for="comment">' . _x( 'Comment', 'noun' ) . ( $aria_req ? '<span class="required">*</span>' : '' ) . '</label>
            <textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
        </div>',
    'fields' => apply_filters( 'comment_form_default_fields', array(
        'author' =>
            '<div class="Comments-form_author FormItem">' .
                '<label for="author">' . __( 'Name' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .
                '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
                '" size="30"' . $aria_req . ' />
            </div>',
        'email' =>
            '<div class="Comments-form_email FormItem">
                <label for="email">' . __( 'Email' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .
                '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
                '" size="30"' . $aria_req . ' />
            </div>',
        'url' =>
            '<div class="Comments-form_url FormItem"><label for="url">' .
                __( 'Website' ) . '</label>' .
                '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />
            </div>'
        )),
); ?>
<section class="Comments-form"><?php comment_form($comment_form_args); ?></section>