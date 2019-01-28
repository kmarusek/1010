<div class="social-network-accounts-site">
    <img src="<?php echo plugins_url('../images/blogger_icon.png', __FILE__);?>" />
    <h4><?php _e('Blogger Accounts', 'microblog-poster');?></h4>
</div>
<?php
$rows = MicroblogPoster_Poster::get_accounts_object('blogger');
foreach($rows as $row):
    $update_accounts[] = $row->account_id;

    $authorized = false;
    $blogg_link_categories = array();
    if($row->extra)
    {
        $blogg_acc_extra = json_decode($row->extra, true);
        if(isset($blogg_acc_extra['refresh_token']))
        {
            $authorized = true;
        }
        if(isset($blogg_acc_extra['blog_id']))
        {
            $blogg_blog_id = $blogg_acc_extra['blog_id'];
        }
        $include_featured_image = (isset($blogg_acc_extra['include_featured_image']) && $blogg_acc_extra['include_featured_image'] == 1)?true:false;
        if(isset($blogg_acc_extra['link_categories']))
        {
            $blogg_link_categories = $blogg_acc_extra['link_categories'];
            $blogg_link_categories = json_decode($blogg_link_categories, true);
        }
    }

    $authorize_url = "https://accounts.google.com/o/oauth2/auth?response_type=code&client_id={$row->consumer_key}&redirect_uri={$redirect_uri}&state=blogger_microblogposter_{$row->account_id}&scope=http://www.blogger.com/feeds/&access_type=offline";
?>
    <div style="display:none">
        <div id="update_account<?php echo $row->account_id;?>">
            <form id="update_account_form<?php echo $row->account_id;?>" method="post" action="" enctype="multipart/form-data" >

                <h3 class="new-account-header"><?php _e('<span class="microblogposter-name">MicroblogPoster</span> Plugin', 'microblog-poster');?></h3>
                <div class="delete-wrapper">
                    <?php _e('Blogger Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span>
                </div>
                <div id="blogger-div" class="one-account">
                    <div class="help-div"><span class="description">Blogger/Blogspot&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/blogger-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
                    <div class="input-div">
                        <?php _e('Username:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="username" name="username" value="<?php echo $row->username;?>"/>
                        <span class="description"><?php _e('Easily identify it later, not used for posting.', 'microblog-poster');?></span>
                    </div>
                    <div class="input-div">
                        <?php _e('Blog Id:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="mbp_blogger_blog_id" name="mbp_blogger_blog_id" value="<?php echo $blogg_blog_id;?>"/>
                        <span class="description"><?php _e('Example:', 'microblog-poster');?> '1237342953579224633'</span>
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
                    <div class="input-div">
                        <?php _e('Include featured image:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="checkbox" id="include_featured_image" name="include_featured_image" value="1" <?php if ($include_featured_image) echo "checked";?>/>
                        <span class="description"><?php _e('Do you want to include featured image in your updates?', 'microblog-poster');?></span>
                    </div>
                    <div class="mbp-separator"></div>
                    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
                        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories($blogg_link_categories);?>
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
                <?php _e('Blogger Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span><br />
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
                <a href="<?php echo $authorize_url; ?>" ><?php _e('Authorize this Blogger account', 'microblog-poster');?></a>
            <?php endif;?>

        </div>
    </div>

<?php endforeach;?>