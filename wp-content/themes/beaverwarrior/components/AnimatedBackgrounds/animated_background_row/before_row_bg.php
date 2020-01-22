<ul data-scrollalax data-scrollalax-depthrange="inside" data-scrollalax-loadmin="<?php echo $rows->settings->bw_anim_load_min; ?>"<?php if ($rows->settings->bw_ab_loadanim === "yes") { ?> data-scrolleffects-loadanimation="true"<?php } ?> class="AnimatedBackgrounds is-ScrollEffects--indeterminate<?php if ($rows->settings->bw_anim_load !== "none") { ?> is-ScrollEffects--unloaded<?php } ?>">
    <li class="AnimatedBackgrounds-extra_bg_layer"></li>
    <?php $i = 1;
    $id = "AnimatedBackgrounds--" . uniqid();

    foreach ($rows->settings->bw_anim_layers as $id => $anim_settings) {
        if (!isset($anim_settings->layer_enable) || $anim_settings->layer_enable === "no") break;
        
        $layer_srcset = wp_get_attachment_image_srcset($anim_settings->layer_image, 'full');
        $layer_id = $id . "_" . $i;
        
        if (isset($anim_settings->layer_animdata) && is_object($anim_settings->layer_animdata)) {
            $layer_animdata_text = json_encode($anim_settings->layer_animdata);
        } else {
            $layer_animdata_text = $anim_settings->layer_animdata;
        }
        
        switch ($anim_settings->layer_enable) {
            case "image":
                ?>
                    <li data-scrollalax-depth="<?php echo $anim_settings->layer_depth; ?>" class="AnimatedBackgrounds-layer AnimatedBackgrounds-layer--bob_<?php echo $anim_settings->layer_bob; ?> AnimatedBackgrounds-layer--num_<?php echo $id; ?>">
                        <div class="AnimatedBackgrounds-static_bg" style="background-image: url('<?php echo $anim_settings->layer_image_src; ?>');"></div>
                    </li>
                <?php
                break;
            case "atlas":
                ?>
                    <li data-scrollalax-depth="<?php echo $anim_settings->layer_depth; ?>" class="AnimatedBackgrounds-layer AnimatedBackgrounds-layer--bob_<?php echo $anim_settings->layer_bob; ?>">
                        <img class="AnimatedBackgrounds-atlas_source" src="<?php echo $anim_settings->layer_image_src; ?>" srcset="<?php echo $layer_srcset; ?>" alt="" id="<?php echo $layer_id . "-image"; ?>">
                        <canvas class="AnimatedBackgrounds-atlas_player" data-atlasplayer data-atlasplayer-image="#<?php echo $layer_id . "-image"; ?>" data-atlasplayer-data='<?php echo $layer_animdata_text; ?>'<?php if ($anim_settings->layer_loop === "force-loop") { ?> data-atlasplayer-loop<?php } ?><?php if ($anim_settings->layer_loop === "force-once") { ?> data-atlasplayer-once<?php } ?>></canvas>
                    </li>
                <?php
                break;
            default:
                break;
        }

        $i += 1;
    } ?>
</ul>
<?php if ($rows->settings->bw_anim_load !== "none") { ?>
    <div class="AnimatedBackgrounds-load_animation">
        <?php if ($rows->settings->bw_anim_load === "content") {
            FLBuilder::render_query(array(
                'post_type' => 'fl-builder-template',
                'p' => intval($rows->settings->bw_anim_load_content)
            ));
        } ?>
    </div>
<?php } ?>