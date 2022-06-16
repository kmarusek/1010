<?php

if( !defined( 'ABSPATH' ) )
	exit;


/**
 * Load Notes Actions
 */
add_action( 'init', 'wp_client_reports_pro_load_notes_actions', 999 );
function wp_client_reports_pro_load_notes_actions(){

    if (is_admin() || wp_doing_cron()) {

        register_post_type( 'client_report_notes',
            array(
                'labels' => array(
                'name' => __( 'Site Maintenance Notes', 'wp-client-reports-pro' ),
                'singular_name' => __( 'Site Maintenance Note', 'wp-client-reports-pro' ),
            ),
            'description' => __( 'Site Maintenance Notes', 'wp-client-reports-pro' ),
            'public' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'show_ui' => false,
            'show_in_nav_menus' => false,
            'show_in_menu' => false, 
            'show_in_admin_bar' => false,
        ));
    
        add_action('wp_client_reports_stats', 'wp_client_reports_pro_stats_page_notes', 11);
        add_action('wp_client_reports_stats_email', 'wp_client_reports_pro_stats_email_notes', 11, 2);
        add_action('wp_ajax_wp_client_reports_pro_notes_data', 'wp_client_reports_pro_notes_data');

    }

}


/**
 * Save a new note or edit an existing one
 */
add_action('wp_ajax_wp_client_reports_pro_add_note', 'wp_client_reports_pro_add_note_from_ajax');
function wp_client_reports_pro_add_note_from_ajax() {

    if (current_user_can('administrator')) {

        $timezone = wp_timezone();

        $note_text = $_POST['note_text'];
        $note_action = sanitize_text_field($_POST['note_action']);
        $note_icon_type = sanitize_text_field($_POST['note_icon_type']);
        $note_date = sanitize_text_field($_POST['note_date']);
        $note_id = intval($_POST['note_id']);
        $edit_type = 'new';

        $note_date_object = new DateTime($note_date, $timezone);
        $note_date_object->setTime(00, 00, 00);
        $note_date_timestamp = $note_date_object->format('Y-m-d H:i:s');
        $note_date_object->setTimezone(new DateTimeZone('UTC'));
        $note_date_timestamp_gmt = $note_date_object->format('Y-m-d H:i:s');

        if ($note_action == 'delete' && $note_id !== 0 && get_post_type($note_id) == 'client_report_notes') {
            wp_delete_post($note_id);
            $edit_type = 'delete';
        } else {

            $author_id = get_current_user_id();

            $post = array(
                'post_status' => 'publish',
                'post_date' => $note_date_timestamp,
                'post_date_gmt' => $note_date_timestamp_gmt,
                'post_content' => $note_text,
                'post_excerpt' => $note_icon_type,
                'post_author' => $author_id,
                'post_title' => null,
                'post_type' => 'client_report_notes',
            );

            if ($note_id === 0) {
                $edit_type = 'new';
            } else if (get_post_type($note_id) == 'client_report_notes') {
                $post['ID'] = $note_id;
                $edit_type = 'edit';
            }

            $post_id = wp_insert_post( $post );

        }

        if ($note_action == 'addedit' && $post_id) {
            wp_client_reports_delete_transients('wp_client_reports_notes');
            if ($edit_type == 'new') {
                echo json_encode(['status' => 'success', 'message' => __( 'Note Successfully Added', 'wp-client-reports-pro' )]);
            } else if ($edit_type == 'edit') {
                echo json_encode(['status' => 'success', 'message' => __( 'Note Successfully Edited', 'wp-client-reports-pro' )]);
            }
        } else if ($note_action == 'delete' && $edit_type == 'delete') {
            echo json_encode(['status' => 'success', 'message' => __( 'Note Deleted', 'wp-client-reports-pro' )]);
        } else {
            echo json_encode(['status' => 'error', 'message' => __( 'There was an error saving the note.', 'wp-client-reports-pro' )]);
        }

    } else {

        echo json_encode(['status' => 'error', 'message' => __( 'Only site administrators can add maintenance notes.', 'wp-client-reports-pro' )]);
        
    }

    wp_die();

}


/**
 * Ajax request report data for Notes
 */
function wp_client_reports_pro_notes_data() {

    $start = null;
    $end = null;
    if (isset($_GET['start'])) {
        $start = sanitize_text_field($_GET['start']);
    }
    if (isset($_GET['end'])) {
        $end = sanitize_text_field($_GET['end']);
    }

    $dates = wp_client_reports_validate_dates($start, $end);

    $data = wp_client_reports_pro_get_notes_data($dates->start_date, $dates->end_date);

    print json_encode($data);
    wp_die();

}


/**
 * Get report data for Notes
 */
function wp_client_reports_pro_get_notes_data($start_date, $end_date) {

    $date_format = get_option('date_format');
    
    $timezone = wp_timezone();

    $start_date_object = new DateTime($start_date . " 00:00:00", $timezone);
    $start_date_object->setTimezone(new DateTimeZone('UTC'));
    $start_date_mysql = $start_date_object->format('Y-m-d H:i:s');

    $end_date_object = new DateTime($end_date . " 23:59:59", $timezone);
    $end_date_object->setTimezone(new DateTimeZone('UTC'));
    $end_date_mysql = $end_date_object->format('Y-m-d H:i:s');

    $notes_data = get_transient('wp_client_reports_notes_data_' . wp_client_reports_nodash($start_date) . '_' . wp_client_reports_nodash($end_date));

    if ($notes_data === false) {

        $notes_data = array();

        $notes_args = array(
            'post_type' => 'client_report_notes',
            'order' => 'ASC',
            'posts_per_page' => -1,
            'date_query' => array(
                'before' => $end_date_mysql,
                'after'  => $start_date_mysql,
                'inclusive' => true,
            ),
        );
        $notes_query = new WP_Query( $notes_args );

        if ($notes_query->have_posts()){
            foreach($notes_query->posts as $note) {
                $note_object = new \stdClass;
                $note_object->id = $note->ID;

                $note_date_object = new DateTime($note->post_date_gmt, $timezone);
                $note_date = $note_date_object->format($date_format);


                $note_object->date = $note_date;
                $note_object->icon = $note->post_excerpt;
                if ($note->post_excerpt) {
                    $note_object->icon_url = WP_CLIENT_REPORTS_PRO_PLUGIN_URL . '/img/icon-notes-' . $note->post_excerpt . '.png';
                } else {
                    $note_object->icon_url = null;
                }
                $note_object->note = $note->post_content;

                $notes_data[] = $note_object;
            }
        }
        

        set_transient('wp_client_reports_data_notes_data_' . wp_client_reports_nodash($start_date) . '_' . wp_client_reports_nodash($end_date), $notes_data, 3600 * 24);
    }

    $notes_data = apply_filters( 'wp_client_reports_pro_notes_data', $notes_data, $start_date, $end_date );

    return $notes_data;

}


/**
 * Report page section for Notes
 */
function wp_client_reports_pro_stats_page_notes() {
    ?>
        <div class="metabox-holder">
            <div class="postbox wp-client-reports-postbox loading" id="wp-client-reports-pro-notes">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Site Maintenance Notes','wp-client-reports-pro'); ?></h2>
                </div>
                <div class="inside">
                    <div class="main">

                        <div class="wp-client-report-section">

                            <ul id="wp-client-reports-pro-notes-list" class="wp-client-reports-list"></ul>

                            <?php if (current_user_can('administrator')) : ?>
                                <div><a href="#TB_inline?width=600&height=525&inlineId=wp-client-reports-pro-add-note-modal" class="thickbox button button-primary" id="wp-client-reports-pro-add-new-note"><?php _e( 'Add New', 'wp-client-reports-pro' ); ?></a></div>
                            <?php endif; ?>

                        </div>

                    </div><!-- .inside -->
                </div><!-- .main -->
            </div><!-- .postbox -->
        </div><!-- .metabox-holder -->

        <?php if (current_user_can('administrator')) : ?>
            <div id="wp-client-reports-pro-add-note-modal" class="wp-client-reports-pro-add-note-modal" style="display:none;">
                <form method="GET" action="#" id="wp-client-reports-pro-add-note">

                    <div class="wp-client-reports-field-group" style="margin-top:20px;">
                        <label class="wp-client-reports-label" for="note-icon"><?php _e( 'Icon', 'wp-client-reports-pro' ); ?></label>
                        <div class="wp-client-reports-pro-note-select-icon"><a href="#" class="selected" data-icon="info"><img src="/wp-content/plugins/wp-client-reports-pro/img/icon-notes-info.png" width="20" height="20"></a> <a href="#" data-icon="added"><img src="/wp-content/plugins/wp-client-reports-pro/img/icon-notes-added.png" width="20" height="20"></a><a href="#" data-icon="completed"><img src="/wp-content/plugins/wp-client-reports-pro/img/icon-notes-completed.png" width="20" height="20"></a><a href="#" data-icon="alert"><img src="/wp-content/plugins/wp-client-reports-pro/img/icon-notes-alert.png" width="20" height="20"></a><a href="#" data-icon="removed"><img src="/wp-content/plugins/wp-client-reports-pro/img/icon-notes-removed.png" width="20" height="20"></a></div>
                    </div>

                    <div class="wp-client-reports-field-group">
                        <label class="wp-client-reports-label" for="note-date"><?php _e( 'Date', 'wp-client-reports-pro' ); ?></label>
                        <input name="note_date" type="text" id="note-date" value="" required class="regular-text">
                    </div>

                    <div class="wp-client-reports-field-group">
                        <label class="wp-client-reports-label" for="note-text"><?php _e( 'Note Text', 'wp-client-reports-pro' ); ?></label>
                        <textarea name="note_text" id="note-text" rows="4" required class="large-text"></textarea>
                    </div>

                    <?php /* $settings = array(
                            'tinymce'       => array(
                                'toolbar1'      => 'bold,italic,underline,separator,link,unlink',
                                'toolbar2'      => '',
                                'toolbar3'      => '',
                            ),
                            'media_buttons' => false,
                            'quicktags' => false
                        );
                    ?>
                    <?php wp_editor( '', 'wp-client-reports-pro-edit-note', $settings ); */ ?>
                
                    <input type="hidden" name="note_icon_type" value="info">
                    <input type="hidden" name="note_id" value="">
                    <input type="hidden" name="note_action" value="addedit">
                    <input type="hidden" name="action" value="wp_client_reports_pro_add_note">

                    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Note','wp-client-reports-pro'); ?>"><img src="<?php echo admin_url(); ?>images/spinner-2x.gif" id="save-note-spinner" style="display:none;"> <a href="#" style="color:#d63638;float:right;line-height:30px;" id="wp-client-reports-pro-note-delete">Delete Note</a></p>
                </form>
                <div class="notice wp-client-reports-success" id="wp-client-reports-pro-note-status" style="display:none;margin-top:26px;">
                    <p></p>
                </div>
            </div><!-- #wp-client-reports-which-email-modal -->
        <?php endif;
}




/**
 * Report email section for Notes
 */
function wp_client_reports_pro_stats_email_notes($start_date, $end_date) {
    $notes_data = wp_client_reports_pro_get_notes_data($start_date, $end_date);

    wp_client_reports_render_email_header(__( 'Site Maintenance Notes', 'wp-client-reports-pro' ));

    ?>
        <tr>
            <td bgcolor="#ffffff" align="left" style="padding: 0px 40px 40px 40px; font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell,'Helvetica Neue',sans-serif; font-size: 14px; line-height: 20px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <?php
                if (is_array($notes_data) && !empty($notes_data)) : 
                    foreach($notes_data as $note) :
                        $allowed_html = ['strong' => [], 'em' => [], 'b' => [], 'i' => [], 'a' => ['href' => [] ] ];
                        $note_text = stripslashes(wp_kses($note->note, $allowed_html));
                        echo '<tr><td style="width:20px;padding:8px 8px 8px 0px;border-bottom:solid 1px #dddddd;vertical-align:top;"><img src="' . esc_html($note->icon_url) . '" width="20" height="20"></td><td style="width:400px;padding:8px;border-bottom:solid 1px #dddddd;vertical-align:top;">' . $note_text . '</td><td style="text-align:right;width:100px;padding:8px 0px 8px 8px;border-bottom:solid 1px #dddddd;vertical-align:top;">' . esc_html($note->date) . '</td>';
                    endforeach;
                endif;
                ?>
                </table>
            </td>
        </tr>
    <?php
    
}


/**
 * When force refresh is called, clear all transient data
 */
add_action( 'wp_client_reports_force_update', 'wp_client_reports_force_notes_update', 13 );
function wp_client_reports_force_notes_update() {
    wp_client_reports_delete_transients('wp_client_reports_notes');
}