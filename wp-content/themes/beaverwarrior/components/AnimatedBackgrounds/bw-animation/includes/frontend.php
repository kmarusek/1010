<div class="AnimatedBackgrounds-standalone_wrapper">
    <div class="AnimatedBackgrounds-standalone" style="padding-top: <?php echo 1 / floatval($settings->aspect_ratio) * 100; ?>%">
        <ul data-scrollalax data-scrollalax-depthrange="inside" class="AnimatedBackgrounds is-ScrollEffects--indeterminate">
            <li class="AnimatedBackgrounds-extra_bg_layer"></li>
            <?php $i = 1;
            $id = "AnimatedBackgrounds--" . uniqid();

            while (true) {
                $layer_enable = "bw_ab_layer_" . $i . "_enable";
                if (!isset($settings->$layer_enable) || $settings->$layer_enable === "no") break;

                $layer_depth = "bw_ab_layer_" . $i . "_depth";
                $layer_animdata = "bw_ab_layer_" . $i . "_animdata";
                $layer_image = "bw_ab_layer_" . $i . "_image";
                $layer_image_src = "bw_ab_layer_" . $i . "_image_src";
                $layer_srcset = wp_get_attachment_image_srcset($settings->$layer_image, 'full');
                $layer_id = $id . "_" . $i;

                if (isset($settings->$layer_animdata) && is_object($settings->$layer_animdata)) {
                    $layer_animdata_text = json_encode($settings->$layer_animdata);
                } else {
                    $layer_animdata_text = $settings->$layer_animdata;
                }

                switch ($settings->$layer_enable) {
                    case "image":
                        ?>
                            <li data-scrollalax-depth="<?php echo $settings->$layer_depth; ?>">
                                <div class="AnimatedBackgrounds-static_bg" style="background-image: url('<?php echo $settings->$layer_image_src; ?>');"></div>
                            </li>
                        <?php
                        break;
                    case "atlas":
                        ?>
                            <li data-scrollalax-depth="<?php echo $settings->$layer_depth; ?>">
                                <img class="AnimatedBackgrounds-atlas_source" src="<?php echo $settings->$layer_image_src; ?>" srcset="<?php echo $layer_srcset; ?>" alt="" id="<?php echo $layer_id . "-image"; ?>">
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
    </div>
</div>