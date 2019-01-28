<div class="social-network-accounts-site">
    <img src="<?php echo plugins_url('../images/vkontakte_icon.png', __FILE__);?>" />
    <h4><?php _e('VKontakte Accounts', 'microblog-poster');?></h4>
</div>
<?php
$rows = MicroblogPoster_Poster::get_accounts_object('vkontakte');
foreach($rows as $row):
    $update_accounts[] = $row->account_id;

    $vk_acc_extra = null;
    $vk_scope = "wall,offline";
    $post_type = "";
    $target_type = "profile";
    $target_id = '';
    $vkontakte_link_categories = array();
    if($row->extra)
    {
        $vk_acc_extra = json_decode($row->extra, true);
        $post_type = $vk_acc_extra['post_type'];
        if(isset($vk_acc_extra['target_type']))
        {
            $target_type = $vk_acc_extra['target_type'];
        }
        if(isset($vk_acc_extra['target_id']))
        {
            $target_id = $vk_acc_extra['target_id'];
        }
        if(isset($vk_acc_extra['link_categories']))
        {
            $vkontakte_link_categories = $vk_acc_extra['link_categories'];
            $vkontakte_link_categories = json_decode($vkontakte_link_categories, true);
        }
    }

    $redirect_uri_vk = 'http://api.vk.com/blank.html';
    $authorize_url = "https://api.vk.com/oauth/authorize?client_id={$row->consumer_key}&redirect_uri={$redirect_uri_vk}&state=vkontakte_microblogposter_{$row->account_id}&scope={$vk_scope}&response_type=token";
?>
    <div style="display:none">
        <div id="update_account<?php echo $row->account_id;?>">
            <form id="update_account_form<?php echo $row->account_id;?>" method="post" action="" enctype="multipart/form-data" >

                <h3 class="new-account-header"><?php _e('<span class="microblogposter-name">MicroblogPoster</span> Plugin', 'microblog-poster');?></h3>
                <div class="delete-wrapper">
                    <?php _e('VKontakte Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span>
                </div>
                <div id="vkontakte-div" class="one-account">
                    <div class="help-div"><span class="description">VKontakte&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/vkontakte-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
                    <div class="input-div">
                        <?php _e('Username:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="username" name="username" value="<?php echo $row->username;?>"/>
                    </div>
                    <div class="input-div">
                        <?php _e('VKontakte target type:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">

                        <?php if($target_type=='page'):?>
                            <span class="mbp-vkontakte-target-type-span"><?php _e('Public Page wall', 'microblog-poster');?></span>
                        <?php elseif($target_type=='group'):?>
                            <span class="mbp-vkontakte-target-type-span"><?php _e('Group wall', 'microblog-poster');?></span>
                        <?php elseif($target_type=='event'):?>
                            <span class="mbp-vkontakte-target-type-span"><?php _e('Event wall', 'microblog-poster');?></span>
                        <?php else:?>
                            <span class="mbp-vkontakte-target-type-span"><?php _e('Profile wall', 'microblog-poster');?></span>
                        <?php endif;?>
                    </div>
                    <div class="input-div">

                        <?php if($target_type=='page'):?>
                            <?php _e('Page ID', 'microblog-poster');?>
                        <?php elseif($target_type=='group'):?>
                            <?php _e('Group ID', 'microblog-poster');?>
                        <?php elseif($target_type=='event'):?>
                            <?php _e('Event ID', 'microblog-poster');?>
                        <?php else:?>
                            <?php _e('Profile ID', 'microblog-poster');?>
                        <?php endif;?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="mbp_vkontakte_target_id" name="mbp_vkontakte_target_id" value="<?php echo $target_id;?>" />
                        <span class="description"> <?php echo ucfirst($target_type);?> ID. (<?php _e('numeric', 'microblog-poster');?>)</span>
                    </div>
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
                        <input type="radio" name="post_type_vk" value="text" <?php if($post_type=='text') echo 'checked'; ?>> <?php _e('Text', 'microblog-poster');?> - <span class="description"><?php _e('Text only status update.', 'microblog-poster');?></span><br>
                        <input type="radio" name="post_type_vk" value="link" <?php if($post_type=='link') echo 'checked'; ?>> <?php _e('Link', 'microblog-poster');?> - <span class="description"><?php _e('(Text message + VKontakte link box.)', 'microblog-poster');?></span>
                    </div>
                    <div class="input-div">

                    </div>
                    <div class="input-div-large">
                        <span class="description-small">
                            <?php _e('If you choose to post with link box, VKontakte is auto fetching an image from your post and add it to the link box.', 'microblog-poster');?>
                        </span>
                    </div>
                    <div class="mbp-separator"></div>
                    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
                        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories($vkontakte_link_categories);?>
                    <?php else:?>
                        <?php microblogposter_show_more_infos_category_driven();?>
                    <?php endif;?>
                    <div class="mbp-separator"></div>
                    <div class="input-div">
                        <?php _e('Application ID/API Key:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="consumer_key" value="<?php echo $row->consumer_key;?>" />
                        <span class="description">(Application ID)</span>
                    </div>
                    <div class="input-div">
                        <?php _e('Application Secret:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="consumer_secret" value="<?php echo $row->consumer_secret;?>" />
                        <span class="description">(Application Secret)</span>
                    </div>
                    <div class="input-div">
                        <?php _e('Access Token:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="access_token_vk" value="<?php echo $vk_acc_extra['access_token'];?>" />
                        <span class="description">(Access Token)</span>
                    </div>
                </div>

                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="vkontakte" />
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
                <?php _e('VKontakte Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span><br />
                <span class="delete-wrapper-del"><?php _e('Delete?', 'microblog-poster');?></span>
                </div>
                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="vkontakte" />
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
        <?php if(isset($vk_acc_extra['access_token']) && $vk_acc_extra['access_token']):?>
            <?php if($vk_acc_extra['expires'] == '0'):?>
                <div><?php _e('Authorization is valid permanently', 'microblog-poster');?></div>
                <div><a href="<?php echo $authorize_url; ?>" target="_blank"><?php _e('Re-Authorize this vkontakte account', 'microblog-poster');?></a></div>
            <?php else:?>
                <div><?php _e('Authorization is valid until', 'microblog-poster');?> <?php echo date('d-m-Y', $vk_acc_extra['expires']); ?></div>
                <div><a href="<?php echo $authorize_url; ?>" target="_blank"><?php _e('Refresh authorization now', 'microblog-poster');?></a></div>
            <?php endif;?>
        <?php else:?>
                <div><a href="<?php echo $authorize_url; ?>" target="_blank"><?php _e('Authorize this vkontakte account', 'microblog-poster');?></a></div>
        <?php endif;?>
    </div>

<?php endforeach;?>