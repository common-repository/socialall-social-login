<?php if( ! defined( 'ABSPATH' ) ) exit; ?>
<?php 
    header( 'Content-Type: ' . get_bloginfo('html_type') . '; charset=' . get_bloginfo('charset') );
    wp_admin_css( 'login', true ); 
?>
<link rel="stylesheet" href="<?php echo SK_SOCLALL_LOGIN_CSS_URL; ?>/socl-login.css" type="text/css" media="all" />

<div id="login" class="login wp-core-ui">
    <h1 style="margin-bottom: 20px;">
        <img src="<?php echo SK_SOCLALL_LOGIN_IMAGES_URL; ?>/soclall-logo.png" style="margin-bottom: 15px;" />
        <div><?php _e( 'Register Email', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></div>
    </h1>
    
    <?php if( isset( $soclall_user ) ) : ?>
    <div class="sk-soclall-login-user">
        <?php 
            if( !empty( $soclall_user['avatar_url'] ) )
                $avatar_url = $soclall_user['avatar_url'];
            else 
                $avatar_url = SK_SOCLALL_LOGIN_IMAGES_URL . "/unknown.gif";
        ?>
        <img src="<?php echo $avatar_url; ?>" />
        <p><?php echo Sk_SoclAll_Login_Check::gen_display_name( $soclall_user ); ?></p>
    </div>
    <?php endif; ?>
    
    <div id="login_error" style="display: none;"></div>
    
    <form action="">
        <input type="hidden" value="<?php echo $_GET['token']; ?>" name="token" />
        <input type="hidden" value="<?php echo $_GET['network']; ?>" name="network" />
        
        <p style="margin-bottom: 10px;"><?php _e( 'Please fill email to register:', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></p>
        <p><label><?php _e( 'Email', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?><br />
            <input type="text" class="input" size="20" value="" name="sk_soclall_login_email" />
        </label></p>
        <p class="submit">
            <input type="button" id="sk_soclall_login_regis_email" name="sk_soclall_login_regis_email" class="button button-primary button-large" value="<?php _e( 'Complete', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?>" />
        </p>
        <img id="loading" src="<?php echo SK_SOCLALL_LOGIN_IMAGES_URL; ?>/loading.gif" style="display: none; float: right;" />
    </form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="<?php echo SK_SOCLALL_LOGIN_JS_URL; ?>/socl-login.js" type="text/javascript"></script>