<?php    
    $count = 0;    
    
    $themes_resize = array('no5','no6','no7');
    $themes_custom = array('no4','no7','no8');
    $theme_size = (in_array($theme_applied, $themes_resize)) ? ((isset($basic_settings['theme_resize']) && $basic_settings['theme_resize'] != '0') ? $basic_settings['theme_resize'] : '100') : '0';
    $theme_align = (in_array($theme_applied, $themes_custom)) ? ((isset($basic_settings['theme_cus_align']) && !empty($basic_settings['theme_cus_align'])) ? $basic_settings['theme_cus_align'] : 'l') : '';
    $theme_width = (in_array($theme_applied, $themes_custom) && isset($basic_settings['theme_cus_width']) && !empty($basic_settings['theme_cus_width']) && (int)$basic_settings['theme_cus_width'] < 100) ? $basic_settings['theme_cus_width'] : '';
?>
<?php if( !empty( $basic_settings['text_title'] ) ) : ?>
    <span class="sk-soclall-login-title"><?php echo $basic_settings['text_title']; ?></span>
<?php endif; ?>
<?php 
    $ebay_site_setting = ( isset($basic_settings['ebay_site']) && !empty($basic_settings['ebay_site']) ) ? $basic_settings['ebay_site'] : 'US';
    foreach( $networks as $key => $value ) : ?>
    <?php if( $value['enable'] != '0' ) : ?>
        <?php $href = $soclall->getLoginUrl( $value['code'], $callback_url, ($value['code'] == 'ebay') ? $ebay_site_setting : '' ); ?>
        <?php if(in_array($theme_applied, $themes_custom)) : ?>
            <div class="col col<?php echo (isset($basic_settings['theme_cus_col']) && !empty($basic_settings['theme_cus_col'])) ? $basic_settings['theme_cus_col'] : '1'; ?> pos-<?php echo (isset($basic_settings['theme_cus_pos']) && !empty($basic_settings['theme_cus_pos'])) ? $basic_settings['theme_cus_pos'] : 'l'; ?>">
        <?php endif; ?>
            <a class="sa <?php if($theme_size != '0') { ?>sa-<?php echo $theme_size; } ?> sa-<?php echo $value['code']; ?> <?php if(!empty($theme_align)) { ?>txt-<?php echo $theme_align; } ?>" <?php if(!empty($theme_width)) { ?>style="width: <?php echo $theme_width; ?>%;"<?php } ?> href="<?php echo $href; ?>" <?php if( $check_rememberme ) : ?>onclick="return sk_soclall_login_check_rememberme(this, '<?php echo $callback_url; ?>');"<?php endif; ?> >
                <?php echo (in_array($theme_applied, $themes_custom)) ? ((isset($basic_settings['theme_cus_text'])) ? $basic_settings['theme_cus_text'] : __( 'Login with ', SK_SOCIALALL_LOGIN_TEXT_DOMAIN )) . $value['name'] : ''; ?>
            </a>
        <?php if(in_array($theme_applied, $themes_custom)) : ?></div><?php endif; ?>
    <?php $count++; endif; ?>
<?php endforeach; ?>
    
<?php if( $count == 0 ) : ?>
    <div class="sk-soclall-login-warning">
        <?php _e( 'No social network has been enabled!', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ) ?>
    </div>
<?php endif; ?>
</div>