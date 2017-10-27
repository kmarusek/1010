<?php $form = get_field('contact_page_form'); ?>

<main class="ContactPage" itemscope itemtype="http://schema.org/Organization">
    <div class="ContactPage-content_row">
        <section class="ContactPage-content">
            <?php the_field("contact_page_message"); ?>
            <div class="ContactPage-form">
                <?php if ($form !== false && $form !== null) {
                    //Apparantly, when Gforms says "Form -Object-" they don't really mean it...
                    //This code is here to support multiple forms just in case someone turns that on
                    if (isset($form["title"])) {
                        $form = array($form);
                    }

                    foreach ($form as $delta => $form_indiv) {
                        gravity_form_enqueue_scripts($form_indiv["id"], true);
                        gravity_form($form_indiv["id"], false, true, false, '', true, 1);
                    }
                } ?>
            </div>
        </section>
        <section class="ContactPage-contacts">
            <h2 class="ContactPage-contacts_title"><?php the_field("contact_page_contacts_title"); ?></h2>
            <?php while (have_rows("contact_page_contact_options")) {
                the_row("contact_page_contact_options"); 
            
                switch (get_row_layout()) { 
                    case "address":
                        ?>
                            <div class="ContactPage-contact_option ContactPage-contact_info--address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                                <h3 class="ContactPage-contact_option_title"><?php the_sub_field("contact_option_title"); ?></h3>
                                <?php if (get_sub_field('contact_pobox') !== "") { ?>
                                    <span itemprop="postOfficeBoxNumber"><?php the_sub_field('contact_pobox'); ?><br></span>
                                <?php } ?>
                                <?php if (get_sub_field('contact_street_address') !== "") { ?>
                                    <span itemprop="streetAddress"><?php the_sub_field('contact_street_address'); ?><br></span>
                                <?php } ?>
                                <span itemprop="addressLocality"><?php the_sub_field('contact_address_locality'); ?></span>,
                                <span itemprop="addressRegion"><?php the_sub_field('contact_address_region'); ?></span>
                                <span itemprop="postalCode"><?php the_sub_field('contact_postal_code'); ?></span>
                                <?php if (get_sub_field('contact_address_country') !== "") { ?>
                                    <br><span itemprop="addressCountry"><?php the_sub_field('contact_address_country'); ?></span>
                                <?php } ?>
                            </div>
                        <?php
                        break;
                    case "phone":
                        ?>
                            <div class="ContactPage-contact_option ContactPage-contact_info--phone">
                                <h3 class="ContactPage-contact_option_title"><?php the_sub_field("contact_option_title"); ?></h3>
                                <?php if (get_sub_field('contact_telephone') !== "") { ?>
                                    <p>Phone: <a href="tel:<?php the_sub_field('contact_telephone_dialable'); ?>" itemprop="telephone"><?php the_sub_field('contact_telephone'); ?></a></p>
                                <?php } ?>
                                <?php if (get_sub_field('contact_faxnumber') !== "") { ?>
                                    <p>Fax: <a href="tel:<?php the_sub_field('contact_faxnumber_dialable'); ?>" itemprop="faxNumber"><?php the_sub_field('contact_faxnumber'); ?></a></p>
                                <?php } ?>
                            </div>
                        <?php
                        break;
                    case "email":
                        ?>
                            <div class="ContactPage-contact_option ContactPage-contact_info--email">
                                <h3 class="ContactPage-contact_option_title"><?php the_sub_field("contact_option_title"); ?></h3>
                                <p><a href="mailto:<?php the_sub_field('contact_email'); ?>" itemprop="email"><?php the_sub_field('contact_email'); ?></a></p>
                            </div>
                        <?php
                        break;
                    default:
                        break;
                }
            }
            ?>
        </section>
    </div>
    
    <?php while (have_rows('contact_page_map')) { 
        the_row('contact_page_map');
        print get_template_component("GoogleMap");
    } ?>
</main>