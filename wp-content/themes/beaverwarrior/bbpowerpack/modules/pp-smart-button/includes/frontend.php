<?php
// Default classes for the button
$anchor_classes = array('pp-button');
$nofollow = isset( $settings->link_no_follow ) && 'yes' == $settings->link_no_follow ? ' rel="nofollow"' : '';
// If we're using event tracking
$event_tracking_enabled = isset($settings->event_tracking_enabled) && $settings->event_tracking_enabled;
// Default value for event_tracking_attributes is an empty string
$event_tracking_attributes_string = '';
// If we're using event tracking, add the class to the link classes
if ($event_tracking_enabled){
    // Add the class
    array_push($anchor_classes, PP_SMART_BUTTON_EVENT_TRACKING_CLASS);
    // Create an array of all the attributes we want to use
    $event_tracking_attributes_key_values = array(
        'category' => isset($settings->event_tracking_category) ? $settings->event_tracking_category    : null,
        'action'   => isset($settings->event_tracking_action)   ? $settings->event_tracking_action      : null,
        'label'    => isset($settings->event_tracking_label)    ? $settings->event_tracking_label       : null,
        'value'    => isset($settings->event_tracking_value)    ? $settings->event_tracking_value       : null
    );
    $event_tracking_attributes = array();
    // Now we need to create our attribute string. To do this, we need to loop through the attributes
    // and add attributes for non-null values
    foreach ($event_tracking_attributes_key_values as $attribute_key => $attribute_value) {
        // Only do something if the value is non-null
        if ($attribute_value){
            // Add the attribute to the array
            array_push($event_tracking_attributes, "data-ga-$attribute_key=\"$attribute_value\"");
        }
    }
    // Now redeclare our attributes string
    $event_tracking_attributes_string = implode(' ', $event_tracking_attributes);
}
?>
<div class="<?php echo $module->get_classname(); ?>">
	<a href="<?php echo $settings->link; ?>" target="<?php echo $settings->link_target; ?>" class="<?php echo implode(' ', $anchor_classes); ?>" role="button"<?php echo $nofollow; ?><?php echo $event_tracking_attributes_string; ?>>
		<?php if ( ! empty( $settings->icon ) && ( 'before' == $settings->icon_position || ! isset( $settings->icon_position ) ) && $settings->display_icon == 'yes' ) : ?>
		<i class="pp-button-icon pp-button-icon-before fa <?php echo $settings->icon; ?>"></i>
  <?php endif; ?>
  <span class="pp-button-text"><?php echo $settings->text; ?></span>
  <?php if ( ! empty( $settings->icon ) && 'after' == $settings->icon_position && $settings->display_icon == 'yes' ) : ?>
      <i class="pp-button-icon pp-button-icon-after fa <?php echo $settings->icon; ?>"></i>
  <?php endif; ?>
</a>
</div>
