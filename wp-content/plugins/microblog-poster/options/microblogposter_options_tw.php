<div class="social-network-accounts-site">
    <img src="<?php echo plugins_url('../images/twitter_icon.png', __FILE__);?>" />
    <h4><?php _e('Twitter Accounts', 'microblog-poster');?></h4>
</div>
<?php
$rows = MicroblogPoster_Poster::get_accounts_object('twitter');
foreach($rows as $row):
    $update_accounts[] = $row->account_id;

    $authorized = false;
    $include_featured_image = false;
    $twt_link_categories = array();
    if($row->consumer_key && $row->consumer_secret && $row->access_token && $row->access_token_secret)
    {
        $authorized = true;
    }
    elseif($row->extra)
    {
        $twt_acc_extra = json_decode($row->extra, true);
        if(isset($twt_acc_extra['authorized']) && $twt_acc_extra['authorized']=='1')
        {
            $authorized = true;
        }
        $include_featured_image = (isset($twt_acc_extra['include_featured_image']) && $twt_acc_extra['include_featured_image'] == 1)?true:false;
        if(isset($twt_acc_extra['link_categories']))
        {
            $twt_link_categories = $twt_acc_extra['link_categories'];
            $twt_link_categories = json_decode($twt_link_categories, true);
        }
    }


    $authorize_step = 1;
    $authorize_url = $redirect_uri.'&microblogposter_auth_twitter=1&account_id='.$row->account_id;
    $authorize_url_name = 'authorize_url_'.$row->account_id;
    if(isset($$authorize_url_name))
    {
        $authorize_url = $$authorize_url_name;
        $authorize_step = 2;
    }
?>
    <div style="display:none">
        <div id="update_account<?php echo $row->account_id;?>">
            <form id="update_account_form<?php echo $row->account_id;?>" method="post" action="" enctype="multipart/form-data" >

                <h3 class="new-account-header"><?php _e('<span class="microblogposter-name">MicroblogPoster</span> Plugin', 'microblog-poster');?></h3>
                <div class="delete-wrapper">
                    <?php _e('Twitter Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span>
                </div>
                <div id="twitter-div" class="one-account">
                    <div class="help-div"><span class="description"> Twitter&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/twitter-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
                    <div class="input-div">
                        <?php _e('Username:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="username" name="username" value="<?php echo $row->username;?>"/>
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
                        <span class="description-small"><?php echo $description_shortcodes_m;?></span>
                    </div>
                    <div class="mbp-separator"></div>
                    <div class="input-div">
                        <?php _e('Include featured image:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="checkbox" id="include_featured_image" name="include_featured_image" value="1" <?php if ($include_featured_image) echo "checked";?>/>
                        <span class="description">
                            <?php _e('Do you want to include featured image in your updates?', 'microblog-poster');?>
                            <?php if(!MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','filter_single_account')):?>
                                <a href="https://efficientscripts.com/web/products/addons" target="_blank"><?php _e('Upgrade Now', 'microblog-poster');?></a>
                            <?php endif;?>  
                        </span>
                    </div>
                    <div class="mbp-separator"></div>
                    <?php microblogposter_display_link_categories($twt_link_categories);?>
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
                    <div class="input-div">

                    </div>
                    <div class="input-div-large">
                        <span class="description-small">
                            <?php _e('The two fields below \'Access Token\' and \'Access Token Secret\' are either generated interactively or you provided them manually.', 'microblog-poster');?>&nbsp;
                            <?php _e('In any case these two fields are MANDATORY in order to successfully post to twitter.', 'microblog-poster');?>
                        </span>
                    </div>
                    <div class="input-div">
                        <?php _e('Access Token:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="access_token" value="<?php echo $row->access_token;?>" />
                        <span class="description">(Access Token)</span>
                    </div>
                    <div class="input-div">
                        <?php _e('Access Token Secret:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="access_token_secret" value="<?php echo $row->access_token_secret;?>" />
                        <span class="description">(Access Token Secret)</span>
                    </div>
                </div>

                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="twitter" />
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
                <?php _e('Twitter Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span><br />
                <span class="delete-wrapper-del"><?php _e('Delete?', 'microblog-poster');?></span>
                </div>
                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="twitter" />
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
                <a href="<?php echo $authorize_url; ?>" ><?php _e('Authorize this Twitter account', 'microblog-poster');?></a>
                <?php if($authorize_step==1) _e('(2 steps required)', 'microblog-poster');?>
                <?php if($authorize_step==2) _e('Final step, click once again.', 'microblog-poster');?>
            <?php endif;?>
        </div>
    </div>

<?php endforeach;?>