<div class="social-network-accounts-site">
    <img src="<?php echo plugins_url('../images/buffer_icon.png', __FILE__);?>" />
    <h4><?php _e('Buffer Accounts', 'microblog-poster');?></h4>
</div>  
<?php
$rows = MicroblogPoster_Poster::get_accounts_object('buffer');
foreach($rows as $row):
    $update_accounts[] = $row->account_id;
    $refresh_url = $redirect_uri.'&buffer_account_id='.$row->account_id;
    $is_raw = MicroblogPoster_SupportEnc::is_enc($row->extra);
    $extra = json_decode($row->extra, true);
    if(is_array($extra))
    {
        $api_key = $extra['api_key'];
    }
    if(is_array($extra['google']))
    {
        $gpb_count = $extra['google']['google_accounts'];
    }
    else
    {
        $gpb_count = 0;
    }
    if(is_array($extra['facebook']))
    {
        $fbb_count = $extra['facebook']['facebook_accounts'];
    }
    else
    {
        $fbb_count = 0;
    }
?>

    <div id="mbp_refresh_buffer_form_wrapper">
        <a href="<?php echo $refresh_url; ?>" ><?php _e('Refresh available accounts', 'microblog-poster');?></a><br />
        <span><?php echo "Google+ -> ($gpb_count)";?></span><br />
        <span><?php echo "Facebook -> ($fbb_count)";?></span>
    </div>

    <div style="display:none">
        <div id="update_account<?php echo $row->account_id;?>">
            <form id="update_account_form<?php echo $row->account_id;?>" method="post" action="" enctype="multipart/form-data" >
                <h3 class="new-account-header"><?php _e('<span class="microblogposter-name">MicroblogPoster</span> Plugin', 'microblog-poster');?></h3>
                <div class="delete-wrapper">
                    <?php _e('Buffer Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span>
                </div>
                <div id="buffer-div" class="one-account">
                    <div class="help-div"><span class="description">Buffer&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/buffer-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
                    <div class="input-div">
                        <?php _e('Username:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="username" value="<?php echo $row->username;?>" />
                    </div>
                    <div class="input-div">
                        <?php _e('API Key:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="api_key" value="<?php echo $api_key;?>" />
                        <span class="description">(API Key)</span>
                    </div>
                </div>

                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="buffer" />
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
                <?php _e('Buffer Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span><br />
                <span class="delete-wrapper-del"><?php _e('Delete?', 'microblog-poster');?></span>
                </div>
                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="buffer" />
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
    </div>

<?php endforeach;?>