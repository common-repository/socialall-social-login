<?php
/**
 * Plugin Name: SocialAll - Social Login
 * Plugin URI: https://socialall.dev
 * Description: The plugin is the best way for user to login using existing social accounts and helps your user log in faster without complex sign up procedure.
 * Version: 2.5
 * Author: SocialAll
 * Author URI: https://socialall.dev
 * Text Domain: sk-soclall-login
 * License: GPL2+
 */
 
if( ! defined( 'ABSPATH' ))
    exit;
    
if( ! defined( 'SK_SOCLALL_LOGIN_VERSION' ) )
    define( 'SK_SOCLALL_LOGIN_VERSION', '2.5' );

if( ! defined( 'SK_SOCLALL_LOGIN_PLUGIN_PATH' ) )
    define( 'SK_SOCLALL_LOGIN_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
    
if( ! defined( 'SK_SOCLALL_LOGIN_CSS_URL' ) )
    define( 'SK_SOCLALL_LOGIN_CSS_URL', plugin_dir_url( __FILE__ ) . "css" );
    
if( ! defined( 'SK_SOCLALL_LOGIN_JS_URL' ) )
    define( 'SK_SOCLALL_LOGIN_JS_URL', plugin_dir_url( __FILE__ ) . "js" );
    
if( ! defined( 'SK_SOCLALL_LOGIN_IMAGES_URL' ) )
    define( 'SK_SOCLALL_LOGIN_IMAGES_URL', plugin_dir_url( __FILE__ ) . "images" );
    
if( ! defined( 'SK_SOCLALL_LOGIN_SHORTCODE' ) )
    define( 'SK_SOCLALL_LOGIN_SHORTCODE', 'SA_Login' );
    
if( ! defined( 'SK_SOCLALL_LOGIN_ACTIVATION_SETTINGS' ) )
    define( 'SK_SOCLALL_LOGIN_ACTIVATION_SETTINGS', 'sk_soclall_login_api_networks_settings' );
    
if( ! defined( 'SK_SOCLALL_LOGIN_BASIC_SETTINGS' ) )
    define( 'SK_SOCLALL_LOGIN_BASIC_SETTINGS', 'sk_soclall_login_settings' );
    
if( ! defined( 'SK_SOCIALALL_LOGIN_TEXT_DOMAIN' ) )
    define( 'SK_SOCIALALL_LOGIN_TEXT_DOMAIN', 'sk-soclall-login' );
    
include_once('sk-soclall-login-functions.php');
 
if( ! class_exists( 'SK_Soclall_Login' ) ) {
    class SK_Soclall_Login extends SK_Soclall_Login_Functions {
        
        private $is_show = false;
        private $api_settings = array();
        
        function __construct() {
            register_activation_hook( __FILE__, array( $this, 'sk_activation_plugin' ) ); // load settings default
            add_action( 'init', array( $this, 'load_plugin_text_domain' ) ); // load text domain
            add_action( 'admin_menu', array( $this, 'add_soclall_login_admin_menu' ) ); // add admin menu
            add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_assets' ) ); // load js, css file for admin
            add_action( 'admin_post_sk_soclall_login_save_settings', array( $this, 'update_settings' ) ); // save custom settings
            add_action( 'admin_post_sk_soclall_login_restore_default_settings', array( $this, 'restore_default_settings' ) ); // restore default settings
            add_action( 'personal_options', array( $this, 'connect_to_networks_options' ) ); // connect networks options in profile page of current user
            add_action( 'password_reset', array( $this, 'update_wp_user_pass' ) );
            
            add_shortcode( SK_SOCLALL_LOGIN_SHORTCODE, array( $this, 'load_shortcode' ) ); // create shortcode
            
            $this->api_settings = get_option( SK_SOCLALL_LOGIN_ACTIVATION_SETTINGS );
            if( !empty( $this->api_settings['api']['app_id'] ) && !empty( $this->api_settings['api']['secret_key'] ) ) {
                $this->is_show = true;
                add_action( 'wp_enqueue_scripts', array( $this, 'load_socl_login_assets' ) ); // load js, css file for social login form
                add_action( 'login_enqueue_scripts', array( $this, 'load_socl_login_assets' ) );
                add_action( 'init', array( $this, 'login_by_SoclAll' ) ); // callback function after get token
                
                $basic_settings = get_option( SK_SOCLALL_LOGIN_BASIC_SETTINGS );
                
                // add social login form to login_form, register_form, comment_form
                if( in_array( 'login', $basic_settings['enable_display'] ) )
                    add_action( 'login_form', array( $this, 'load_social_login_form' ) );
                    
                if( in_array( 'register', $basic_settings['enable_display'] ) ) {
                    add_action( 'register_form', array( $this, 'load_social_login_form' ) );
                    add_action( 'after_signup_form', array( $this, 'load_social_login_form' ) );
                }
                
                if( in_array( 'comment', $basic_settings['enable_display'] ) )
                    add_action( 'comment_form_top', array( $this, 'load_social_login_form_by_shortcode' ) );
            }
        }
        
        function sk_activation_plugin() {
            if( !get_option( SK_SOCLALL_LOGIN_ACTIVATION_SETTINGS ) && !get_option( SK_SOCLALL_LOGIN_BASIC_SETTINGS ))
                include( 'admin/default-settings.php' );
        }
        
        function load_plugin_text_domain() {
            if( ! function_exists('load_plugin_textdomain') )
                exit;
            load_plugin_textdomain(SK_SOCIALALL_LOGIN_TEXT_DOMAIN, false);
        }
        
        function add_soclall_login_admin_menu() {
            if( ! function_exists('add_menu_page') )
                exit;
            add_menu_page( 'SocialAll - Social Login', 'SocialAll - Social Login', 'manage_options', SK_SOCIALALL_LOGIN_TEXT_DOMAIN, array( $this, 'load_settings_page' ), SK_SOCLALL_LOGIN_IMAGES_URL . "/soclall-icon.png" );
        }
        
        function load_settings_page() {
            // 28-12-2015: update new networks
            include( 'admin/update-new-networks.php' );
            //end update
            include( 'lib/soclall_ebay_sites.php' );
            include( 'admin/settings-page.php' );
        }
        
        function load_admin_assets() {
            if( function_exists('wp_enqueue_style') ) {
                wp_enqueue_style( 'sk-soclall-login-sa-css', SK_SOCLALL_LOGIN_CSS_URL . '/socl_login_theme.css', '', SK_SOCLALL_LOGIN_VERSION );
                wp_enqueue_style( 'sk-soclall-login-profile-css', SK_SOCLALL_LOGIN_CSS_URL . '/profile.css', '', SK_SOCLALL_LOGIN_VERSION );   
            }
            
            if( isset( $_GET['page'] ) && $_GET['page'] == SK_SOCIALALL_LOGIN_TEXT_DOMAIN ) {
                // css
                if( function_exists('wp_enqueue_style') )
                    wp_enqueue_style( 'sk-soclall-login-admin-css', SK_SOCLALL_LOGIN_CSS_URL . '/admin.css', '', SK_SOCLALL_LOGIN_VERSION );
                    
                // js
                if( function_exists('wp_enqueue_script') )
                    wp_enqueue_script( 'sk-soclall-login-admin-js', SK_SOCLALL_LOGIN_JS_URL . '/admin.js', array( 'jquery' ), SK_SOCLALL_LOGIN_VERSION );
            }
        }
        
        function load_socl_login_assets() {
            // css
            if( function_exists('wp_enqueue_style') ) {
                wp_enqueue_style( 'sk-soclall-sa-css', SK_SOCLALL_LOGIN_CSS_URL . '/socl_login_theme.css', '', SK_SOCLALL_LOGIN_VERSION );
                wp_enqueue_style( 'sk-soclall-login-css', SK_SOCLALL_LOGIN_CSS_URL . '/socl-login.css', '', SK_SOCLALL_LOGIN_VERSION );
            }
                
            // js
                if( function_exists('wp_enqueue_script') )
                    wp_enqueue_script( 'sk-soclall-login-js', SK_SOCLALL_LOGIN_JS_URL . '/socl-login.js', array( 'jquery' ), SK_SOCLALL_LOGIN_VERSION );
        }
        
        function load_social_login_form() {
            if( ! is_user_logged_in() ) {
                include_once( 'lib/Soclall.php' );
                include( 'social-login/login-form.php' );
            }
        }
        
        function load_social_login_form_by_shortcode() {
            if( ! is_user_logged_in() )
                echo do_shortcode( "[" . SK_SOCLALL_LOGIN_SHORTCODE . "]" );
        }
        
        function load_shortcode( $attrs ) {
            if( is_user_logged_in() || $this->is_show === false )
                return;
            
            ob_start();
            include_once( 'lib/Soclall.php' );
            include( 'social-login/short-code.php' );
            $contents = ob_get_contents();
            ob_get_clean();
            
            return $contents;
        }
        
        function login_by_SoclAll() {
            include_once( 'lib/Soclall.php' );
            include( 'social-login/check-login.php' );
        }
        
        function update_settings() {
            if( ! function_exists('wp_verify_nonce') )
                exit;
            
            if( isset( $_POST['sk_soclall_login_save_settings'] ) && isset( $_POST['action_nonce'] ) && wp_verify_nonce( $_POST['action_nonce'], 'sk_soclall_login_save_settings_nonce' ) )
                include( 'admin/update-settings.php' );
            else
                $this->error_message_session_in_admin();
        }
        
        function restore_default_settings() {
            if( ! function_exists('wp_verify_nonce') )
                exit;
            
            if( isset( $_GET['action_nonce'] ) && wp_verify_nonce( $_GET['action_nonce'], 'sk_soclall_login_restore_default_settings_nonce' ) ) {
                include( 'admin/default-settings.php' );
                
                if (!session_id()) {
                    session_start();
                }
                $_SESSION['sk_soclall_login_message'] = __( 'Settings restored successfully!', SK_SOCIALALL_LOGIN_TEXT_DOMAIN );
                exit( wp_redirect( admin_url() . "admin.php?page=" . SK_SOCIALALL_LOGIN_TEXT_DOMAIN ) );
            } else
                $this->error_message_session_in_admin();
        }
        
        function error_message_session_in_admin() {
            if (!session_id()) {
                session_start();
            }
            $_SESSION['sk_soclall_login_message_error'] = __( 'Error occurred! Please try again!', SK_SOCIALALL_LOGIN_TEXT_DOMAIN );
            wp_redirect( admin_url() . "admin.php?page=" . SK_SOCIALALL_LOGIN_TEXT_DOMAIN );
        }
        
        function connect_to_networks_options( $user ) {
            if(get_current_user_id() == $user->ID) {
                include_once( 'lib/Soclall.php' );
                include( 'admin/connect-to-networks-options.php' );
            }
        }
        
        function update_wp_user_pass( $user, $new_pass ) {
            if( ! function_exists('update_user_meta') )
                exit;
            update_user_meta( $user->ID, 'sk_login_pass', $this->encrypt_decrypt( 'encrypt', $new_pass ) );
        }
    }
}
 
new SK_Soclall_Login();

?>