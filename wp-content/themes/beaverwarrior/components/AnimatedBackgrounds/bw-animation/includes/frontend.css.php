<?php if ($settings->anim_load === "color") { ?>
    .fl-node-<?php echo $id; ?> .AnimatedBackgrounds.is-ScrollEffects--unloaded + .AnimatedBackgrounds-load_animation:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
        
        background-color: <?php echo $settings->anim_load_color; ?>;
    }
<?php } ?>

<?php if ($settings->anim_load === "image") { ?>
    .fl-node-<?php echo $id; ?> .AnimatedBackgrounds.is-ScrollEffects--unloaded + .AnimatedBackgrounds-load_animation:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
        
        background-color: #<?php echo $settings->anim_load_color; ?>;
        background-image: url("<?php echo $settings->anim_load_image_src; ?>");
        background-size: <?php echo $settings->anim_load_bgsize; ?>;
    }
<?php } ?>

<?php if ($settings->anim_load === "content") { ?>
    .fl-node-<?php echo $id; ?> .AnimatedBackgrounds.is-ScrollEffects--unloaded + .AnimatedBackgrounds-load_animation:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
        
        background-color: #<?php echo $settings->anim_load_color; ?>;
        background-image: url("<?php echo $settings->anim_load_image_src; ?>");
        background-size: <?php echo $settings->anim_load_bgsize; ?>;
    }
    <?php BWAnimatedBackgroundsSettingsCompat::render_content_css_by_id($settings->anim_load_content); ?>
<?php } ?>

<?php if ($settings->anim_load !== "none") { ?>
    .fl-node-<?php echo $id; ?> .AnimatedBackgrounds-load_animation {
        transition: z-index 0s <?php echo $settings->anim_load_fade; ?>s, opacity <?php echo $settings->anim_load_fade; ?>s;
    }
<?php } ?>

<?php foreach ($settings->bw_anim_layers as $layer_id => $anim_settings) { ?>
    .fl-node-<?php echo $id; ?> AnimatedBackgrounds-layer--num_<?php echo $layer_id; ?> .AnimatedBackgrounds-static_bg {
        <?php if ($anim_settings->layer_bgsize === "max_width") { ?>
            max-width: <?php echo $anim_settings->layer_max_width; ?>px;
            margin: 0 auto;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: bottom;
        <?php } else { ?>
            background-size: <?php echo $anim_settings->layer_bgsize; ?>
        <?php } ?>
    }
<?php } ?>