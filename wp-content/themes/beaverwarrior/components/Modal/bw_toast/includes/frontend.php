<?php

$idbase = uniqid();

?>

<button type="button" class="Button" data-target="#<?php echo $idbase; ?>" data-toggle="offcanvas" data-toggle-options="nohover"><?php echo $module->get_button_content($settings); ?></button>
<div class="Modal<?php if ($settings->reveal_style != "modal") { echo " Modal--" . $settings->reveal_style; } ?> is-Offcanvas--closed" id="<?php echo $idbase; ?>">
    <button type="button" class="Modal-close" data-dismiss="offcanvas">X</button>
    <div class="Modal-content">
        <?php echo $module->get_modal_content( $settings ); ?>
    </div>
</div>
<div class="Offcanvas-backdrop is-Offcanvas--backdrop_inactive" data-offcanvas-backdrop="1" data-offcanvas-backdrop-for="<?php echo $idbase; ?>"></div>
