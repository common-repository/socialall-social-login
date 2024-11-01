<?php if( ! defined( 'ABSPATH' ) ) exit; ?>

<?php
if (!session_id()) {
    session_start();
}

$api_settings = array();
$api_option = get_option( SK_SOCLALL_LOGIN_ACTIVATION_SETTINGS );

if( isset( $_POST['sk_soclall_login_app_id'] ) && isset( $_POST['sk_soclall_login_secret_key'] ) ) {
    $api_settings['api'] = array( 'app_id' => $_POST['sk_soclall_login_app_id'], 'secret_key' => $_POST['sk_soclall_login_secret_key'] );
} else
    $api_settings['api'] = $api_option['api'];

$networks_settings = $api_option['networks'];
$networks = array();
if( isset( $_POST['sk_soclall_login_networks'] ) && is_array( $_POST['sk_soclall_login_networks'] ) ) {
    foreach ( $networks_settings as $key => $network ) {
        if( in_array( $network['code'], $_POST['sk_soclall_login_networks'] ) )
            $networks[] = array( 'name' => $network['name'], 'code' => $network['code'], 'enable' => '1' );
        else
            $networks[] = array( 'name' => $network['name'], 'code' => $network['code'], 'enable' => '0' );
    }
} else {
    foreach( $networks_settings as $key => $network ) {
        $networks[] = array( 'name' => $network['name'], 'code' => $network['code'], 'enable' => '0' );
    }
}

$api_settings['networks'] = $networks;

update_option( SK_SOCLALL_LOGIN_ACTIVATION_SETTINGS, $api_settings );

$basic_option = get_option( SK_SOCLALL_LOGIN_BASIC_SETTINGS );
$pass_secret = $basic_option['pass_secret'];
$basic_settings = array( 'pass_secret' => array( 'secret_key' => $pass_secret['secret_key'], 'secret_iv' => $pass_secret['secret_iv'] ) );

if( isset( $_POST['sk_soclall_login_display'] ) && is_array( $_POST['sk_soclall_login_display'] ) ) {
    $basic_settings['enable_display'] = $_POST['sk_soclall_login_display'];
} else
    $basic_settings['enable_display'] = array();

if( isset( $_POST['sk_soclall_login_title'] ) ) {
    $basic_settings['text_title'] = $_POST['sk_soclall_login_title'];
}

if( isset( $_POST['sk_soclall_login_redirection'] ) ) {
    $basic_settings['login_redirect_option'] = $_POST['sk_soclall_login_redirection'];
}

if( isset( $_POST['sk_soclall_login_custom_url'] ) ) {
    $basic_settings['login_redirect_link'] = $_POST['sk_soclall_login_custom_url'];
}

if( isset( $_POST['sk_soclall_login_send_email'] ) ) {
    $basic_settings['enable_send_email_notification'] = $_POST['sk_soclall_login_send_email'];
}

if( isset( $_POST['sk_soclall_login_apply_theme'] ) ) {
    $basic_settings['theme_applied'] = $_POST['sk_soclall_login_apply_theme'];
} else $basic_settings['theme_applied'] = 'default';

if( isset( $_POST['sk_soclall_login_theme_resize'] ) ) {
    $basic_settings['theme_resize'] = $_POST['sk_soclall_login_theme_resize'];
} else $basic_settings['theme_resize'] = '0';

if( isset( $_POST['sk_soclall_login_theme_col'] ) ) {
    $basic_settings['theme_cus_col'] = $_POST['sk_soclall_login_theme_col'];
} else $basic_settings['theme_cus_col'] = '1';

if( isset( $_POST['sk_soclall_login_theme_width'] ) ) {
    $basic_settings['theme_cus_width'] = $_POST['sk_soclall_login_theme_width'];
} else $basic_settings['theme_cus_width'] = '100';

if( isset( $_POST['sk_soclall_login_theme_text'] ) ) {
    $basic_settings['theme_cus_text'] = $_POST['sk_soclall_login_theme_text'];
} else $basic_settings['theme_cus_text'] = 'Login with ';

if( isset( $_POST['sk_soclall_login_theme_align'] ) ) {
    $basic_settings['theme_cus_align'] = $_POST['sk_soclall_login_theme_align'];
} else $basic_settings['theme_cus_align'] = 'l';

if( isset( $_POST['sk_soclall_login_theme_pos'] ) ) {
    $basic_settings['theme_cus_pos'] = $_POST['sk_soclall_login_theme_pos'];
} else $basic_settings['theme_cus_pos'] = 'l';

if( isset( $_POST['sk_soclall_login_ebay_site'] ) ) {
    $basic_settings['ebay_site'] = $_POST['sk_soclall_login_ebay_site'];
} else $basic_settings['ebay_site'] = 'US';

update_option( SK_SOCLALL_LOGIN_BASIC_SETTINGS, $basic_settings );

$_SESSION['sk_soclall_login_message'] = __( 'Settings updated successfully!', SK_SOCIALALL_LOGIN_TEXT_DOMAIN );
wp_redirect( admin_url(). "admin.php?page=" . SK_SOCIALALL_LOGIN_TEXT_DOMAIN );
exit;
?>