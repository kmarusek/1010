<ul data-scrollalax data-scrollalax-depthrange="outside">
    <?php $i = 1;

    while (true) {
        $layer_enable = "bw_ab_layer_" . $i . "_enable";
        if ($rows->settings->$layer_enable != "yes") break;

        $layer_depth = "bw_ab_layer_" . $i . "_depth";
        $layer_image = "bw_ab_layer_" . $i . "_image";
        $layer_image_src = "bw_ab_layer_" . $i . "_image_src";
        $layer_srcset = wp_get_attachment_image_srcset($rows->settings->$layer_image);

        ?>
            <li data-scrollalax-depth="<?php echo $rows->settings->$layer_depth; ?>">
                <img src="<?php echo $rows->settings->$layer_image_src; ?>">
            </li>
        <?php

        $i += 1;
    } ?>
</ul>