<div class="social-network-accounts-site">
    <img src="<?php echo plugins_url('../images/wordpress_icon.png', __FILE__);?>" />
    <h4><?php _e('Wordpress Accounts', 'microblog-poster');?></h4>
</div>  
<?php
$rows = MicroblogPoster_Poster::get_accounts_object('wordpress');
foreach($rows as $row):
    $update_accounts[] = $row->account_id;
    $is_raw = MicroblogPoster_SupportEnc::is_enc($row->extra);
    $wp_acc_extra = json_decode($row->extra, true);
    $wp_link_categories = array();
    if(is_array($wp_acc_extra))
    {
        $include_tags = (isset($wp_acc_extra['include_tags']) && $wp_acc_extra['include_tags'] == 1)?true:false;
        $wordpress_blog_url = $wp_acc_extra['blog_url'];
        if(isset($wp_acc_extra['link_categories']))
        {
            $wp_link_categories = $wp_acc_extra['link_categories'];
            $wp_link_categories = json_decode($wp_link_categories, true);
        }
    }
?>
    <div style="display:none">
        <div id="update_account<?php echo $row->account_id;?>">
            <form id="update_account_form<?php echo $row->account_id;?>" method="post" action="" enctype="multipart/form-data" >
                <h3 class="new-account-header"><?php _e('<span class="microblogposter-name">MicroblogPoster</span> Plugin', 'microblog-poster');?></h3>
                <div class="delete-wrapper">
                    <?php _e('Wordpress Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span>
                </div>
                <div id="wordpress-div" class="one-account">
                    <div class="input-div">
                        <?php _e('Blog Url:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="mbp_wordpress_blog_url" name="mbp_wordpress_blog_url" value="<?php echo $wordpress_blog_url;?>" />
                    </div>
                    <div class="input-div">

                    </div>
                    <div class="input-div-large">
                        <span class="description-small">
                            <strong><?php _e('Example:', 'microblog-poster');?></strong> http://www.yourblog.com/xmlrpc.php [<strong>wordpress</strong>]<br />
                            https://youruserame.wordpress.com/xmlrpc.php [<strong>wordpress.com</strong>]<br />
                            http://yourusername.blog.com/xmlrpc.php [<strong>blog.com</strong>]
                        </span>
                    </div>
                    <div class="input-div">
                        <?php _e('Username:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="username" value="<?php echo $row->username;?>" />
                    </div>
                    <div class="input-div">
                        <?php _e('Password:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="password" value="<?php echo ($is_raw)? $row->password : MicroblogPoster_SupportEnc::dec($row->password);?>" />
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
                    <div class="input-div">
                        <?php _e('Include tags:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="checkbox" id="include_tags" name="include_tags" value="1" <?php if ($include_tags) echo "checked";?>/>
                        <span class="description"><?php _e('Do you want to include tags in the bookmarks?', 'microblog-poster');?></span>
                    </div>
                    <div class="mbp-separator"></div>
                    <?php microblogposter_display_link_categories($wp_link_categories);?>
                </div>

                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="wordpress" />
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
                <?php _e('Wordpress Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span><br />
                <span class="delete-wrapper-del"><?php _e('Delete?', 'microblog-poster');?></span>
                </div>
                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="wordpress" />
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