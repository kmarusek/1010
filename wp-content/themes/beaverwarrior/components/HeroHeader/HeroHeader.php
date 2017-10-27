<?php 

if (!isset($post_id)) {
    $post_id = get_the_id();
}

$title = get_field('hero_header_title', $post_id);
if ($title === NULL || $title === "") {
    $title = get_the_title($post_id);
}

$img = get_field('hero_header_image', $post_id);
if ($img === NULL) {
    $img = "";
} else {
    $img = $img["url"];
}

$background_class = get_field("hero_header_background_color", $post_id);
$focal_class = get_field("hero_header_image_focus", $post_id);
$alignment_class = get_field("hero_header_alignment", $post_id);
$size_class = get_field("hero_header_size", $post_id);

if ($size_class !== "disabled") { ?>
    <header class="HeroHeader HeroHeader--<?php echo $background_class; ?> HeroHeader--<?php echo $size_class; ?> HeroHeader--<?php echo $focal_class; ?>" style="background-image: url(<?php echo $img ?>);">
        <div class="HeroHeader-valign HeroHeader-valign--<?php echo $alignment_class; ?>">
            <div class="HeroHeader-container">
                <h1 class="HeroHeader-title"><?php echo $title; ?></h1>
                <?php the_field("hero_header_content", $post_id); ?>
            </div>
        </div>
    </header>
<?php }