<div class="social-network-accounts-site">
    <img src="<?php echo plugins_url('../images/pinterest_icon.png', __FILE__);?>" />
    <h4><?php _e('Pinterest Accounts', 'microblog-poster');?></h4>
</div>
<?php
$rows = MicroblogPoster_Poster::get_accounts_object('pinterest');
foreach($rows as $row):
    $update_accounts[] = $row->account_id;

    $authorized = false;
    $pinterest_link_categories = array();
    if($row->extra)
    {
        $pinterest_acc_extra = json_decode($row->extra, true);
        if(isset($pinterest_acc_extra['access_token']))
        {
            $authorized = true;
        }
        if(isset($pinterest_acc_extra['board_name']))
        {
            $pinterest_board_name = $pinterest_acc_extra['board_name'];
        }
        if(isset($pinterest_acc_extra['user_name']))
        {
            $pinterest_user_name = $pinterest_acc_extra['user_name'];
        }
        else
        {
            $pinterest_user_name = $row->username;
        }
        if(isset($pinterest_acc_extra['link_categories']))
        {
            $pinterest_link_categories = $pinterest_acc_extra['link_categories'];
            $pinterest_link_categories = json_decode($pinterest_link_categories, true);
        }
    }

    $authorize_url = "https://api.pinterest.com/oauth/?response_type=code&client_id={$row->consumer_key}&redirect_uri={$redirect_uri_pinterest}&state=pinterest_microblogposter_{$row->account_id}&scope=read_public,write_public";
?>
    <div style="display:none">
        <div id="update_account<?php echo $row->account_id;?>">
            <form id="update_account_form<?php echo $row->account_id;?>" method="post" action="" enctype="multipart/form-data" >

                <h3 class="new-account-header"><?php _e('<span class="microblogposter-name">MicroblogPoster</span> Plugin', 'microblog-poster');?></h3>
                <div class="delete-wrapper">
                    <?php _e('Pinterest Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span>
                </div>
                <div id="blogger-div" class="one-account">
                    <div class="help-div"><span class="description">Pinterest&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/pinterest-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
                    <div class="input-div">
                        <?php _e('Username:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="username" name="username" value="<?php echo $row->username;?>"/>
                        <span class="description"><?php echo $description_mandatory_username;?></span>
                    </div>
                    <div class="input-div">
                        <?php _e('Username:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="mbp_pinterest_user_name" name="mbp_pinterest_user_name" value="<?php echo $pinterest_user_name;?>"/>
                        <span class="description">(Pinterest Username) <?php _e('Example:', 'microblog-poster');?> 'eddylafarge'</span>
                    </div>
                    <div class="input-div">
                        <?php _e('Board Name:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="mbp_pinterest_board_name" name="mbp_pinterest_board_name" value="<?php echo $pinterest_board_name;?>"/>
                        <span class="description">(Pinterest Board Name) <?php _e('Example:', 'microblog-poster');?> 'ideas-for-the-house'</span>
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
                    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
                        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories($pinterest_link_categories);?>
                    <?php else:?>
                        <?php microblogposter_show_more_infos_category_driven();?>
                    <?php endif;?>
                    <div class="mbp-separator"></div>
                    <div class="input-div">
                        <?php _e('Client Id:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="consumer_key" value="<?php echo $row->consumer_key;?>" />
                        <span class="description">(Client Id)</span>
                    </div>
                    <div class="input-div">
                        <?php _e('Client Secret:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="consumer_secret" value="<?php echo $row->consumer_secret;?>" />
                        <span class="description">(Client Secret)</span>
                    </div>
                </div>

                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="blogger" />
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
                <?php _e('Pinterest Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span><br />
                <span class="delete-wrapper-del"><?php _e('Delete?', 'microblog-poster');?></span>
                </div>
                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="blogger" />
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
        <div>

            <?php if($authorized): ?>
                <div><?php _e('Authorization is valid permanently', 'microblog-poster');?></div>
                <a href="<?php echo $authorize_url; ?>" ><?php _e('Refresh authorization now', 'microblog-poster');?></a>
            <?php else:?>
                <a href="<?php echo $authorize_url; ?>" ><?php _e('Authorize this Pinterest account', 'microblog-poster');?></a>
            <?php endif;?>

        </div>
    </div>

<?php endforeach;?>