<?php print get_template_component('HTML'); ?>
<?php print get_template_component('Page'); ?>
<?php print get_template_component('SiteHeader'); ?>

<div class="container">
	<div class="row">

		<div class="fl-content <?php FLTheme::content_class(); ?>">
			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
				<?php get_template_part('content', 'single'); ?>
			<?php endwhile; endif; ?>
		</div>

	</div>
</div>

<?php print get_template_component('SiteFooter'); ?>
<?php print get_template_component('Page', 'end'); ?>
<?php print get_template_component('HTML', 'end'); ?>
