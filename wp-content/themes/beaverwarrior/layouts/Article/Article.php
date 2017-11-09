<?php

$url = urlencode(get_permalink());
$title = urlencode(get_the_title());

$twitter = "https://twitter.com/intent/tweet?text=" . $title . "&url=" . $url;

$twitter_user = get_theme_mod('skeleton_warrior_social_twitteruser');
if ($twitter_user === FALSE || $twitter_user === "") {
    $twitter .= "&via=" . $twitter_user;
}

$facebook = "https://www.facebook.com/sharer/sharer.php?u=" . $url;
$pinterest = "https://pinterest.com/pin/create/bookmarklet/?url=" . $url . "&description=" . $title;
$linkedin = "https://www.linkedin.com/shareArticle?url=" . $url . "&title=" . $title;

?>

<?php if ( have_posts() ) { ?>
    <?php while (have_posts()) {
        the_post(); ?>
        <?php do_action('fl_before_post'); ?>

        <main <?php post_class("fl-post Article Article--full"); ?> id="fl-post-<?php the_ID(); ?>" itemscope="itemscope" itemtype="http://schema.org/BlogPosting">
            <div class="Article-body">
                <header class="fl-post-header Article-header--overview">
                    <h1 class="fl-post-title" itemprop="headline">
                        <?php the_title(); ?>
                        <?php edit_post_link( _x( 'Edit', 'Edit post link text.', 'fl-automator' ) ); ?>
                    </h1>
                    <?php FLTheme::post_top_meta(); ?>
                </header><!-- .fl-post-header -->

                <?php do_action('fl_before_post_content'); ?>

                <div class="fl-post-content clearfix" itemprop="text">
                    <?php the_content(); ?>
                </div>

                <?php do_action('fl_after_post_content'); ?>
            </div>
            <nav class="Article-share">
                <h2><?php echo __('Share this post', 'skeleton_warrior'); ?></h2>
                <ul class="Article-share_buttons">
                    <li>
                        <a href="<?php echo $facebook; ?>" class="Article-share_link Article-share_link--facebook" target="_blank"><span>Facebook</span></a>
                        <a href="<?php echo $twitter; ?>" class="Article-share_link Article-share_link--twitter" target="_blank"><span>Twitter</span></a>
                        <a href="<?php echo $pinterest; ?>" class="Article-share_link Article-share_link--pinterest" target="_blank"><span>Pinterest</span></a>
                        <a href="<?php echo $linkedin; ?>" class="Article-share_link Article-share_link--linkedin" target="_blank"><span>Linkedin</span></a>
                    </li>
                </ul>
            </nav>
            <div class="Article-comments">
                <?php comments_template(); ?>
            </div>
        </main>

        <?php do_action('fl_after_post'); ?>
    <?php }
} ?>
