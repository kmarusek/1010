<div class="pp-social-icons pp-social-icons-<?php echo $settings->align; ?> pp-responsive-<?php echo $settings->responsive_align; ?>">
<?php
$icon_prefix = 'fa';
$email_icon_prefix = 'fa';
$enabled_icons = $module->_enabled_icons;

if ( in_array( 'font-awesome-5-brands', $enabled_icons ) ) {
	$icon_prefix = 'fab';
}
if ( in_array( 'font-awesome-5-solid', $enabled_icons ) ) {
	$email_icon_prefix = 'fas';
}

$labels = $module->get_labels();

foreach($settings->icons as $icon) {

	if(!is_object($icon)) {
		continue;
	}
	?>
	<span class="pp-social-icon" itemscope itemtype="http://schema.org/Organization">
		<link itemprop="url" href="<?php echo site_url(); ?>">
		<a itemprop="sameAs" href="<?php echo $icon->link; ?>" target="<?php echo isset($icon->link_target) ? $icon->link_target : '_blank'; ?>"<?php echo isset( $labels[ $icon->icon ] ) ? ' title="' . $labels[ $icon->icon ] . '" aria-label="' . $labels[ $icon->icon ] . '"' : '' ; ?> role="button">
			<?php if ( $icon->icon == 'custom' ) { ?>
				<i class="<?php echo $icon->icon_custom; ?>"></i>
			<?php } elseif ( 'fa-envelope' == $icon->icon ) { ?>
				<i class="<?php echo $email_icon_prefix; ?> <?php echo $icon->icon; ?>"></i>
			<?php } else { ?>
				<i class="<?php echo $icon_prefix; ?> <?php echo $icon->icon; ?>"></i>
			<?php } ?>
		</a>
	</span>
	<?php
}

?>
</div>
