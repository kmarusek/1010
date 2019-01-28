<div class="social-network-accounts-site">
    <img src="<?php echo plugins_url('../images/plurk_icon.png', __FILE__);?>" />
    <h4><?php _e('Plurk Accounts', 'microblog-poster');?></h4>
</div>
<?php
$rows = MicroblogPoster_Poster::get_accounts_object('plurk');
foreach($rows as $row):
    $update_accounts[] = $row->account_id;
    $plurk_qualifier = "says";
    $extra = json_decode($row->extra, true);
    $plurk_link_categories = array();
    if(is_array($extra))
    {
        if(isset($extra['qualifier']))
        {
            $plurk_qualifier = $extra['qualifier'];
        }
        if(isset($extra['link_categories']))
        {
            $plurk_link_categories = $extra['link_categories'];
            $plurk_link_categories = json_decode($plurk_link_categories, true);
        }
    }
?>
    <div style="display:none">
        <div id="update_account<?php echo $row->account_id;?>">
            <form id="update_account_form<?php echo $row->account_id;?>" method="post" action="" enctype="multipart/form-data" >
                <h3 class="new-account-header"><?php _e('<span class="microblogposter-name">MicroblogPoster</span> Plugin', 'microblog-poster');?></h3>
                <div class="delete-wrapper">
                    <?php _e('Plurk Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span>
                </div>
                <div id="plurk-div" class="one-account">
                    <div class="help-div"><span class="description">Plurk&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/plurk-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
                    <div class="input-div">
                        <?php _e('Username:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <input type="text" id="username" name="username" value="<?php echo $row->username;?>"/>
                    </div>
                    <div class="input-div">
                        <?php _e('Qualifier:', 'microblog-poster');?>
                    </div>
                    <div class="input-div-large">
                        <select name="mbp_plurk_qualifier">
                            <option value="loves" <?php if($plurk_qualifier=='loves') echo 'selected="selected";'?>>loves</option>
                            <option value="likes" <?php if($plurk_qualifier=='likes') echo 'selected="selected";'?>>likes</option>
                            <option value="shares" <?php if($plurk_qualifier=='shares') echo 'selected="selected";'?>>shares</option>
                            <option value="gives" <?php if($plurk_qualifier=='gives') echo 'selected="selected";'?>>gives</option>
                            <option value="hates" <?php if($plurk_qualifier=='hates') echo 'selected="selected";'?>>hates</option>
                            <option value="wants" <?php if($plurk_qualifier=='wants') echo 'selected="selected";'?>>wants</option>
                            <option value="has" <?php if($plurk_qualifier=='has') echo 'selected="selected";'?>>has</option>
                            <option value="will" <?php if($plurk_qualifier=='will') echo 'selected="selected";'?>>will</option>
                            <option value="asks" <?php if($plurk_qualifier=='asks') echo 'selected="selected";'?>>asks</option>
                            <option value="wishes" <?php if($plurk_qualifier=='wishes') echo 'selected="selected";'?>>wishes</option>
                            <option value="was" <?php if($plurk_qualifier=='was') echo 'selected="selected";'?>>was</option>
                            <option value="feels" <?php if($plurk_qualifier=='feels') echo 'selected="selected";'?>>feels</option>
                            <option value="thinks" <?php if($plurk_qualifier=='thinks') echo 'selected="selected";'?>>thinks</option>
                            <option value="says" <?php if($plurk_qualifier=='says') echo 'selected="selected";'?>>says</option>
                            <option value="is" <?php if($plurk_qualifier=='is') echo 'selected="selected";'?>>is</option>
                            <option value=":" <?php if($plurk_qualifier==':') echo 'selected="selected";'?>>:</option>
                            <option value="freestyle" <?php if($plurk_qualifier=='freestyle') echo 'selected="selected";'?>>freestyle</option>
                            <option value="hopes" <?php if($plurk_qualifier=='hopes') echo 'selected="selected";'?>>hopes</option>
                            <option value="needs" <?php if($plurk_qualifier=='needs') echo 'selected="selected";'?>>needs</option>
                            <option value="wonders" <?php if($plurk_qualifier=='wonders') echo 'selected="selected";'?>>wonders</option>
                        </select>
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
                    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
                        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories($plurk_link_categories);?>
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
                <input type="hidden" name="account_type" value="plurk" />
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
                <?php _e('Plurk Account:', 'microblog-poster');?> <span class="delete-wrapper-user"><?php echo $row->username;?></span><br />
                <span class="delete-wrapper-del"><?php _e('Delete?', 'microblog-poster');?></span>
                </div>
                <input type="hidden" name="account_id" value="<?php echo $row->account_id;?>" />
                <input type="hidden" name="account_type" value="plurk" />
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