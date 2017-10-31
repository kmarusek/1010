<?php print get_template_component('HTML'); ?>
<?php print get_template_component('Page'); ?>
<?php print get_template_component('SiteHeader'); ?>

<?php do_action('fl_before_content'); ?>
<div class="fl-page-content" itemprop="mainContentOfPage">
    <?php do_action('fl_content_open'); ?>
