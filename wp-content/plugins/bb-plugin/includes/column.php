<?php $element = ( ! empty( $col->settings->container_element ) ? $col->settings->container_element : 'div' ); ?>
<<?php echo $element; ?><?php echo FLBuilder::render_column_attributes( $col ); ?>>
	<div class="fl-col-content fl-node-content">
	<?php FLBuilder::render_modules( $col ); ?>
	</div>
</<?php echo $element; ?>>
