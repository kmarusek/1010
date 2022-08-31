<?php
$wrapper_classes = [
	"class" => ["GridTeam"],
	"id" => 'GridTeamBanner-' . $id,
];

?>
<section <?php echo spacestation_render_attributes($wrapper_classes); ?>>
	<?php
	global $wp_embed;
	$w = 0;
	$u = 0;
	?>
	<span class="GridTeam-team"><?php echo $settings->meet_team; ?></span>
	<div class="GridTeam-row">
		<?php
		foreach ($settings->the_team_member as $member) :
			$n = preg_replace('/\s+/', '_', $member->name);
			$w++;
		?>
			<div class="GridTeam-team_member">
				<div class="GridTeam-member_wrapper">
					<?php
					if ($member->include_modal == 'yes') : ?>
						<a data-toggle="modal" data-target="#team_member_modal_<?php echo $n; ?>" href="#" class="GridTeam-panel_link">
						<?php else : ?>
							<a class="GridTeam-panel_link">
<?php endif; ?>
						<div class="GridTeam-image_container">
							<img src="<?php echo wp_get_attachment_image_url($member->image, 'medium'); ?>">
						</div>
						<div class="GridTeam-text_container">
							<h4 class='GridTeam-name'>
								<?php echo $member->name; ?>
							</h4>
							<h6 class='GridTeam-position'>
								<?php echo $member->position; ?>
							</h6>
						</div>
						</a>
				</div>
			</div>
		<?php
		endforeach;
		?>
	</div>


	<?php
	foreach ($settings->the_team_member as $modal) :
		$m = preg_replace('/\s+/', '_', $modal->name);
		$u++;
	?>

		<div class="GridTeam-modal GridTeam-fade" id="team_member_modal_<?php echo $m; ?>" role="dialog" aria-hidden="true">
			<div class="GridTeam-modal-dialog GridTeam-modal-dialog-centered modal-lg" role="document">
				<div class="GridTeam-modal-content">
					<div class="GridTeam-modal-header">
						<button type="button" class="GridTeam-close" data-dismiss="modal" aria-label="Close">
							<span class='GridTeam-closeSymbol' aria-hidden="true"><i class="<?php echo $settings->back_icon; ?>"></i>Go back</span>
						</button>
					</div>
					<div class="GridTeam-modal-body">
						<div class="container-fluid">
							<div class="GridTeam-rowe">
								<div class="GridTeam-txtCol">
									<div class="GridTeam-modal_titles">
										<h4 class='GridTeam-modal-title'>
											<?php echo $modal->name; ?>
										</h4>
										<h6 class='GridTeam-modal-position'>
											<?php echo $modal->position; ?>
										</h6>
									</div>
									<h4 class='GridTeam-modal-title'>Bio</h4>
									<div class="GridTeam-modal_text">
										<?php echo $modal->modal_text; ?>
									</div>
									<?php
									if ($member->cta !== '') :
									?><h4 class='GridTeam-modal-title'>Contact</h4>
										<a class='GridTeam-cta' href='<?php echo $member->url; ?>'><?php echo $member->cta; ?></a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
					</div>
				</div>
			</div>
		</div>

	<?php
	endforeach;
	?>
</section>