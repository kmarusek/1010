<div class="social-network-accounts-site">
    <img src="<?php echo plugins_url('../images/facebook_icon.png', __FILE__);?>" />
    <h4><?php _e('Facebook Accounts', 'microblog-poster');?></h4>
</div>
<?php
$rows = MicroblogPoster_Poster::get_accounts_object('facebook');
foreach($rows as $row):
    $update_accounts[] = $row->account_id;

    $fb_acc_extra = null;
    $fb_scope = "publish_actions";
    $post_type = "";
    $target_type = "profile";
    $page_id = '';
    $group_id = '';
    $fb_link_categories = array();
    if($row->extra)
    {
        $fb_acc_extra = json_decode($row->extra, true);
        $post_type = $fb_acc_extra['post_type'];
        //$default_image_url = $fb_acc_extra['default_image_url'];
        if(isset($fb_acc_extra['target_type']))
        {
            $target_type = $fb_acc_extra['target_type'];
        }
        if(isset($fb_acc_extra['page_id']))
        {
            $page_id = $fb_acc_extra['page_id'];
        }
        if(isset($fb_acc_extra['group_id']))
        {
            $group_id = $fb_acc_extra['group_id'];
        }
        if(isset($fb_acc_extra['link_categories']))
        {
            $fb_link_categories = $fb_acc_extra['link_categories'];
            $fb_link_categories = json_decode($fb_link_categories, true);
        }
    }

    if($target_type == "page")
    {
        $fb_scope = "publish_pages,manage_pages";
    }
    elseif($target_type == "group")
    {
        $fb_scope = "publish_to_groups";
    }
    $fb_scope = urlencode($fb_scope);

    $authorize_url = "http://www.facebook.com/dialog/oauth/?client_id={$row->consumer_key}&redirect_uri={$redirect_uri}&state=microblogposter_{$row->account_id}&scope={$fb_scope}";

?>
    <div style="display:none">
        <div id="update_account<?php echo $row->account_id;?>">
            <form id="update_account_form<?php echo $row->account_id;?>" method="post" action="" enctype="multipart/form-data" >

                <h3 class="new-account-header"><?php _e('<span class="microblogposter-name">MicroblogPoster</span> Plugin', 'microblog-poster');?></h3>
                <div class="delete-wrapper">
                    <?php _e('Facebook Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span>
                </div>
                <div id="facebook-div" class="one-account">
                    <div class="help-div"><span class="description">Facebook&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/facebook-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
                    <div class="input-div">
                        <?php _e('Username:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="username" name="username" value="<?php echo $row->username;?>"/>
                    </div>
                    <div class="input-div">
                        <?php _e('Facebook target type:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">

                        <?php if($target_type=='page'):?>
                            <span class="mbp-facebook-target-type-span"><?php _e('Page timeline', 'microblog-poster');?></span>
                        <?php elseif($target_type=='group'):?>
                            <span class="mbp-facebook-target-type-span"><?php _e('Group timeline', 'microblog-poster');?></span>
                        <?php else:?>
                            <span class="mbp-facebook-target-type-span"><?php _e('Profile timeline', 'microblog-poster');?></span>
                        <?php endif;?>
                    </div>
                    <?php if($target_type=='page'):?>
                        <div class="input-div">
                            <?php _e('Page ID:', 'microblog-poster');?>
                        </div>
                        <div class="input-div-large">
                            <input type="text" id="mbp_facebook_page_id" name="mbp_facebook_page_id" value="<?php echo $page_id;?>" />
                            <span class="description"><?php _e('Your Facebook Page ID. (numeric)', 'microblog-poster');?></span>
                        </div>
                    <?php elseif($target_type=='group'):?>
                        <div class="input-div">
                            <?php _e('Group ID:', 'microblog-poster');?>
                        </div>
                        <div class="input-div-large">
                            <input type="text" id="mbp_facebook_group_id" name="mbp_facebook_group_id" value="<?php echo $group_id;?>" />
                            <span class="description"><?php _e('Your Facebook Group ID. (numeric)', 'microblog-poster');?></span>
                        </div>
                    <?php endif;?>
                    <div class="input-div">
                        <?php _e('Message Format:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <textarea id="message_format" name="message_format" rows="2"><?php echo $row->message_format;?></textarea>
                        <span class="description"><?php _e('Message that\'s actually posted.', 'microblog-poster');?></span>
                    </div>
                    <div class="input-div">

                    </div>
                    <div class="input-div-large">
                        <span class="description-small"><?php echo $description_shortcodes;?></span>
                    </div>
                    <div class="mbp-separator"></div>
                    <div class="input-div input-div-radio">
                        <?php _e('Post Type:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="radio" name="post_type_fb" value="text" <?php if($post_type=='text') echo 'checked'; ?>> <?php _e('Text', 'microblog-poster');?> <span class="description"><?php _e('(Text only status update.)', 'microblog-poster');?></span><br>
                        <input type="radio" name="post_type_fb" value="link" <?php if($post_type=='link') echo 'checked'; ?>> <?php _e('Link', 'microblog-poster');?> <span class="description"><?php _e('(Text message + Facebook link box.)', 'microblog-poster');?></span>
                    </div>
                    <div class="input-div">

                    </div>
                    <div class="input-div-large">
                        <span class="description-small">

                        </span>
                    </div>
                    <div class="mbp-separator"></div>
                    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
                        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories($fb_link_categories);?>
                    <?php else:?>
                        <?php microblogposter_show_more_infos_category_driven();?>
                    <?php endif;?>
                    <div class="mbp-separator"></div>
                    <div class="input-div">
                        <?php _e('Application ID/API Key:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="consumer_key" value="<?php echo $row->consumer_key;?>" />
                        <span class="description">(Application ID / API Key)</span>
                    </div>
                    <div class="input-div">
                        <?php _e('Application Secret:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="consumer_secret" value="<?php echo $row->consumer_secret;?>" />
                        <span class="description">(Application Secret)</span>
                    </div>
                </div>

                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="facebook" />
                <input type="hidden" name="update_account_hidden" value="1" />
                <div class="button-holder">
                    <button type="button" class="button cancel-account" ><?php _e('Cancel', 'microblog-poster');?></button>
                    <button type="button" class="button-primary save-account<?php echo $row->account_id;?>" ><?php _e('Save', 'microblog-poster');?></button>
                </div>

            </form>
        </div>
    </div>
    <div style="display:none">
        <div id="delete_account<?php echo $row->account_id;?>">
            <form id="delete_account_form<?php echo $row->account_id;?>" method="post" action="" enctype="multipart/form-data" >
                <div class="delete-wrapper">
                <?php _e('Facebook Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span><br />
                <span class="delete-wrapper-del"><?php _e('Delete?', 'microblog-poster');?></span>
                </div>
                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="facebook" />
                <input type="hidden" name="delete_account_hidden" value="1" />
                <div class="button-holder-del">
                    <button type="button" class="button cancel-account" ><?php _e('Cancel', 'microblog-poster');?></button>
                    <button type="button" class="del-account-fb button-primary del-account<?php echo $row->account_id;?>" ><?php _e('Delete', 'microblog-poster');?></button>
                </div>
            </form>
        </div>
    </div>
    <div class="account-wrapper">
        <span class="account-username"><?php echo $row->username;?></span>
        <span class="edit-account edit<?php echo $row->account_id;?>"><?php _e('Edit', 'microblog-poster');?></span>
        <span class="del-account del<?php echo $row->account_id;?>"><?php _e('Del', 'microblog-poster');?></span>
        <?php if(isset($fb_acc_extra['access_token']) && $fb_acc_extra['access_token']):?>
            <?php if($fb_acc_extra['expires'] == '0'):?>
                <div><?php _e('Authorization is valid permanently', 'microblog-poster');?></div>
                <div><a href="<?php echo $authorize_url; ?>" ><?php _e('Re-Authorize this facebook account', 'microblog-poster');?></a></div>
            <?php else:?>
                <div><?php _e('Authorization is valid until', 'microblog-poster');?> <?php echo date('d-m-Y', $fb_acc_extra['expires']); ?></div>
                <div><a href="<?php echo $authorize_url; ?>" ><?php _e('Refresh authorization now', 'microblog-poster');?></a></div>
            <?php endif;?>
        <?php else:?>
                <div><a href="<?php echo $authorize_url; ?>" ><?php _e('Authorize this facebook account', 'microblog-poster');?></a></div>
        <?php endif;?>
    </div>

<?php endforeach;?>