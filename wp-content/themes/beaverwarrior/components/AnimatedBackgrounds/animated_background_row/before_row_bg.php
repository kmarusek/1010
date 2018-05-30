<ul data-scrollalax data-scrollalax-depthrange="inside"<?php if ($rows->settings->bw_ab_loadanim) { ?> data-scrolleffects-loadanimation="true"<?php } ?> class="AnimatedBackgrounds is-ScrollEffects--unloaded">
    <?php $i = 1;
    $id = "AnimatedBackgrounds--" . uniqid();

    while (true) {
        $layer_enable = "bw_ab_layer_" . $i . "_enable";
        if (!isset($rows->settings->$layer_enable) || $rows->settings->$layer_enable === "no") break;

        $layer_depth = "bw_ab_layer_" . $i . "_depth";
        $layer_animdata = "bw_ab_layer_" . $i . "_animdata";
        $layer_image = "bw_ab_layer_" . $i . "_image";
        $layer_image_src = "bw_ab_layer_" . $i . "_image_src";
        $layer_srcset = wp_get_attachment_image_srcset($rows->settings->$layer_image);
        $layer_id = $id . "_" . $i;
        
        if (isset($rows->settings->$layer_animdata) && is_object($rows->settings->$layer_animdata)) {
            $layer_animdata_text = json_encode($rows->settings->$layer_animdata);
        } else {
            $layer_animdata_text = $rows->settings->$layer_animdata;
        }
        
        switch ($rows->settings->$layer_enable) {
            case "image":
                ?>
                    <li data-scrollalax-depth="<?php echo $rows->settings->$layer_depth; ?>">
                        <div class="AnimatedBackgrounds-static_bg" style="background-image: url('<?php echo $rows->settings->$layer_image_src; ?>');"></div>
                    </li>
                <?php
                break;
            case "atlas":
                ?>
                    <li data-scrollalax-depth="<?php echo $rows->settings->$layer_depth; ?>">
                        <img class="AnimatedBackgrounds-atlas_source" src="<?php echo $rows->settings->$layer_image_src; ?>" srcset="<?php echo $layer_srcset; ?>" alt="" id="<?php echo $layer_id . "-image"; ?>">
                        <canvas class="AnimatedBackgrounds-atlas_player" data-atlasplayer data-atlasplayer-image="#<?php echo $layer_id . "-image"; ?>" data-atlasplayer-data='<?php echo $layer_animdata_text; ?>'></canvas>
                    </li>
                <?php
                break;
            default:
                break;
        }

        $i += 1;
    } ?>
</ul>