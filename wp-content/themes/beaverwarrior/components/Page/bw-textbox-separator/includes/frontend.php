<?php
// Get the header text
$header_text = $module->getTextboxHeaderText();
// Get the header type
$header_type = $module->getTextboxHeaderType();
?>
<div class="textbox-separator-container">
    <div class="textbox">
        <?php
        echo sprintf(
            '<%s class="textbox-header">%s</%s>',
            // For the opening tag
            $header_type,
            // The text
            $header_text,
            // For the closing tag
            $header_type
        );
        ?>
    </div>
</div>