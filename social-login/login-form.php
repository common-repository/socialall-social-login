<?php
    include( 'top-login-form.php' );
        
    $callback_url .= "sk_soclall_login=login";
    $networks = $api_settings['networks'];
    $check_rememberme = true;
    
    include( 'bottom-login-form.php' );  
?>

<script type="text/javascript">
    jQuery(function($) {
        if($('form#registerform').length)
            $('form#registerform p:first-child').before($('#sk-soclall-login-form'));
        if($('form#loginform').length)
            $('form#loginform p:first-child').before($('#sk-soclall-login-form'));
    });
</script>