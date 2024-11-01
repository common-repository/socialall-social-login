<?php if( ! defined( 'ABSPATH' ) ) exit; ?>

<?php 
    $basic_settings = get_option( SK_SOCLALL_LOGIN_BASIC_SETTINGS );
    $theme_applied = isset($basic_settings['theme_applied']) ? $basic_settings['theme_applied'] : 'default';
?>
<div id="sk-soclall-login-form" class="sk-soclall-login-wrap sa-<?php echo $theme_applied; ?>">
<?php if( isset( $_REQUEST['error'] ) || isset( $_REQUEST['denied'] ) ) { ?>
    <div class="sk-soclall-login-warning">
        <?php _e( 'You have Access Denied. Please authorize the app to login!', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ) ?>
    </div>
<?php } ?>

<?php
    $api_settings = get_option( SK_SOCLALL_LOGIN_ACTIVATION_SETTINGS );
    $api = array( 'app_id' => $api_settings['api']['app_id'], 'secret_key' => $api_settings['api']['secret_key'] );
    $soclall = new Soclall($api); 
    
    $callback_url = home_url();
    if( $basic_settings['login_redirect_option'] == 'current' ) {
        $callback_url = Sk_SoclAll_Login_Check::gen_current_page_url();
    }
    
    if( false === strpos( $callback_url, '?' ) )
        $callback_url .= "?";
    else
        $callback_url .= "&";
?>