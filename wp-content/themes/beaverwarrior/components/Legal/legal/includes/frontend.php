<?php
$wrapper_classes = [
	"class" => ["Legal"],
	"id" => 'LegalBanner-' . $id,
];
?>
<section <?php echo spacestation_render_attributes($wrapper_classes); ?>>

	<span class="Legal-title"><?php echo $settings->legal_option; ?></span>
	<div class="Legal-row">
		<?php
		foreach ($settings->legal_item as $item) : ?>
			<div class="Legal-content">
				<div class="Legal-content_wrapper">
					<div class="Legal-text_container">
						<h4 class='Legal-name'>
							<?php echo $item->name; ?>
						</h4>
						<h6 class='Legal-position'>
							<?php echo $item->modal_text; ?>
						</h6>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>