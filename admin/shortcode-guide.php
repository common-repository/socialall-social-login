<div class="sk-soclall-login-shortcode">[<?php echo SK_SOCLALL_LOGIN_SHORTCODE; ?>]</div>
<div class="sk-soclall-login-shortcode-how-to">
    <span><?php _e( 'You can add Short Code to display the Social Login form in the contents of a post or page:', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></span>
    <ol>
        <li>In Admin menu, select <b>Posts</b> &rarr; <b>Add New</b> OR select <b>Pages</b> &rarr; <b>Add New</b></li>
        <li>Copy Shortcode and paste on where you want to display Social Login form.</li>
        <li>You can also use <b>networks</b> attribute to customize what networks you want to show. Example: [<?php echo SK_SOCLALL_LOGIN_SHORTCODE; ?> <b>networks=facebook,google,twitter,live</b>]</li>
    </ol>
    <table class="sk-soclall-login-shortcode-table">
        <tr>
            <td><i>Network Name</i></td>
            <td><i>Code</i></td>
        </tr>
        <?php foreach( $this->api_settings['networks'] as $key => $network ) : ?>
        <tr>
            <td><?php echo $network['name']; ?></td>
            <td><b><?php echo $network['code']; ?></b></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>