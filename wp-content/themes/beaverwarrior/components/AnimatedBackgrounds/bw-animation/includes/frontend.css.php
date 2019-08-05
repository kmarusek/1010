<?php if ($settings->anim_load === "color") { ?>
    .fl-node-<?php echo $id; ?> .AnimatedBackgrounds.is-ScrollEffects--unloaded:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
        
        background-color: <?php echo $settings->anim_load_color; ?>;
    }
<?php } ?>

<?php if ($settings->anim_load === "image") { ?>
    .fl-node-<?php echo $id; ?> .AnimatedBackgrounds.is-ScrollEffects--unloaded:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
        
        background-color: #<?php echo $settings->anim_load_color; ?>;
        background-image: url("<?php echo $settings->anim_load_image_src; ?>");
    }
<?php } ?>