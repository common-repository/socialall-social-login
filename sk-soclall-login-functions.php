<?php if( ! defined( 'ABSPATH' )) exit; ?>
<?php 
if( !class_exists( 'SK_Soclall_Login_Functions' ) ) {
    class SK_Soclall_Login_Functions {
        function encrypt_decrypt($action, $string) {
            $output = false;
            $encrypt_method = "AES-256-CBC";
            
            $basic_settings = get_option( SK_SOCLALL_LOGIN_BASIC_SETTINGS );
            $pass_secret = $basic_settings['pass_secret'];
            $secret_key = $pass_secret['secret_key'];
            $secret_iv = $pass_secret['secret_iv'];
        
            $key = hash( 'sha256', $secret_key );
            
            $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
        
            if( $action == 'encrypt' ) {
                $output = openssl_encrypt( $string, $encrypt_method, $key, 0, $iv );
                $output = base64_encode( $output );
            }
            elseif( $action == 'decrypt' ){
                $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
            }
        
            return $output;
        }
    }
}
?>