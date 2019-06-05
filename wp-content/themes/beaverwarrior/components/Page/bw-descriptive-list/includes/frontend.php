<?php
// Get all of the list items
$list_items = $module->getListItems();
?>
<ul class="descriptive-list-container">
    <?php
    // Loop through the list items
    for ( $i=0; $i<count($list_items); $i++ ){
        // The current item
        $current_list_item = $list_items[$i];
        echo sprintf(
            '<li>
            <div class="icon-container">
            %s
            </div>
            <div class="content-container">
            <dl>
            <dt>%s</dt>
            <dd>%s</dd>
            </dl>
            </div>
            </li>
            ',
            // The icon
            $current_list_item->icon_enabled === 'enabled' && $current_list_item->icon ? sprintf( '<i class="dl-icon %s"></i>', $current_list_item->icon ) : '',
            // The term we're using
            $current_list_item->dt_text,
            // The definition we're using
            $current_list_item->dd_text
        );
    }
    ?>
</ul>