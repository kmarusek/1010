<?php

$default_pagecolor = get_theme_mod('skeleton_warrior_pagecolor');
$has_pageborder = get_theme_mod('skeleton_warrior_pageborder') === "border";
?>

<div class="fl-page Page Page--<?php echo $default_pagecolor; ?><?php if ($has_pageborder) { ?> Page--with_border<?php } ?>">
    <?php do_action('fl_page_open'); ?>
    <?php if ($has_pageborder) { ?><div class="Page-border"></div><?php } ?>
