<?php print get_template_component('HTML'); ?>
<?php print get_template_component('Page'); ?>

<?php if (get_theme_mod("bw-header-phylactery") == "true") { ?>
    <?php print get_template_component('SiteHeader'); ?>
<?php } else { ?>
    <?php print get_template_component('SiteHeader', 'themer'); ?>
<?php } ?>

<?php do_action('fl_before_content'); ?>
<div class="fl-page-content" itemprop="mainContentOfPage">
    <?php do_action('fl_content_open'); ?>
