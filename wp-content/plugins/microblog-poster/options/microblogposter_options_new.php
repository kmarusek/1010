<div id="twitter-div" class="one-account">
    <div class="help-div"><span class="description"> Twitter&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/twitter-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" name="username" />
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
        <span class="description-small"><?php echo $description_shortcodes_m;?></span>
    </div>
    <div class="mbp-separator"></div>
    <div class="input-div">
        <?php _e('Include featured image:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="checkbox" id="include_featured_image" name="include_featured_image" value="1" />
        <span class="description">
            <?php _e('Do you want to include featured image in your updates?', 'microblog-poster');?>
            <?php if(!MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Pro','filter_single_account')):?>
                <a href="https://efficientscripts.com/web/products/addons" target="_blank"><?php _e('Upgrade Now', 'microblog-poster');?></a>
            <?php endif;?>  
        </span>
    </div>
    <div class="mbp-separator"></div>
    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
    <?php else:?>
        <?php microblogposter_show_more_infos_category_driven();?>
    <?php endif;?>
    <div class="mbp-separator"></div>
    <div class="input-div">
        <?php _e('Consumer Key:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="consumer_key" value="" />
        <span class="description">(Application Consumer Key)</span>
    </div>
    <div class="input-div">
        <?php _e('Consumer Secret:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="consumer_secret" value="" />
        <span class="description">(Application Consumer Secret)</span>
    </div>
    <div class="input-div">

    </div>
    <div class="input-div-large">
        <span class="description-small">
            <?php _e('Leave the fields \'Access Token\' and \'Access Token Secret\' below blank if you want to authorize your account interactively.', 'microblog-poster');?>
            <?php _e('If you provide them, your account will be ready to post immediately and you won\'t have to authorize interactively.', 'microblog-poster');?>
            <?php _e('Not providing these two fields is meant to allow you posting to multiple twitter accounts with a single twitter App. ', 'microblog-poster');?>
            <?php _e('You then authorize each account interactively against your App.', 'microblog-poster');?>
        </span>
    </div>
    <div class="input-div">
        <?php _e('Access Token:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="access_token" value="" />
        <span class="description"><?php _e('Optional.', 'microblog-poster');?> (Access Token)</span>
    </div>
    <div class="input-div">
        <?php _e('Access Token Secret:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="access_token_secret" value="" />
        <span class="description"><?php _e('Optional.', 'microblog-poster');?> (Access Token Secret)</span>
    </div>
</div>
<div id="plurk-div" class="one-account">
    <div class="help-div"><span class="description">Plurk&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/plurk-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" name="username" value="" />
    </div>
    <div class="input-div">
        <?php _e('Qualifier:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <select name="mbp_plurk_qualifier">
            <option value="loves">loves</option>
            <option value="likes">likes</option>
            <option value="shares">shares</option>
            <option value="gives">gives</option>
            <option value="hates">hates</option>
            <option value="wants">wants</option>
            <option value="has">has</option>
            <option value="will">will</option>
            <option value="asks">asks</option>
            <option value="wishes">wishes</option>
            <option value="was">was</option>
            <option value="feels">feels</option>
            <option value="thinks">thinks</option>
            <option value="says" selected="selected">says</option>
            <option value="is">is</option>
            <option value=":">:</option>
            <option value="freestyle">freestyle</option>
            <option value="hopes">hopes</option>
            <option value="needs">needs</option>
            <option value="wonders">wonders</option>
        </select>
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
        <span class="description-small"><?php echo $description_shortcodes_m;?></span>
    </div>
    <div class="mbp-separator"></div>
    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
    <?php else:?>
        <?php microblogposter_show_more_infos_category_driven();?>
    <?php endif;?>
    <div class="mbp-separator"></div>
    <div class="input-div">
        <?php _e('Consumer Key:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="consumer_key" value="" />
        <span class="description">(Application Consumer Key)</span>
    </div>
    <div class="input-div">
        <?php _e('Consumer Secret:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="consumer_secret" value="" />
        <span class="description">(Application Consumer Secret)</span>
    </div>
    <div class="input-div">
        <?php _e('Access Token:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="access_token" value="" />
        <span class="description">(Access Token)</span>
    </div>
    <div class="input-div">
        <?php _e('Access Token Secret:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="access_token_secret" value="" />
        <span class="description">(Access Token Secret)</span>
    </div>
</div>
<div id="friendfeed-div" class="one-account">
    <div class="help-div"><span class="description">FriendFeed&nbsp;:&nbsp;<a href="https://efficientscripts.com/help/microblogposter/friendfeedhelp" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" name="username" value="" />
    </div>
    <div class="input-div">
        <?php _e('Remote Key:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="password" value="" />
        <span class="description">(Remote Key)</span>
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
        <span class="description-small"><?php echo $description_shortcodes_m_ff;?></span>
    </div>
    <div class="mbp-separator"></div>
    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
    <?php else:?>
        <?php microblogposter_show_more_infos_category_driven();?>
    <?php endif;?>
</div>
<div id="delicious-div" class="one-account">
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" name="username" value="" />
    </div>
    <div class="input-div">
        <?php _e('Password:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="password" value="" />
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
    <div class="input-div">
        <?php _e('Include tags:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="checkbox" id="include_tags" name="include_tags" value="1"/>
        <span class="description"><?php _e('Do you want to include tags in the bookmarks?', 'microblog-poster');?></span>
    </div>
    <div class="mbp-separator"></div>
    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
    <?php else:?>
        <?php microblogposter_show_more_infos_category_driven();?>
    <?php endif;?>
</div>
<div id="facebook-div" class="one-account">
    <div class="help-div"><span class="description">Facebook&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/facebook-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" name="username" value="" />
        <span class="description"><?php echo $description_mandatory_username;?></span>
    </div>
    <div class="input-div">
        <?php _e('Facebook target type:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <select name="mbp_facebook_target_type" id="mbp_facebook_target_type">
            <!--option value="profile"><?php _e('Profile timeline', 'microblog-poster');?></option-->
            <option value="page"><?php _e('Page timeline', 'microblog-poster');?></option>
            <option value="group"><?php _e('Group timeline', 'microblog-poster');?></option>
        </select>
        <span class="description"><?php _e('Where you want to auto post.', 'microblog-poster');?></span>
    </div>
    <div id="mbp-facebook-input-div">
        <div id="mbp-facebook-page-id-div">
            <div class="input-div">
                <?php _e('Page ID:', 'microblog-poster');?>
            </div>
            <div class="input-div-large">
                <input type="text" id="mbp_facebook_page_id" name="mbp_facebook_page_id" value="" />
                <span class="description"><?php _e('Your Facebook Page ID. (numeric)', 'microblog-poster');?></span>
            </div>
        </div>
        <div id="mbp-facebook-group-id-div">
            <div class="input-div">
                <?php _e('Group ID:', 'microblog-poster');?>
            </div>
            <div class="input-div-large">
                <input type="text" id="mbp_facebook_group_id" name="mbp_facebook_group_id" value="" />
                <span class="description"><?php _e('Your Facebook Group ID. (numeric)', 'microblog-poster');?></span>
            </div>
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
            <span class="description-small"><?php echo $description_shortcodes;?></span>
        </div>
        <div class="mbp-separator"></div>
        <div class="input-div input-div-radio">
            <?php _e('Post Type:', 'microblog-poster');?>
        </div>
        <div class="input-div-large">
            <input type="radio" name="post_type_fb" value="text" checked="checked"> <?php _e('Text', 'microblog-poster');?> <span class="description"><?php _e('(Text only status update.)', 'microblog-poster');?></span><br>
            <input type="radio" name="post_type_fb" value="link"> <?php _e('Link', 'microblog-poster');?> <span class="description"><?php _e('(Text message + Facebook link box.)', 'microblog-poster');?></span>
        </div>
        <div class="input-div">

        </div>
        <div class="input-div-large">
            <span class="description-small">

            </span>
        </div>
        <div class="mbp-separator"></div>
        <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
            <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
        <?php else:?>
            <?php microblogposter_show_more_infos_category_driven();?>
        <?php endif;?>
        <div class="mbp-separator"></div>
        <div class="input-div">
            <?php _e('Application ID/API Key:', 'microblog-poster');?>
        </div>
        <div class="input-div-large">
            <input type="text" id="" name="consumer_key" value="" />
            <span class="description">(Application ID / API Key)</span>
        </div>
        <div class="input-div">
            <?php _e('Application Secret:', 'microblog-poster');?>
        </div>
        <div class="input-div-large">
            <input type="text" id="" name="consumer_secret" value="" />
            <span class="description">(Application Secret)</span>
        </div>
    </div>
    <div id="mbp-facebook-upgrade-now"><?php _e('Available with the Pro / Enterprise Add-on.', 'microblog-poster');?> <a href="https://efficientscripts.com/web/products/addons" target="_blank"><?php _e('Upgrade Now', 'microblog-poster');?></a></div>
</div>
<div id="diigo-div" class="one-account">
    <div class="help-div"><span class="description">Diigo&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/diigo-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" name="username" value="" />
    </div>
    <div class="input-div">
        <?php _e('Password:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="password" value="" />
    </div>
    <div class="input-div">
        <?php _e('API Key:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="api_key" value="" />
        <span class="description">(API Key)</span>
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
    <div class="input-div">
        <?php _e('Include tags:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="checkbox" id="include_tags" name="include_tags" value="1"/>
        <span class="description"><?php _e('Do you want to include tags in the bookmarks?', 'microblog-poster');?></span>
    </div>
    <div class="mbp-separator"></div>
    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
    <?php else:?>
        <?php microblogposter_show_more_infos_category_driven();?>
    <?php endif;?>
</div>
<div id="linkedin-div" class="one-account">
    <div class="help-div"><span class="description">Linkedin&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/linkedin-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" name="username" value="" />
        <span class="description"><?php echo $description_mandatory_username;?></span>
    </div>
    <div class="input-div">
        <?php _e('Linkedin target type:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <select name="mbp_linkedin_target_type" id="mbp_linkedin_target_type">
            <option value="profile"><?php _e('Profile timeline', 'microblog-poster');?></option>
            <option value="company"><?php _e('Company timeline', 'microblog-poster');?></option>
        </select>
        <span class="description"><?php _e('Where you want to auto post.', 'microblog-poster');?></span>
    </div>
    <div id="mbp-linkedin-input-div">
        <div id="mbp-linkedin-group-id-div">
            <div class="input-div">
                <?php _e('Group ID:', 'microblog-poster');?>
            </div>
            <div class="input-div-large">
                <input type="text" id="mbp_linkedin_group_id" name="mbp_linkedin_group_id" value="" />
                <span class="description"><?php _e('Your Linkedin Group ID.', 'microblog-poster');?></span>
            </div>
        </div>
        <div id="mbp-linkedin-company-id-div">
            <div class="input-div">
                <?php _e('Company ID:', 'microblog-poster');?>
            </div>
            <div class="input-div-large">
                <input type="text" id="mbp_linkedin_company_id" name="mbp_linkedin_company_id" value="" />
                <span class="description"><?php _e('Your Linkedin Company ID.', 'microblog-poster');?></span>
            </div>
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
            <span class="description-small"><?php echo $description_shortcodes_less;?></span>
        </div>
        <div class="mbp-separator"></div>
        <div class="input-div input-div-radio">
            <?php _e('Post Type:', 'microblog-poster');?>
        </div>
        <div class="input-div-large">
            <!--input type="radio" name="post_type_lkn" value="text" checked="checked"> Text <span class="description">Text only status update.</span><br-->
            <input type="radio" name="post_type_lkn" value="link" checked="checked"> <?php _e('Link', 'microblog-poster');?> <span class="description"><?php _e('(Text message + Linkedin link box.)', 'microblog-poster');?></span>
        </div>
        <div class="input-div">

        </div>
        <div class="input-div-large">
            <span class="description-small">
                <?php _e('Posting with link box you\'ll need a thumbnail for your link. If your post contains a featured image, MicroblogPoster will take that one.', 'microblog-poster');?>
                <?php _e('If not, no explicit image url will be submitted and your update will appear without a thumbnail.', 'microblog-poster');?>
                <?php _e('If you want always to have an image going with your link then specify a default image url just below.', 'microblog-poster');?>
                <?php _e('This default thumbnail url will be posted for each new post that doesn\'t have featured image.', 'microblog-poster');?>
            </span>
        </div>
        <div class="input-div">
            <?php _e('Default Image Url:', 'microblog-poster');?>
        </div>
        <div class="input-div-large">
            <input type="text" id="default_image_url" name="default_image_url" />
            <span class="description"><?php _e('Default Thumbnail for link box.', 'microblog-poster');?> <a href="https://efficientscripts.com/help/microblogposter/generalhelp#def_img_url" target="_blank"><?php _e('Help', 'microblog-poster');?></a></span>
        </div>
        <div class="mbp-separator"></div>
        <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
            <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
        <?php else:?>
            <?php microblogposter_show_more_infos_category_driven();?>
        <?php endif;?>
        <div class="mbp-separator"></div>
        <div class="input-div">
            <?php _e('Application ID/API Key:', 'microblog-poster');?>
        </div>
        <div class="input-div-large">
            <input type="text" id="" name="consumer_key" value="" />
            <span class="description">(Application ID / API Key)</span>
        </div>
        <div class="input-div">
            <?php _e('Application Secret:', 'microblog-poster');?>
        </div>
        <div class="input-div-large">
            <input type="text" id="" name="consumer_secret" value="" />
            <span class="description">(Application Secret)</span>
        </div>
    </div>
    <div id="mbp-linkedin-upgrade-now"><?php _e('Available with the Pro / Enterprise Add-on.', 'microblog-poster');?> <a href="https://efficientscripts.com/web/products/addons" target="_blank"><?php _e('Upgrade Now', 'microblog-poster');?></a></div>
</div>
<div id="tumblr-div" class="one-account">
    <div class="help-div"><span class="description">Tumblr&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/tumblr-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" name="username" />
        <span class="description"><?php echo $description_mandatory_username;?></span>
    </div>
    <div class="input-div">
        <?php _e('Blog Hostname:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="mbp_tumblr_blog_hostname" name="mbp_tumblr_blog_hostname" />
        <span class="description"><?php _e('Example:', 'microblog-poster');?> 'blogname.tumblr.com'</span>
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
        <span class="description-small"><?php echo $description_shortcodes;?></span>
    </div>
    <div class="mbp-separator"></div>
    <div class="input-div">
        <?php _e('Include featured image:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="checkbox" id="include_featured_image_tumblr" name="include_featured_image" value="1" />
        <span class="description"><?php _e('Do you want to include featured image in your updates?', 'microblog-poster');?></span>
    </div>
    <div class="mbp-separator"></div>
    <div class="input-div input-div-radio">
        <?php _e('Post Type:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="radio" class="post_type_tmb_class post_type_tmb_class1" name="mbp_post_type_tmb" id="post_type_tmb_text" value="text" checked="checked"> <?php _e('Text', 'microblog-poster');?> <span class="description"><?php _e('(Text status update.)', 'microblog-poster');?></span><br>
        <input type="radio" class="post_type_tmb_class post_type_tmb_class1" name="mbp_post_type_tmb" value="link"> <?php _e('Link', 'microblog-poster');?> <span class="description"><?php _e('Tumblr link box status update.', 'microblog-poster');?></span>
    </div>
    <div class="input-div">

    </div>
    <div class="input-div-large">
        <span class="description-small">
            <?php _e('Link box + description of your post. Message Format field above isn\'t used.', 'microblog-poster');?>
        </span>
    </div>
    <div class="mbp-separator"></div>
    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
    <?php else:?>
        <?php microblogposter_show_more_infos_category_driven();?>
    <?php endif;?>
    <div class="mbp-separator"></div>
    <div id="mbp-tumblr-input-div">
        <div class="input-div">
            <?php _e('Consumer Key:', 'microblog-poster');?>
        </div>
        <div class="input-div-large">
            <input type="text" id="" name="consumer_key" value="" />
            <span class="description">(Application Consumer Key)</span>
        </div>
        <div class="input-div">
            <?php _e('Consumer Secret:', 'microblog-poster');?>
        </div>
        <div class="input-div-large">
            <input type="text" id="" name="consumer_secret" value="" />
            <span class="description">(Application Consumer Secret)</span>
        </div>
    </div>
    <div id="mbp-tumblr-upgrade-now"><?php _e('Available with the Pro / Enterprise Add-on.', 'microblog-poster');?> <a href="https://efficientscripts.com/web/products/addons" target="_blank"><?php _e('Upgrade Now', 'microblog-poster');?></a></div>
</div>
<div id="blogger-div" class="one-account">
    <div class="help-div"><span class="description">Blogger/Blogspot&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/blogger-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" name="username" />
        <span class="description"><?php echo $description_mandatory_username;?></span>
    </div>
    <div class="input-div">
        <?php _e('Blog Id:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="mbp_blogger_blog_id" name="mbp_blogger_blog_id" />
        <span class="description"><?php _e('Example:', 'microblog-poster');?> '1237342953579224633'</span>
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
        <span class="description-small"><?php echo $description_shortcodes;?></span>
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
        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
    <?php else:?>
        <?php microblogposter_show_more_infos_category_driven();?>
    <?php endif;?>
    <div class="mbp-separator"></div>
    <div class="input-div">
        <?php _e('Client Id:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="consumer_key" value="" />
        <span class="description">(Client Id)</span>
    </div>
    <div class="input-div">
        <?php _e('Client Secret:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="consumer_secret" value="" />
        <span class="description">(Client Secret)</span>
    </div>
</div>
<div id="instapaper-div" class="one-account">
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" name="username" value="" />
    </div>
    <div class="input-div">
        <?php _e('Password:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="password" value="" />
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
    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
    <?php else:?>
        <?php microblogposter_show_more_infos_category_driven();?>
    <?php endif;?>
</div>
<div id="vkontakte-div" class="one-account">
    <div class="help-div"><span class="description">VKontakte&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/vkontakte-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" name="username" value="" />
        <span class="description"><?php echo $description_mandatory_username;?></span>
    </div>
    <div class="input-div">
        <?php _e('VKontakte target type:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <select name="mbp_vkontakte_target_type" id="mbp_vkontakte_target_type">
            <option value="profile"><?php _e('Profile wall', 'microblog-poster');?></option>
            <option value="page"><?php _e('Public Page wall', 'microblog-poster');?></option>
            <option value="group"><?php _e('Group wall', 'microblog-poster');?></option>
            <option value="event"><?php _e('Event wall', 'microblog-poster');?></option>
        </select>
        <span class="description"><?php _e('Where you want to auto post.', 'microblog-poster');?></span>
    </div>
    <div id="mbp-vkontakte-input-div">
        <div class="input-div">
            <span class="mbp_vkontakte_target_type_string"></span> 
        </div>
        <div class="input-div-large">
            <input type="text" id="mbp_vkontakte_target_id" name="mbp_vkontakte_target_id" value="" />
            <span class="description"> <span class="mbp_vkontakte_target_type_string"></span>. (<?php _e('numeric', 'microblog-poster');?>)</span>
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
            <span class="description-small"><?php echo $description_shortcodes;?></span>
        </div>
        <div class="mbp-separator"></div>
        <div class="input-div input-div-radio">
            <?php _e('Post Type:', 'microblog-poster');?>
        </div>
        <div class="input-div-large">
            <input type="radio" name="post_type_vk" value="text" checked="checked"> <?php _e('Text', 'microblog-poster');?> - <span class="description"><?php _e('Text only status update.', 'microblog-poster');?></span><br>
            <input type="radio" name="post_type_vk" value="link"> <?php _e('Link', 'microblog-poster');?> - <span class="description"><?php _e('(Text message + VKontakte link box.)', 'microblog-poster');?></span>
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
            <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
        <?php else:?>
            <?php microblogposter_show_more_infos_category_driven();?>
        <?php endif;?>
        <div class="mbp-separator"></div>
        <div class="input-div">
            <?php _e('Application ID/API Key:', 'microblog-poster');?>
        </div>
        <div class="input-div-large">
            <input type="text" id="" name="consumer_key" value="" />
            <span class="description">(Application ID)</span>
        </div>
        <div class="input-div">
            <?php _e('Application Secret:', 'microblog-poster');?>
        </div>
        <div class="input-div-large">
            <input type="text" id="" name="consumer_secret" value="" />
            <span class="description">(Application Secret)</span>
        </div>
    </div>
    <div id="mbp-vkontakte-upgrade-now"><?php _e('Available with the Pro / Enterprise Add-on.', 'microblog-poster');?> <a href="https://efficientscripts.com/web/products/addons" target="_blank"><?php _e('Upgrade Now', 'microblog-poster');?></a></div>
</div>
<div id="xing-div" class="one-account">
    <div class="help-div"><span class="description"> Xing&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/xing-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" name="username" />
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
        <span class="description-small"><?php echo $description_shortcodes_less;?></span>
    </div>
    <div class="mbp-separator"></div>
    <div class="input-div input-div-radio">
        <?php _e('Post Type:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="radio" name="post_type_xing" value="text" checked="checked"> <?php _e('Text', 'microblog-poster');?> - <span class="description"><?php _e('Text only status update.', 'microblog-poster');?></span><br>
        <input type="radio" name="post_type_xing" value="link"> <?php _e('Link', 'microblog-poster');?> - <span class="description"><?php _e('(Text message + Xing link box.)', 'microblog-poster');?></span>
    </div>
    <div class="mbp-separator"></div>
    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
    <?php else:?>
        <?php microblogposter_show_more_infos_category_driven();?>
    <?php endif;?>
    <div class="mbp-separator"></div>
    <div class="input-div">
        <?php _e('Consumer Key:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="consumer_key" value="" />
        <span class="description">(Application Consumer Key)</span>
    </div>
    <div class="input-div">
        <?php _e('Consumer Secret:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="consumer_secret" value="" />
        <span class="description">(Application Consumer Secret)</span>
    </div>
</div>
<div id="pinterest-div" class="one-account">
    <div class="help-div"><span class="description">Pinterest&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/pinterest-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" name="username" />
        <span class="description"><?php echo $description_mandatory_username;?></span>
    </div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="mbp_pinterest_user_name" name="mbp_pinterest_user_name" />
        <span class="description">(Pinterest Username) <?php _e('Example:', 'microblog-poster');?> 'eddylafarge'</span>
    </div>
    <div class="input-div">
        <?php _e('Board Name:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="mbp_pinterest_board_name" name="mbp_pinterest_board_name" />
        <span class="description">(Pinterest Board Name) <?php _e('Example:', 'microblog-poster');?> 'ideas-for-the-house'</span>
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
        <span class="description-small"><?php echo $description_shortcodes;?></span>
    </div>
    <div class="mbp-separator"></div>
    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
    <?php else:?>
        <?php microblogposter_show_more_infos_category_driven();?>
    <?php endif;?>
    <div class="mbp-separator"></div>
    <div class="input-div">
        <?php _e('Client Id:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="consumer_key" value="" />
        <span class="description">(Client Id)</span>
    </div>
    <div class="input-div">
        <?php _e('Client Secret:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="consumer_secret" value="" />
        <span class="description">(Client Secret)</span>
    </div>
</div>
<div id="flickr-div" class="one-account">
    <div class="help-div"><span class="description"> Flickr&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/flickr-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" name="username" />
    </div>
     <div class="input-div">
            <?php _e('Album Id:', 'microblog-poster');?>
        </div>
        <div class="input-div-large">
            <input type="text" id="mbp_flickr_album_id" name="mbp_flickr_album_id" value=""/>
            <span class="description"><?php _e('Example:', 'microblog-poster');?> '1237342953579224633'</span>
        </div>
        <div class="mbp-separator"></div>
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
        <span class="description-small"><?php echo $description_shortcodes;?></span>
    </div>
    <div class="mbp-separator"></div>
    <div class="input-div">
        <?php _e('Include tags:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="checkbox" id="include_tags" name="include_tags" value="1"/>
        <span class="description"><?php _e('Do you want to include tags in the bookmarks?', 'microblog-poster');?></span>
    </div>
    <div class="mbp-separator"></div>
    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
    <?php else:?>
        <?php microblogposter_show_more_infos_category_driven();?>
    <?php endif;?>
    <div class="mbp-separator"></div>
    <div class="input-div">
        <?php _e('Consumer Key:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="consumer_key" value="" />
        <span class="description">(Application Consumer Key)</span>
    </div>
    <div class="input-div">
        <?php _e('Consumer Secret:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="consumer_secret" value="" />
        <span class="description">(Application Consumer Secret)</span>
    </div>
    <div class="input-div">

    </div>
</div>

<div id="wordpress-div" class="one-account">
    <div class="input-div">
        <?php _e('Blog Url:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="mbp_wordpress_blog_url" name="mbp_wordpress_blog_url" value="" />
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
        <input type="text" id="username" name="username" value="" />
    </div>
    <div class="input-div">
        <?php _e('Password:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="password" value="" />
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
        <span class="description-small"><?php echo $description_shortcodes;?></span>
    </div>
    <div class="input-div">
        <?php _e('Include tags:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="checkbox" id="include_tags" name="include_tags" value="1"/>
        <span class="description"><?php _e('Do you want to include tags in the bookmarks?', 'microblog-poster');?></span>
    </div>
    <div class="mbp-separator"></div>
    <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
        <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
    <?php else:?>
        <?php microblogposter_show_more_infos_category_driven();?>
    <?php endif;?>
</div>

<div id="buffer-div" class="one-account">
    <div class="help-div"><span class="description">Buffer&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/buffer-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" name="username" value="" />
    </div>
    <div class="input-div">
        <?php _e('API Key:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="" name="api_key" value="" />
        <span class="description">(API Key)</span>
    </div>
</div>
<div id="googleplus-div" class="one-account">
    <div class="help-div"><span class="description">Google+&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/googleplus-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" class="gp-username" name="username" value="" />
        <span class="description"><?php echo $description_mandatory_username;?></span>
    </div>
    <div class="input-div">
        <?php _e('Buffer account:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
         <select name="mbp_buffer_name" id="mbp_buffer_name">
             <option value=""><?php _e('(None)', 'microblog-poster');?></option> 
             <?php $dd_accounts = MicroblogPoster_Poster::get_accounts_object('buffer'); ?>
             <?php if(!empty($dd_accounts)): ?>
                <?php foreach($dd_accounts as $account): ?>
                    <?php $gp_accounts = MicroblogPoster_Poster::get_googleplus_accounts($account->account_id); ?>
                    <?php if(!empty($gp_accounts)): ?>
                        <option value="<?php echo $account->account_id;?>"><?php echo $account->username; ?> </option> 
                    <?php endif ?>
                <?php endforeach; ?>
            <?php endif; ?>
         </select>
        <span class="description"></span>
    </div>
    <div id="mbp-googleplus-input-div">
        <?php $dd_accounts = MicroblogPoster_Poster::get_accounts_object('buffer'); ?>
        <?php if(!empty($dd_accounts)): ?>
            <?php foreach($dd_accounts as $account): ?>
            <?php $select_id = 'mbp-googleplus-accounts-div-'.$account->account_id; ?>
            <div class="googleplus-select" id="<?php echo $select_id; ?>" >
                <?php $gp_accounts = MicroblogPoster_Poster::get_googleplus_accounts($account->account_id); ?>
                <?php if(!empty($gp_accounts)): ?>
                <div class="input-div">
                <?php _e('Google+ account:', 'microblog-poster');?>
                </div>
                <div class="input-div-large">
                    <select name="mbp_googleplus_select_name" id="mbp_googleplus_select_name">
                        <?php foreach ($gp_accounts as $key => $value): ?>
                        <option value="<?php echo $key; ?>"><?php echo $value; ?> </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="description"></span>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
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
            <?php _e('Include tags:', 'microblog-poster');?>
        </div>
        <div class="input-div-large">
            <input type="checkbox" id="include_tags" name="include_tags" value="1" />
            <span class="description"><?php _e('Do you want to include tags in the bookmarks?', 'microblog-poster');?></span>
        </div>
        <div class="mbp-separator"></div>
        <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
            <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
        <?php else:?>
            <?php microblogposter_show_more_infos_category_driven();?>
        <?php endif;?>
    </div>
</div>
<div id="facebookb-div" class="one-account">
    <div class="help-div"><span class="description">Facebook&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/facebook-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" class="gp-username" name="username" value="" />
        <span class="description"><?php echo $description_mandatory_username;?></span>
    </div>
    <div class="input-div">
        <?php _e('Buffer account:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
         <select name="mbp_buffer_name" id="mbp_buffer_name">
             <option value=""><?php _e('(None)', 'microblog-poster');?></option> 
             <?php $dd_accounts = MicroblogPoster_Poster::get_accounts_object('buffer'); ?>
             <?php if(!empty($dd_accounts)): ?>
                <?php foreach($dd_accounts as $account): ?>
                    <?php $gp_accounts = MicroblogPoster_Poster::get_facebook_accounts($account->account_id); ?>
                    <?php if(!empty($gp_accounts)): ?>
                        <option value="<?php echo $account->account_id;?>"><?php echo $account->username; ?> </option> 
                    <?php endif ?>
                <?php endforeach; ?>
            <?php endif; ?>
         </select>
        <span class="description"></span>
    </div>
    <div id="mbp-facebookb-input-div">
        <?php $dd_accounts = MicroblogPoster_Poster::get_accounts_object('buffer'); ?>
        <?php if(!empty($dd_accounts)): ?>
            <?php foreach($dd_accounts as $account): ?>
            <?php $select_id = 'mbp-facebookb-accounts-div-'.$account->account_id; ?>
            <div class="facebookb-select" id="<?php echo $select_id; ?>" >
                <?php $gp_accounts = MicroblogPoster_Poster::get_facebook_accounts($account->account_id); ?>
                <?php if(!empty($gp_accounts)): ?>
                <div class="input-div">
                <?php _e('Facebook account:', 'microblog-poster');?>
                </div>
                <div class="input-div-large">
                    <select name="mbp_facebookb_select_name" id="mbp_facebookb_select_name">
                        <?php foreach ($gp_accounts as $key => $value): ?>
                        <option value="<?php echo $key; ?>"><?php echo $value; ?> </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="description"></span>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
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
            <?php _e('Include tags:', 'microblog-poster');?>
        </div>
        <div class="input-div-large">
            <input type="checkbox" id="include_tags" name="include_tags" value="1" />
            <span class="description"><?php _e('Do you want to include tags in the bookmarks?', 'microblog-poster');?></span>
        </div>
        <div class="mbp-separator"></div>
        <?php if(MicroblogPoster_Poster::is_method_callable('MicroblogPoster_Poster_Enterprise_Options','microblogposter_display_link_categories')):?>
            <?php MicroblogPoster_Poster_Enterprise_Options::microblogposter_display_link_categories(array());?>
        <?php else:?>
            <?php microblogposter_show_more_infos_category_driven();?>
        <?php endif;?>
    </div>
</div>
<div id="googlemybusiness-div" class="one-account">
    <div class="help-div"><span class="description">Google My Business&nbsp;:&nbsp;<a href="https://efficientscripts.com/web/microblogposter/googlemybusiness-auto-publish" target="_blank"><?php _e('Help with screenshots in english', 'microblog-poster');?></a></span></div>
    <div class="input-div">
        <?php _e('Username:', 'microblog-poster');?>
    </div>
    <div class="input-div-large">
        <input type="text" id="username" name="username" value="" />
    </div>
</div>