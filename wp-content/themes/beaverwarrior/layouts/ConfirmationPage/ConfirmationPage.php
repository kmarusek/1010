<main class="ConfirmationPage">
    <div class="ConfirmationPage-content">
        <h1 class="ConfirmationPage-title"><?php the_field("confirmation_page_title"); ?></h1>
        <?php the_field("confirmation_page_body"); ?>
    </div>
    <nav class="ConfirmationPage-resources">
        <h2 class="ConfirmationPage-resources_title"><?php the_field("confirmation_page_resource_title"); ?></h2>
        <ul class="ConfirmationPage-resources_menu">
            <?php while (have_rows("confirmation_page_resources")) {
                the_row("confirmation_page_resources"); ?>
                <li><a href="<?php the_sub_field('confirmation_page_resource_url') ?>"><?php the_sub_field('confirmation_page_resource_label'); ?></a></li>
            <?php } ?>
        </ul>
    </nav>
</main>