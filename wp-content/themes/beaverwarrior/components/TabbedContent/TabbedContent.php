<?php $rowids = array();
    $bg = get_sub_field('tabbedcontent_bg');
?>

<section class="ContentSection ContentSection--<?php the_sub_field('tabbedcontent_bg_color'); ?>">
    <?php if ($bg !== false) { ?>
        <div class="ContentSection-background" style="background-image: url('<?php echo $bg["url"]; ?>');"></div>
    <?php } else { ?>
        <div class="ContentSection-background ContentSection--<?php the_sub_field('tabbedcontent_bg_color'); ?>-background"></div>
    <?php } ?>
    <div class="Container TabbedContent">
        <h2 class="TabbedContent-title"><?php the_sub_field('tabbedcontent_title'); ?></h2>
        <nav class="TabbedContent-navigation">
            <ul class="TabbedContent-selector_list TabbedContent-selector_list--centered" data-tabbedcontent-set>
                <?php while (have_rows('tabbedcontent_tabs')) {
                    the_row('tabbedcontent_tabs');
                    $this_rowid = uniqid();
                    $rowids[] = $this_rowid;
                    $icon = get_sub_field("tabbedcontent_tab_icon");
                ?>
                    <li>
                        <a href="#<?php echo $this_rowid; ?>">
                            <?php if ($icon !== false) { ?>
                                <img src="<?php echo $icon["url"]; ?>"
                                     srcset="<?php echo skeletonwarrior_srcset($icon); ?>"
                                     class="TabbedContent-selector_icon"
                                     alt="">
                            <?php } ?>
                            <?php the_sub_field("tabbedcontent_tab_title"); ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <div class="TabbedContent-sections">
            <?php $i = 0;
            while (have_rows('tabbedcontent_tabs')) {
                the_row('tabbedcontent_tabs'); ?>
                <section id="<?php echo $rowids[$i]; ?>" class="TabbedContent-section" data-tabbedcontent-region<?php if ($i === 0) echo " data-tabbedcontent-region-active"; ?>>
                    <?php the_sub_field('tabbedcontent_tab_content'); ?>
                </section>
            <?php $i++;
            } ?>
        </div>
    </div>
</section>