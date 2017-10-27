<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="SiteHeader-search SiteHeader-search--first_menu">
    <div class="SiteHeader-form_item FormItem">
        <input type="search" class="field" name="s" id="s" placeholder="<?php esc_attr_e( 'Search', 'twentyeleven' ); ?>" aria-label="<?php esc_attr_e( 'Search', 'twentyeleven' ); ?>" />
    </div>
    <div class="FormItem--actions SiteHeader-form_button_wrapper">
        <button type="submit" class="submit FormItem-action FormItem-action--primary SiteHeader-form_button SiteHeader-search_button" name="submit" id="searchsubmit">
            <?php esc_attr_e( 'Search', 'twentyeleven' ); ?>
        </button>
    </div>
</form>