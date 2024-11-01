<?php
if (!defined('ABSPATH'))
    exit;

$soclall_settings = array();
$basic_option = get_option(SK_SOCLALL_LOGIN_ACTIVATION_SETTINGS);

if (isset($_GET['action']) && $_GET['action'] == 'sk_soclall_login_restore_default_settings')
    $soclall_settings['api'] = $basic_option['api'];
else
    $soclall_settings['api'] = array('app_id' => '', 'secret_key' => '');

$soclall_settings['networks'] = array(
    array('name' => 'Facebook', 'code' => 'facebook', 'enable' => '0'),
    array('name' => 'Twitter', 'code' => 'twitter', 'enable' => '0'),
    array('name' => 'Google Plus', 'code' => 'google', 'enable' => '0'),
    array('name' => 'LinkedIn', 'code' => 'linkedin', 'enable' => '0'),
    array('name' => 'Microsoft Live', 'code' => 'live', 'enable' => '0'),
    array('name' => 'Plurk', 'code' => 'plurk', 'enable' => '0'),
    array('name' => 'Tumblr', 'code' => 'tumblr', 'enable' => '0'),
    array('name' => 'Mail.ru', 'code' => 'mailru', 'enable' => '0'),
    array('name' => 'Reddit', 'code' => 'reddit', 'enable' => '0'),
    array('name' => 'Last.fm', 'code' => 'lastfm', 'enable' => '0'),
    array('name' => 'Vkontakte', 'code' => 'vkontakte', 'enable' => '0'),
    array('name' => 'Disqus', 'code' => 'disqus', 'enable' => '0'),
    array('name' => 'Foursquare', 'code' => 'foursquare', 'enable' => '0'),
    array('name' => 'Wordpress', 'code' => 'wordpress', 'enable' => '0'),
    array('name' => 'Github', 'code' => 'github', 'enable' => '0'),
    array('name' => 'Instagram', 'code' => 'instagram', 'enable' => '0'),
    array('name' => 'Amazon', 'code' => 'amazon', 'enable' => '0'),
    array('name' => 'Ebay', 'code' => 'ebay', 'enable' => '0')
);

update_option(SK_SOCLALL_LOGIN_ACTIVATION_SETTINGS, $soclall_settings);

$basic_settings = array(
    'pass_secret' => array('secret_key' => 'qsrgukpzdvhm', 'secret_iv' => '1qsxcft6yjmko0'),
    'text_title' => 'Login by SocialAll:',
    'enable_display' => array('login', 'register', 'comment'),
    'login_redirect_option' => 'current',
    'login_redirect_link' => '',
    'enable_send_email_notification' => '0',
    'theme_applied' => 'default',
    'theme_resize' => '0',
    'theme_cus_col' => '1',
    'theme_cus_pos' => 'l',
    'theme_cus_align' => 'l',
    'theme_cus_width' => '100',
    'theme_cus_text' => 'Login with ',
    'ebay_site' => 'US'
);

update_option(SK_SOCLALL_LOGIN_BASIC_SETTINGS, $basic_settings);
