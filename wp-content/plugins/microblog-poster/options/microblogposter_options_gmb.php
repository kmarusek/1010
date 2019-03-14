<div class="social-network-accounts-site">
    <img src="<?php echo plugins_url('../images/googlemybusiness_icon.png', __FILE__);?>" />
    <h4><?php _e('Google My Business Accounts', 'microblog-poster');?></h4>
</div>  
<?php
$rows = MicroblogPoster_Poster::get_accounts_object('googlemybusiness');
foreach($rows as $row):
    $update_accounts[] = $row->account_id;
    $configure_accounts[] = $row->account_id;
    $refresh_url = $redirect_uri.'&googlemybusiness_account_id='.$row->account_id;
    $gmb_acc_extra = json_decode($row->extra, true);
    $gmb_link_categories = array();
    $gmb_accounts = array();
    if(is_array($gmb_acc_extra))
    {
        if(isset($gmb_acc_extra['accounts']))
        {
            $gmb_accounts = $gmb_acc_extra['accounts'];
        }
    }
    $client_id = '702601226893-oqkbt65mpchi8p3url8itq1cd3if4rsq.apps.googleusercontent.com';
    $state_gmb = urlencode($redirect_uri . "&mbp_gmb_" . $row->account_id);
    $gmb_redirect_uri = "https://efficientscripts.com/api/googleMyBusinessRedirect.php";
    $promtp_gmb = urlencode("select_account consent");
    
    $authorize_url = "https://accounts.google.com/o/oauth2/auth?client_id={$client_id}&redirect_uri={$gmb_redirect_uri}&state={$state_gmb}&scope=https://www.googleapis.com/auth/plus.business.manage&response_type=code&access_type=offline&prompt={$promtp_gmb}";

?>
    

    <div id="mbp_refresh_gmb_form_wrapper">
        <span class="configure-account configure<?php echo $row->account_id;?>"><?php _e('Add Locations', 'microblog-poster');?></span><br /><br />
        <?php $gmb_locations = MicroblogPoster_Poster::get_gmb_locations_object($row->account_id); ?>
        <?php if(!empty($gmb_locations)): ?>
            <?php foreach($gmb_locations as $gmb_account_locations): ?>
                <?php $gmb_account_locations_extra = json_decode($gmb_account_locations->extra, true); ?>
                <span><?php echo $gmb_account_locations->username; ?></span>
                <span class="edit-account edit<?php echo $gmb_account_locations->account_id;?>"><?php _e('Edit', 'microblog-poster');?></span>
                <span class="del-account del<?php echo $gmb_account_locations->account_id;?>"><?php _e('Del', 'microblog-poster');?></span><br />
                <div class="mbp-separator-small"></div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div style="display:none">
        <div id="configure_account<?php echo $row->account_id;?>">
            <form id="configure_account_form<?php echo $row->account_id;?>" method="post" action="" enctype="multipart/form-data" >
                <h3 class="new-account-header"><?php _e('<span class="microblogposter-name">MicroblogPoster</span> Plugin', 'microblog-poster');?></h3>
                <div class="delete-wrapper">
                    <?php _e('Google My Business Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span>
                </div>
                <div class="input-div">
                    <?php _e('Locations name:', 'microblog-poster');?>
                </div>
                <div class="input-div-large">
                    <input type="text" id="" name="username" value="" />
                </div>
                <div class="input-div">
                    <?php _e('Google account:', 'microblog-poster');?>
                </div>
                <div class="input-div-large">
                     <select name="gmb_account_name" id="gmb_account_name_<?php echo $row->account_id;?>">
                         <option value="a"><?php _e('(Select Account)', 'microblog-poster');?></option> 
                         
                         <?php if(!empty($gmb_accounts)): ?>
                            <?php foreach($gmb_accounts as $account): ?>
                                <option value="<?php echo $account['id'];?>"><?php echo $account['name']; ?> </option> 
                            <?php endforeach; ?>
                         <?php endif; ?>
                     </select>
                    <span class="description"></span>
                </div>
                <div class="input-div">
                    <?php _e('Locations:', 'microblog-poster');?>
                </div>
                <div class="input-div-large">
                     <select name="gmb_location_names[]" id="gmb_location_names" multiple>
                         
                     </select>
                    <span class="description"></span>
                </div>
                
                <div class="input-div">
                    <?php _e('Message Format:', 'microblog-poster');?>
                </div>
                <div class="input-div-large">
                    <textarea id="message_format" name="message_format" rows="2"></textarea>
                    <span class="description"><?php _e('Message that\'s actually posted.', 'microblog-poster');?></span>
                </div>
                <div class="input-div">

                </div>
                <div class="input-div-large">
                    <span class="description-small"><?php echo $description_shortcodes_bookmark;?></span>
                </div>
                <div class="mbp-separator"></div>
                <div class="input-div">
                    <?php _e('Include featured image:', 'microblog-poster');?>
                </div>
                <div class="input-div-large">
                    <input type="checkbox" id="include_featured_image" name="include_featured_image" value="1" />
                    <span class="description"><?php _e('Do you want to include featured image in your updates?', 'microblog-poster');?></span>
                </div>
                <div class="mbp-separator"></div>
                <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
                    <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories($gmb_link_categories);?>
                <?php else:?>
                    <?php microblogposter_show_more_infos_category_driven();?>
                <?php endif;?>
                
                
                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="googlemybusinessl" />
                <input type="hidden" name="new_account_hidden" value="1" />
                <div class="button-holder">
                    <button type="button" class="button cancel-account" ><?php _e('Cancel', 'microblog-poster');?></button>
                    <button type="button" class="button-primary configure-account<?php echo $row->account_id;?>" ><?php _e('Save', 'microblog-poster');?></button>
                </div>
            </form>
        </div>
    </div>

    <div style="display:none">
        <div id="update_account<?php echo $row->account_id;?>">
            <form id="update_account_form<?php echo $row->account_id;?>" method="post" action="" enctype="multipart/form-data" >
                <h3 class="new-account-header"><?php _e('<span class="microblogposter-name">MicroblogPoster</span> Plugin', 'microblog-poster');?></h3>
                <div class="delete-wrapper">
                    <?php _e('Google My Business Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span>
                </div>
                <div id="delicious-div" class="one-account">
                    <div class="input-div">
                        <?php _e('Username:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="username" value="<?php echo $row->username;?>" />
                    </div>
                </div>

                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="googlemybusiness" />
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
                <?php _e('Google My Business Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span><br />
                <span class="delete-wrapper-del"><?php _e('Delete?', 'microblog-poster');?></span>
                </div>
                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="googlemybusiness" />
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
        <span class="del-account del<?php echo $row->account_id;?>"><?php _e('Del', 'microblog-poster');?></span><br />
        <?php if(!MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','handle_manual_post')):?>
            
            <a><?php _e('Refresh verified locations', 'microblog-poster');?></a><br /><br />
            <?php if(isset($gmb_acc_extra['refresh_token']) && $gmb_acc_extra['refresh_token']):?>
                <div><?php _e('Authorization is valid permanently', 'microblog-poster');?></div>
                <div><a><?php _e('Re-Authorize this Google My Business account', 'microblog-poster');?></a></div>
            <?php else:?>
                <a><?php _e('Authorize this Google My Business account', 'microblog-poster');?></a><br />
                <?php _e('Available with the Enterprise Add-on.', 'microblog-poster');?>
                <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','filter_single_account')):?>
                <a href="https://efficientscripts.com/web/login" target="_blank"><?php _e('Upgrade Now', 'microblog-poster');?></a>
                <?php else:?>
                <a href="https://efficientscripts.com/web/products/addons" target="_blank"><?php _e('Upgrade Now', 'microblog-poster');?></a>
                <?php endif;?>
            <?php endif;?>
                
        <?php else:?>
                
            <a href="<?php echo $refresh_url; ?>" ><?php _e('Refresh verified locations', 'microblog-poster');?></a><br /><br />
            <?php if(isset($gmb_acc_extra['refresh_token']) && $gmb_acc_extra['refresh_token']):?>
                <div><?php _e('Authorization is valid permanently', 'microblog-poster');?></div>
                <div><a href="<?php echo $authorize_url; ?>" ><?php _e('Re-Authorize this Google My Business account', 'microblog-poster');?></a></div>
            <?php else:?>
                <div><a href="<?php echo $authorize_url; ?>" ><?php _e('Authorize this Google My Business account', 'microblog-poster');?></a></div>
            <?php endif;?>
                
        <?php endif;?>
    </div>
<?php endforeach;?>

<?php
$rows = MicroblogPoster_Poster::get_accounts_object('googlemybusinessl');
foreach($rows as $row):
    $update_accounts[] = $row->account_id;
    $main_account = MicroblogPoster_Poster::get_account_object($row->consumer_key);
    
    $main_extra = json_decode($main_account->extra, true);
    $gmbl_acc_extra = json_decode($row->extra, true);
    $gmb_link_categories = array();
    if(is_array($gmbl_acc_extra))
    {
        if(isset($gmbl_acc_extra['link_categories']))
        {
            $gmb_link_categories = $gmbl_acc_extra['link_categories'];
            $gmb_link_categories = json_decode($gmb_link_categories, true);
        }
        $include_featured_image = (isset($gmbl_acc_extra['include_featured_image']) && $gmbl_acc_extra['include_featured_image'] == 1)?true:false;
    }
    $sql="SELECT * FROM $table_accounts WHERE account_id = %d LIMIT 1";
    $rows = $wpdb->get_results($wpdb->prepare($sql, $row->consumer_key));
    $current_account = $rows[0];
    $extra = json_decode($current_account->extra, true);
    $locations = array();
    if(isset($extra['accounts']) && !empty($extra['accounts']))
    {
        foreach($extra['accounts'] as $account)
        {
            if($account['id'] == $gmbl_acc_extra['account_id'])
            {
                foreach($account['locations'] as $location)
                {
                    $locations[] = array("id"=>$location['id'], "name"=>$location['name']);
                }
            }
        }
    }
?>
    <div style="display:none">
        <div id="update_account<?php echo $row->account_id;?>">
            <form id="update_account_form<?php echo $row->account_id;?>" method="post" action="" enctype="multipart/form-data" >
                <h3 class="new-account-header"><?php _e('<span class="microblogposter-name">MicroblogPoster</span> Plugin', 'microblog-poster');?></h3>
                <div class="delete-wrapper">
                    <?php _e('GMB Locations Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span>
                </div>
                <div id="delicious-div" class="one-account">
                    <div class="input-div">
                        <?php _e('Locations name:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="username" value="<?php echo $row->username;?>" />
                    </div>
                    <div class="input-div">
                        <?php _e('Google account:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="" name="gmb_account_name" value="<?php echo $main_extra['accountsQuickAccess'][$gmbl_acc_extra['account_id']];?>" disabled='disabled' />
                    </div>
                    <div class="input-div">
                        <?php _e('Locations:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                         <select name="gmb_location_names[]" id="gmb_location_names" multiple>
                             <?php if(!empty($locations)): ?>
                                <?php foreach($locations as $location): ?>
                                    <option value="<?php echo $location['id'];?>" <?php if(in_array($location['id'],$gmbl_acc_extra['locations'])) {echo "selected";}?>><?php echo $location['name']; ?> </option> 
                                <?php endforeach; ?>
                             <?php endif; ?>
                         </select>
                        <span class="description"></span>
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
                        <span class="description-small"><?php echo $description_shortcodes_bookmark;?></span>
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
                        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories($gmb_link_categories);?>
                    <?php else:?>
                        <?php microblogposter_show_more_infos_category_driven();?>
                    <?php endif;?>
                </div>

                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="main_account_id" value="<?php echo $main_account->account_id;?>" />
                <input type="hidden" name="account_type" value="googlemybusinessl" />
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
                <?php _e('Google Locations Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span><br />
                <span class="delete-wrapper-del"><?php _e('Delete?', 'microblog-poster');?></span>
                </div>
                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="googlemybusinessl" />
                <input type="hidden" name="delete_account_hidden" value="1" />
                <div class="button-holder-del">
                    <button type="button" class="button cancel-account" ><?php _e('Cancel', 'microblog-poster');?></button>
                    <button type="button" class="del-account-fb button-primary del-account<?php echo $row->account_id;?>" ><?php _e('Delete', 'microblog-poster');?></button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach;?>