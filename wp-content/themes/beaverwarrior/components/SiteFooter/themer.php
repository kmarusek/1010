<?php if ( FLTheme::has_footer() ) : ?>
<footer class="fl-page-footer-wrap" itemscope="itemscope" itemtype="http://schema.org/WPFooter">
  <?php

  do_action( 'fl_footer_wrap_open' );
  do_action( 'fl_before_footer_widgets' );

  FLTheme::footer_widgets();

  do_action( 'fl_after_footer_widgets' );
  do_action( 'fl_before_footer' );

  FLTheme::footer();

  do_action( 'fl_after_footer' );
  do_action( 'fl_footer_wrap_close' );

  ?>
</footer>
<?php endif; ?>
