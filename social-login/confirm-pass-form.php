<?php if( ! defined( 'ABSPATH' ) ) exit; ?>
<?php 
    header( 'Content-Type: ' . get_bloginfo('html_type') . '; charset=' . get_bloginfo('charset') );
    wp_admin_css( 'login', true ); 
?>

<div id="login" class="login wp-core-ui">
    <h1 style="margin-bottom: 20px;">
        <img src="<?php echo SK_SOCLALL_LOGIN_IMAGES_URL; ?>/soclall-logo.png" style="margin-bottom: 15px;" />
        <div><?php _e( 'Confirm Password', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></div>
    </h1>
    
    <div id="login_error" style="display: none;"></div>
    
    <form action="">
        <input type="hidden" value="<?php echo $_GET['token']; ?>" name="token" />
        <input type="hidden" value="<?php echo $_GET['network']; ?>" name="network" />
        
        <p style="margin-bottom: 10px;"><?php _e( 'Please confirm password (operation must be performed one time only):', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></p>
        <p><label><?php _e( 'Password', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?><br />
            <input type="password" class="input" size="20" value="" name="sk_soclall_login_pass" />
        </label></p>
        <p class="submit">
            <input type="button" id="sk_soclall_login_confirm_pass" name="sk_soclall_login_confirm_pass" class="button button-primary button-large" value="<?php _e( 'Confirm', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?>" />
        </p>
        <img id="loading" src="<?php echo SK_SOCLALL_LOGIN_IMAGES_URL; ?>/loading.gif" style="display: none; float: right;" />
    </form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="<?php echo SK_SOCLALL_LOGIN_JS_URL; ?>/socl-login.js" type="text/javascript"></script>