<?php
    include( 'top-login-form.php' );
    
    if( isset( $attrs['networks'] ) ) {
        $networks_attr = explode( ',', str_replace( ' ', '', $attrs['networks'] ) );
        $networks = array();
        
        foreach( $api_settings['networks'] as $key => $value ) {
            if( in_array( $value['code'], $networks_attr ) ) {
                $networks[] = array(
                    'name' => $value['name'],
                    'code' => $value['code'],
                    'enable' => $value['enable']
                );
            }
        }
    } else
        $networks = $api_settings['networks'];
    
    $callback_url .= "sk_soclall_login=login&sk_soclall_display=shortcode";
    $check_rememberme = false;
    
    include( 'bottom-login-form.php' );
?>