<?php
global $wp_embed;
$w = 0;
$u = 0;
?>
<div class="team_members_wrapper">
	<div class="container-fluid">
		<div class="row">
			<?php
			foreach ( $settings->the_team_member as $member ):
				$n = preg_replace('/\s+/', '_', $member->name);
				$w++;
			?>
			<div class="team_member">
				<div class="team_member_wrapper">
					<a data-toggle="modal" data-target="#team_member_modal_<?php echo $n; ?>" href="#" class="triptych_panel_link">
						<div class="team_member_image_container">
								<img src="<?php echo $member->image_src; ?>">
						</div>
						<div class="team_member_text_container">
								<h6 class='team_member_position'>
									<?php echo $member->position; ?>
								</h6>
								<h4 class='team_member_name'>
									<?php echo $member->name; ?>
								</h4>
								<?php 
									if($member->cta !== ''):
								?>
								<a class='cta' href='<?php echo $member->url; ?>'><?php echo $member->cta; ?></a>
								<?php endif; ?>
						</div>
					</a>
				</div>
			</div>
			<?php
			endforeach;
			?>
		</div>
	</div>
</div>

<?php
foreach ( $settings->the_team_member as $modal ):
	$m = preg_replace('/\s+/', '_', $modal->name);
	$u++;
	?>
<div class="modal fade" id="team_member_modal_<?php echo $m; ?>" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span class='closeSymbol' aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3 imgCol">
							<div class="team_member_modal_image">
								<img src="<?php echo $modal->modal_image_src; ?>">
							</div>
							<div class="team_member_modal_titles">
								<h6 class='modal-position'>
									<?php echo $modal->position; ?>
								</h6>
								<h4 class='modal-title'>
									<?php echo $modal->name; ?>
								</h4>
							</div>
						</div>
						<div class="col-md-9 txtCol">
							<div class="team_member_modal_text">
								<?php echo $modal->modal_text; ?>
							</div>
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