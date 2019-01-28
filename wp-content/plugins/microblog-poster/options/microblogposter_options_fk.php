<div class="social-network-accounts-site">
    <img src="<?php echo plugins_url('../images/flickr_icon.png', __FILE__);?>" />
    <h4><?php _e('Flickr Accounts', 'microblog-poster');?></h4>
</div>
<?php
$rows = MicroblogPoster_Poster::get_accounts_object('flickr');
foreach($rows as $row):
    $update_accounts[] = $row->account_id;

    $authorized = false;
    $include_tags = false;
    $flickr_link_categories = array();
    if($row->extra)
    {
        $flc_acc_extra = json_decode($row->extra, true);

        if(isset($flc_acc_extra['album_id']))
        {
            $flc_album_id = $flc_acc_extra['album_id'];
        }
        if(isset($flc_acc_extra['authorized']) && $flc_acc_extra['authorized']=='1')
        {
            $authorized = true;
        }
        if(isset($flc_acc_extra['link_categories']))
        {
            $flickr_link_categories = $flc_acc_extra['link_categories'];
            $flickr_link_categories = json_decode($flickr_link_categories, true);
        }
    }

    $authorize_step = 1;
    $authorize_url = $redirect_uri.'&microblogposter_auth_flickr=1&account_id='.$row->account_id;
    $authorize_url_name = 'authorize_url_'.$row->account_id;
    if(isset($$authorize_url_name))
    {
        $authorize_url = $$authorize_url_name;
        $authorize_step = 2;
    }
    if(is_array($flc_acc_extra))
    {
        $include_tags = (isset($flc_acc_extra['include_tags']) && $flc_acc_extra['include_tags'] == 1)?true:false;
    }
?>
    <div style="display:none">
        <div id="update_account<?php echo $row->account_id;?>">
            <form id="update_account_form<?php echo $row->account_id;?>" method="post" action="" enctype="multipart/form-data" >

                <h3 class="new-account-header"><?php _e('<span class="microblogposter-name">MicroblogPoster</span> Plugin', 'microblog-poster');?></h3>
                <div class="delete-wrapper">
                    <?php _e('Flickr Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span>
                </div>
                <div id="flickr-div" class="one-account">
                    <div class="help-div"><span class="description"> Flickr&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/flickr-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
                    <div class="input-div">
                        <?php _e('Username:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="username" name="username" value="<?php echo $row->username;?>"/>
                    </div>
                    <div class="input-div">
                        <?php _e('Album Id:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="mbp_flickr_album_id" name="mbp_flickr_album_id" value="<?php  if(isset($flc_album_id)){echo $flc_album_id;}?>"/>
                        <span class="description"><?php _e('Example:', 'microblog-poster');?> '1237342953579224633'</span>
                    </div>
                    <div class="mbp-separator"></div>
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
                    <div class="input-div">
                        <?php _e('Include tags:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="checkbox" id="include_tags" name="include_tags" value="1" <?php if ($include_tags) echo "checked";?>/>
                        <span class="description"><?php _e('Do you want to include tags in the bookmarks?', 'microblog-poster');?></span>
                    </div>
                    <div class="mbp-separator"></div>
                    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
                        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories($flickr_link_categories);?>
                    <?php else:?>
                        <?php microblogposter_show_more_infos_category_driven();?>
                    <?php endif;?>
                    <div class="mbp-separator"></div>
                    <div class="input-div">
                        <?php _e('Consumer Key:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="consumer_key" value="<?php echo $row->consumer_key;?>" />
                        <span class="description">(Application Consumer Key)</span>
                    </div>
                    <div class="input-div">
                        <?php _e('Consumer Secret:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="consumer_secret" value="<?php echo $row->consumer_secret;?>" />
                        <span class="description">(Application Consumer Secret)</span>
                    </div>
                </div>

                <input type="hidden" name="access_token" value="<?php echo $row->access_token;?>" />
                <input type="hidden" name="access_token_secret" value="<?php echo $row->access_token_secret;?>" />
                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="flickr" />
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
                <?php _e('Flickr Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span><br />
                <span class="delete-wrapper-del"><?php _e('Delete?', 'microblog-poster');?></span>
                </div>
                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="flickr" />
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
                <?php _e('(2 steps required)', 'microblog-poster');?>
            <?php else:?>
                <a href="<?php echo $authorize_url; ?>" ><?php _e('Authorize this Flickr account', 'microblog-poster');?></a>
                <?php if($authorize_step==1) _e('(2 steps required)', 'microblog-poster');?>
                <?php if($authorize_step==2) _e('Final step, click once again.', 'microblog-poster');?>
            <?php endif;?>
        </div>
    </div>
<?php endforeach;?>