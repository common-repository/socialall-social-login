<?php if( ! defined( 'ABSPATH' ) ) exit; ?>
<tr>
    <th scope="row"><?php _e( 'SocialAll - Connect to networks', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></th>
    <td>
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e( 'SocialAll - Connect to networks', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></span></legend>
            <?php 
                $api_settings = get_option( SK_SOCLALL_LOGIN_ACTIVATION_SETTINGS );
                $api = array( 'app_id' => $api_settings['api']['app_id'], 'secret_key' => $api_settings['api']['secret_key'] );
                $soclall = new Soclall($api);
                
                foreach( $api_settings['networks'] as $key => $value ) :
            ?>
            <div class="sk-soclall-login-connect-network sa-default">
                <span class="sa sa-<?php echo $value['code']; ?> sk-soclall-login-icon" title="<?php echo $value['name']; ?>"></span>
                <?php 
                    $check_connect = get_user_meta( $user->ID, "sk_login_" . $value['code'], true );
                    if( !empty( $check_connect ) ) {
                        $user_connected = get_user_meta( $user->ID, "sk_login_" . $value['code'] . "_username", true );
                ?>
                    <p><?php _e( 'Connected to ', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?><b><?php echo !empty($user_connected) ? $user_connected : $value['name']; ?></b>
                    &nbsp;( <a href="?sk_soclall_login=remove_connection&network=<?php echo $value['code']; ?>" onclick="return confirm('<?php _e( 'Are you sure you want to remove the connection?', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?>')"><?php _e( 'Remove connection', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></a>&nbsp;)</p>
                <?php } else { ?>
                    <?php $href = $soclall->getLoginUrl( $value['code'], get_edit_user_link() . '?sk_soclall_login=connect', 'user' ); ?>
                    <p><a href="<?php echo $href; ?>"><?php _e( 'Connect', SK_SOCIALALL_LOGIN_TEXT_DOMAIN ); ?></a></p>
                <?php } ?>
            </div>
            <?php endforeach; ?>
        </fieldset>
    </td>
</tr>