<?php if( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap">
	<div class="sk-soclall-login-wrap">
		<div class="sk-soclall-login-header">
			<img src="<?php echo SK_SOCLALL_LOGIN_IMAGES_URL; ?>/soclall-logo.png" alt="SoclAll - Social Login" />
			<div class="sk-soclall-login-header-title">
                SocialAll - Social Login
                <span><?php _e( 'Version ' . SK_SOCLALL_LOGIN_VERSION, SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></span>
			</div>
		</div>
		<div class="clear"></div>
        
        <?php if( isset( $_SESSION['sk_soclall_login_message'] ) ) { ?>
        <div class="sk-soclall-login-message">
            <?php 
                echo $_SESSION['sk_soclall_login_message'];
                unset( $_SESSION['sk_soclall_login_message'] );
            ?>
        </div>
        <?php } ?>
        <?php if( isset( $_SESSION['sk_soclall_login_message_error'] ) ) { ?>
        <div class="sk-soclall-login-message-error">
            <?php 
                echo $_SESSION['sk_soclall_login_message_error'];
                unset( $_SESSION['sk_soclall_login_message_error'] );
            ?>
        </div>
        <?php } ?>
        
		<div class="sk-soclall-login-content">
			<form method="post" action="<?php echo admin_url() . "admin-post.php"; ?>">
				<input type="hidden" name="action" value="sk_soclall_login_save_settings"/>
                <?php wp_nonce_field( 'sk_soclall_login_save_settings_nonce', 'action_nonce' ); ?>
				<ul class="sk-soclall-login-tabs">
					<li id="sk_soclall_login_tab_soclall_settings" class="sk-soclall-login-tab sk-soclall-login-active-tab"><?php _e( 'SocialAll Settings', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></li>
					<li id="sk_soclall_login_tab_plugin_settings" class="sk-soclall-login-tab"><?php _e( 'Basic Settings', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></li>
                    <li id="sk_soclall_login_tab_manage_theme" class="sk-soclall-login-tab"><?php _e( 'Manage Theme', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></li>
					<li id="sk_soclall_login_tab_shortcode_guide" class="sk-soclall-login-tab"><?php _e( 'Shortcode Guide', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></li>
				</ul>
				<div class="clear"></div>
                
				<div class="sk-soclall-login-tabs-content">
                    <?php   
                        $basic_settings = get_option( SK_SOCLALL_LOGIN_BASIC_SETTINGS );
                    ?>
                    
                    <!-- SoclAll Settings -->
					<div id="for_sk_soclall_login_tab_soclall_settings" class="sk-soclall-login-tab-content">
						<div class="sk-soclall-login-container">
							<h3><?php _e( 'API Settings', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></h3>
							<div class="sk-soclall-login-container-content">
                                <?php if( empty( $this->api_settings['api']['app_id'] ) || empty( $this->api_settings['api']['secret_key'] ) ) : ?>
                                <p><?php _e( 'You must fill App ID and Secret Key to enable the plugin.', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></p>
                                <?php endif; ?>
								<table>
									<tr>
										<td>App ID</td>
										<td><input type="text" name="sk_soclall_login_app_id" value="<?php echo $this->api_settings['api']['app_id']; ?>" placeholder="<?php _e( 'Enter App ID', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?>" /></td>
									</tr>
									<tr>
										<td>Secret Key</td>
										<td><input type="text" name="sk_soclall_login_secret_key" value="<?php echo $this->api_settings['api']['secret_key']; ?>" placeholder="<?php _e( 'Enter Secret Key', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?>" /></td>
									</tr>
								</table>
                                
								<div class="sk-soclall-login-how-to"><?php _e( 'How to get App ID and Secret Key', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?>
									<a id="sk_soclall_login_how_to" href="#">
                                        <span id="sk_soclall_login_how_to_show"><?php _e( 'Show', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></span>
                                        <span id="sk_soclall_login_how_to_hide" style="display: none;"><?php _e( 'Hide', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></span>
                                    </a>
                                    <div class="sk-soclall-login-how-to-info" style="display: none;">
										<?php include_once( 'how-to-get-app-id.php' ); ?>
									</div>
								</div>
							</div>
						</div>
                        
						<div class="sk-soclall-login-container">
							<h3><?php _e( 'Networks Settings', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></h3>
							<div class="sk-soclall-login-container-content">
								<?php if( isset( $this->api_settings['networks'] ) && !empty( $this->api_settings['networks'] ) ) { ?>
                                    <p><?php _e( 'Select network(s) you want to show:', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></p>
								    <table class="sk-soclall-login-table-networks sa-default">
                                        <tr>
                                            <td colspan="3"><input type="checkbox" id="sk_soclall_login_select_all" name="sk_soclall_login_select_all" value="" style="margin-right: 15px;" /><?php _e( 'Select All', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></td>
                                        </tr>
								    <?php foreach( $this->api_settings['networks'] as $key => $network ) : ?>
											<tr>
												<td><input type="checkbox" id="sk_<?php echo $network['code']; ?>" name="sk_soclall_login_networks[]" value="<?php echo $network['code']; ?>" <?php echo ( $network['enable'] != '0' ) ? 'checked="checked"' : ''; ?> /></td>
                                                <td><span class="sa sa-<?php echo $network['code']; ?> sk-soclall-login-icon"></span><?php echo $network['name']; ?></td>
                                                <td><a href="http://socialall.readthedocs.org/en/latest/user-guide/<?php echo $network['code']; ?>" target="_blank" ><?php _e( 'How to config ', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); echo $network['name']; ?></a></td>
											</tr>
								    <?php endforeach; ?>
									</table>
								<?php } ?>
							</div>
						</div>
					</div>
                    
                    <!-- Plugin Settings -->
                    <div id="for_sk_soclall_login_tab_plugin_settings" class="sk-soclall-login-tab-content" style="display: none;">
                        <div class="sk-soclall-login-container">
                            <h4><?php _e( 'Display Settings', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></h4>
                            <div class="sk-soclall-login-container-content">
                                <p><?php _e( 'Where you want to display the Social Login form:', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></p>
                                <table>
									<tr>
										<td><?php _e( 'Login Page', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></td>
										<td><input type="checkbox" name="sk_soclall_login_display[]" value="login" <?php echo ( in_array( 'login', $basic_settings['enable_display'] ) ) ? 'checked="checked"' : ''; ?> /></td>
									</tr>
									<tr>
										<td><?php _e( 'Register Page', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></td>
										<td><input type="checkbox" name="sk_soclall_login_display[]" value="register" <?php echo ( in_array( 'register', $basic_settings['enable_display'] ) ) ? 'checked="checked"' : ''; ?> /></td>
									</tr>
                                    <tr>
										<td><?php _e( 'Comment Box', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></td>
										<td><input type="checkbox" name="sk_soclall_login_display[]" value="comment" <?php echo ( in_array( 'comment', $basic_settings['enable_display'] ) ) ? 'checked="checked"' : ''; ?> /></td>
									</tr>
								</table>
                            </div>
                        </div>
                        
                        <div class="sk-soclall-login-container">
                            <h4><?php _e( 'Social Login Title', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></h4>
                            <div class="sk-soclall-login-container-content">
                                <p><?php _e( 'The title line will display before the Social Login form:', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></p>
                                <input type="text" name="sk_soclall_login_title" value="<?php echo $basic_settings['text_title']; ?>" placeholder="<?php _e( 'Enter Title', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?>" />
                            </div>
                        </div>
                        
                        <div class="sk-soclall-login-container">
                            <h4><?php _e( 'Ebay Site Setting', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></h4>
                            <div class="sk-soclall-login-container-content">
                                <p><?php _e( 'Set Site/Country default for Ebay login:', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></p>
                                <select name="sk_soclall_login_ebay_site">
                                    <?php
                                        $ebay_sites = ( new ReflectionClass('SoclallEbaySites') )->getConstants(); 
                                        $ebay_site_setting = ( isset($basic_settings['ebay_site']) && !empty($basic_settings['ebay_site']) ) ? $basic_settings['ebay_site'] : 'US';
                                        foreach( $ebay_sites as $key => $value ) : ?>
                                        <option value="<?php echo $key; ?>" <?php if($key == $ebay_site_setting) { ?>selected="selected"<?php } ?>><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="sk-soclall-login-container">
                            <h4><?php _e( 'Other Settings', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></h4>
                            <div class="sk-soclall-login-container-content">
                                <div class="sk-soclall-login-container-radio">
                                    <p><?php _e( 'Redirection settings after login success:', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></p>
                                    <label><input type="radio" name="sk_soclall_login_redirection" value="current" <?php echo ( $basic_settings['login_redirect_option'] == 'current' ) ? 'checked="checked"' : ''; ?> /><span><?php _e( 'Redirect to the same page', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></span></label>
                                    <label><input type="radio" name="sk_soclall_login_redirection" value="home" <?php echo ( $basic_settings['login_redirect_option'] == 'home' ) ? 'checked="checked"' : ''; ?> /><span><?php _e( 'Redirect to the home page', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></span></label>
                                    <label><input type="radio" name="sk_soclall_login_redirection" value="custom" id="sk_soclall_login_custom_url" <?php echo ( $basic_settings['login_redirect_option'] == 'custom' ) ? 'checked="checked"' : ''; ?> /><span><?php _e( 'Redirect to a custom URL', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></span></label>
                                    <label <?php echo ( $basic_settings['login_redirect_option'] == 'custom' ) ? '' : 'style="display: none;"'; ?>><?php echo site_url( '/' ); ?>
                                        <input type="text" name="sk_soclall_login_custom_url" value="<?php echo $basic_settings['login_redirect_link']; ?>" placeholder="wp-admin/profile.php" />
                                    </label>
                                </div>
                                <div class="sk-soclall-login-container-radio">
                                    <p><?php _e( 'Send email to user (with username and password) after registration:', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></p>
                                    <label><input type="radio" name="sk_soclall_login_send_email" value="1" <?php echo ( $basic_settings['enable_send_email_notification'] == '1' ) ? 'checked="checked"' : ''; ?> /><span><?php _e( 'Yes', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></span></label>
                                    <label><input type="radio" name="sk_soclall_login_send_email" value="0" <?php echo ( $basic_settings['enable_send_email_notification'] == '0' ) ? 'checked="checked"' : ''; ?> /><span><?php _e( 'No', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shortcode Guide -->
                    <div id="for_sk_soclall_login_tab_shortcode_guide" class="sk-soclall-login-tab-content" style="display: none;">
                        <div class="sk-soclall-login-container">
                            <h3><?php _e( 'Short Code', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></h3>
                            <div class="sk-soclall-login-container-content">
                                <?php include_once( 'shortcode-guide.php' ); ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Manage Theme -->
                    <div id="for_sk_soclall_login_tab_manage_theme" class="sk-soclall-login-tab-content" style="display: none;">
                        <?php 
                            $themes = array('default','core','no3','no4','no5','no6','no7','no8'); 
                            $themes_resize = array('no5','no6','no7');
                            $theme_sizes = array('100','75','50');
                            $theme_no = 1; 
                            $theme_applied = isset($basic_settings['theme_applied']) ? $basic_settings['theme_applied'] : 'default';
                            
                            $themes_custom = array('no4','no7','no8');
                            $themes_position = array(
                                'l' => __( 'Left', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ),
                                'c' => __( 'Center', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ),
                                'r' => __( 'Right', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ),
                            );
                            
                            $theme_cus_col = isset($basic_settings['theme_cus_col']) ? $basic_settings['theme_cus_col'] : '1';
                            $theme_cus_pos = isset($basic_settings['theme_cus_pos']) ? $basic_settings['theme_cus_pos'] : 'l';
                            $theme_cus_align = isset($basic_settings['theme_cus_align']) ? $basic_settings['theme_cus_align'] : 'l';
                            $theme_cus_width = isset($basic_settings['theme_cus_width']) ? $basic_settings['theme_cus_width'] : '100';
                            $theme_cus_text = isset($basic_settings['theme_cus_text']) ? $basic_settings['theme_cus_text'] : '';
                            $theme_cus_text_default = __( 'Login with ', SK_SOCIALALL_LOGIN_TEXT_DOMAIN );
                        ?>
                        <script type="text/javascript">
                           sk_soclall_login_col = '<?php echo $theme_cus_col; ?>';
                           sk_soclall_login_pos = '<?php echo $theme_cus_pos; ?>';
                           sk_soclall_login_align = '<?php echo $theme_cus_align; ?>';
                           sk_soclall_login_width = '<?php echo $theme_cus_width; ?>';
                           sk_soclall_login_text = '<?php echo $theme_cus_text; ?>';
                           sk_soclall_login_text_default = '<?php echo $theme_cus_text_default; ?>'; 
                        </script>
                        
                        <input type="hidden" name="sk_soclall_login_apply_theme" value="<?php echo $theme_applied; ?>" />
                        <input type="hidden" name="sk_soclall_login_theme_resize" value="<?php echo isset($basic_settings['theme_resize']) ? $basic_settings['theme_resize'] : '0';  ?>" />
                        
                        <div id="soclall_manage_theme" class="sk-soclall-login-container">
                                <?php foreach($themes as $item) : ?>
                                    <div class="sk-soclall-login-theme-block <?php if($item == $theme_applied) { ?>active<?php } ?>">
                                        <span class="theme-title"><?php echo _e( 'Theme ', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ) . $theme_no; ?></span>
                                        <?php if(in_array($item, $themes_resize) && $item != 'no7') : ?>
                                            <div class="theme-size">
                                                <span><?php _e( 'Custom size:', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></span>
                                                <select onchange="soclLoginResizeTheme( this, '<?php echo $item; ?>' )">
                                                    <?php foreach($theme_sizes as $size) : ?>
                                                        <option value="<?php echo $size; ?>" <?php if( $item == $theme_applied && isset($basic_settings['theme_resize']) && $size == $basic_settings['theme_resize'] ) { ?>selected="selected"<?php } ?>><?php echo $size . '%'; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        <?php endif; ?>
                                        <input type="button" id="sk_soclall_login_apply" class="sk-soclall-login-btn btn-apply" value="<?php _e( 'Apply', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?>" onclick="soclLoginApplyTheme(this, '<?php echo $item; ?>')" <?php if($item == $theme_applied) { ?>style="display: none;"<?php } ?> />
                                        <span class="dashicons dashicons-yes check-theme-applied" <?php if($item != $theme_applied) { ?>style="display: none;"<?php } ?>></span>
                                        <?php if(in_array($item, $themes_custom)) : ?>
                                            <input type="button" class="sk-soclall-login-btn btn-apply btn-customize" value="<?php _e( 'Customize', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?>" onclick="soclLoginCustomTheme(this, '<?php echo $item; ?>')" />
                                        <?php endif; ?>
                                        <div class="sa-frame">
                                            <div class="sa-<?php echo $item; ?>">
                                                <?php foreach($this->api_settings['networks'] as $network) : ?>
                                                    <?php if(in_array($item, $themes_custom)) { ?>
                                                        <div class="col col<?php echo ($item == $theme_applied) ? $theme_cus_col : '1'; ?> pos-<?php echo ($item == $theme_applied) ? $theme_cus_pos : 'l'; ?>">
                                                            <span class="sa <?php if(in_array($item, $themes_resize)) { ?>sa-<?php echo ($item == $theme_applied && isset($basic_settings['theme_resize']) && $basic_settings['theme_resize'] != '0') ? $basic_settings['theme_resize'] : $theme_sizes[0]; } ?> sa-<?php echo $network['code']; ?> txt-<?php echo ($item == $theme_applied) ? $theme_cus_align : 'l'; ?>" <?php echo ($item == $theme_applied && $theme_cus_width != '100') ? "style='width: " . $theme_cus_width . "%;'" : ''; ?> >
                                                                <?php echo ($item == $theme_applied) ? $theme_cus_text : $theme_cus_text_default; ?><span><?php echo $network['name']; ?></span>
                                                            </span>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="sa <?php if(in_array($item, $themes_resize)) { ?>sa-<?php echo ($item == $theme_applied && isset($basic_settings['theme_resize']) && $basic_settings['theme_resize'] != '0') ? $basic_settings['theme_resize'] : $theme_sizes[0]; } ?> sa-<?php echo $network['code']; ?>"></span>
                                                    <?php } ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $theme_no++; ?>
                                <?php endforeach; ?>
                        </div>
                            
                        <div id="soclall_customize_theme" style="display: none;">
                            <div class="sk-soclall-login-container">
                                <h3><?php _e( 'Customize Theme', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></h3>
                                <div class="sk-soclall-login-container-content">
                                    <table>
                                        <tr>
    										<td><?php _e( 'Custom size', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></td>
    										<td>
                                                <select id="sk_soclall_login_theme_resize">
                                                    <?php foreach($theme_sizes as $size) : ?>
                                                        <option value="<?php echo $size; ?>"><?php echo $size . '%'; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
    									</tr>
    									<tr>
    										<td><?php _e( 'Number of column', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></td>
    										<td>
                                                <select name="sk_soclall_login_theme_col">
                                                    <?php for($i = 1; $i <= 5; $i++) : ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </td>
    									</tr>
    									<tr>
    										<td><?php _e( 'Width of button', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?><span style="font-size: 11px; margin-left: 3px;"><?php _e( '(Fill from 0-100)', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></span></td>
    										<td><input type="text" name="sk_soclall_login_theme_width" value="" /></td>
    									</tr>
                                        <tr>
    										<td><?php _e( 'Content of text', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></td>
    										<td><input type="text" name="sk_soclall_login_theme_text" value="" /></td>
    									</tr>
                                        <tr>
    										<td><?php _e( 'Position of text', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></td>
    										<td>
                                                <select name="sk_soclall_login_theme_align">
                                                    <?php foreach($themes_position as $key => $value) { ?>
                                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
    									</tr>
                                        <tr>
    										<td><?php _e( 'Position of button:', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></td>
    										<td>
                                                <select name="sk_soclall_login_theme_pos">
                                                    <?php foreach($themes_position as $key => $value) { ?>
                                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
    									</tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input type="button" class="sk-soclall-login-btn" value="<?php _e( 'Apply', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?>" onclick="soclLoginApplyCustomTheme()" />
                                                <input type="button" class="sk-soclall-login-btn" style="background-color: #9EABB8;" value="<?php _e( 'Cancel', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?>" onclick="soclLoginCancelCustomTheme()" />
                                            </td>
                                        </tr>
    								</table>
                                </div>
                            </div>
                            <div id="soclall_customize_theme_review" class="sk-soclall-login-container" style="padding: 20px 15px; display: inline-block;"></div>
                        </div>
                    </div>
                    
                    <!-- Save Button and Reset Button -->
                    <div id="sk_soclall_login_bottom">
                        <div class="sk-soclall-login-container">
                            <div class="sk-soclall-login-save-btn">
                                <?php $nonce = wp_create_nonce( 'sk_soclall_login_restore_default_settings_nonce' ); ?>
                                <a href="<?php echo admin_url() . "admin-post.php?action=sk_soclall_login_restore_default_settings&action_nonce=" . $nonce; ?>" title="<?php _e( 'Reset to the default settings (except API settings)', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?>" onclick="return confirm('<?php _e( 'Are you sure you want to restore default settings?', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?>')" style="text-decoration: none;">
                                    <input type="button" value="<?php _e( 'Reset default settings', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?>" class="sk-soclall-login-btn sk-soclall-login-reset" /></a>
                                
                                <input type="submit" name="sk_soclall_login_save_settings" value="<?php _e( 'Update Settings', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?>" class="sk-soclall-login-btn" />
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
				</div>
			</form>
		</div>
	</div>
</div>