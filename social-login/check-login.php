<?php if (!defined('ABSPATH')) exit; ?>
<?php
if (!class_exists('Sk_SoclAll_Login_Check')) {
    class Sk_SoclAll_Login_Check extends SK_Soclall_Login_Functions
    {
        function __construct()
        {
            if (isset($_REQUEST['sk_soclall_login'])) {
                switch ($_REQUEST['sk_soclall_login']) {
                    case 'login':
                        $this->check_login();
                        break;
                    case 'register_email':
                        $this->complete_register_email();
                        break;
                    case 'check_email':
                        $this->check_email_is_existed();
                        break;
                    case 'remove_connection':
                        $this->remove_connection();
                        break;
                    case 'connect':
                        $this->update_soclall_user_meta();
                        break;
                    case 'confirm_pass':
                        $this->complete_confirm_pass();
                        break;
                }
            }
        }

        function check_login()
        {
            if (!isset($_GET['token'])) {
                exit($this->show_message_error("An error occurred while connect to SoclAll!"));
            }

            $soclall_user = $this->get_soclall_user($_GET['token']);

            if (is_array($soclall_user) && !empty($soclall_user))
                $soclall_id = ($_GET['network'] == 'tumblr') ? $soclall_user['profile_url'] : $soclall_user['id'];

            if (isset($soclall_id) && !empty($soclall_id) && isset($_GET['network'])) {

                $wp_user_id = $this->check_socl_user_by_id($soclall_id, $_GET['network']);
                if ($wp_user_id)
                    exit($this->login_socl_user($wp_user_id));

                if (!empty($soclall_user['email'])) {
                    $wp_user_id = $this->check_socl_user_by_email($soclall_user['email']);
                    if ($wp_user_id)
                        exit($this->show_message_error(sprintf(__('%s: the email is existed! If is your, please login and connect to %s.', SK_SOCIALALL_LOGIN_TEXT_DOMAIN), $soclall_user['email'], ucfirst($_GET['network']))));
                    else
                        $wp_user_id = $this->add_new_wp_user($soclall_user, $_GET['network']);

                    if ($wp_user_id)
                        exit($this->login_socl_user($wp_user_id));
                    else
                        exit($this->show_message_error('An error occurred while register new account!'));
                } else {
                    exit($this->register_email_form($soclall_user));
                }
            } else {
                exit($this->show_message_error('An error occurred while get information of social account!'));
            }
        }

        public static function gen_current_page_url()
        {
            $url = 'http';
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
                $url .= "s";
            }
            $url .= "://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                $url .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
            } else {
                $url .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
            }

            return $url;
        }

        function check_socl_user_by_id($soclall_id, $network)
        {
            global $wpdb;
            $result = $wpdb->get_col("SELECT `user_id` FROM `{$wpdb->usermeta}` WHERE `meta_key` = 'sk_login_{$network}' AND `meta_value` = '{$soclall_id}'");
            if ($result)
                return $result[0];
            else return false;
        }

        function check_socl_user_by_email($email)
        {
            global $wpdb;
            $result = $wpdb->get_col("SELECT `ID` FROM `{$wpdb->users}` WHERE `user_email` = '{$email}'");
            if ($result)
                return $result[0];
            else return false;
        }

        function add_new_wp_user($soclall_user, $network)
        {
            $auto_password = wp_generate_password(8, false);
            $new_user_id = wp_create_user($soclall_user['email'], $auto_password, $soclall_user['email']);

            if ($new_user_id) {
                // send email notification
                $basic_option = get_option(SK_SOCLALL_LOGIN_BASIC_SETTINGS);
                if ($basic_option['enable_send_email_notification'] == '1')
                    $this->new_user_email_notifi($new_user_id, $auto_password);
                else
                    $this->new_user_email_notifi($new_user_id);

                // update user meta   
                $soclall_id = ($network == 'tumblr') ? $soclall_user['profile_url'] : $soclall_user['id'];
                $username = $this->gen_display_name($soclall_user);

                if (!empty($soclall_user['username']))
                    update_user_meta($new_user_id, 'nickname', $soclall_user['username']);

                update_user_meta($new_user_id, 'first_name', (!empty($soclall_user['first_name'])) ? $soclall_user['first_name'] : '');
                update_user_meta($new_user_id, 'last_name', (!empty($soclall_user['last_name'])) ? $soclall_user['last_name'] : '');
                update_user_meta($new_user_id, 'description', (!empty($soclall_user['about'])) ? $soclall_user['about'] : '');
                update_user_meta($new_user_id, 'sk_login_pass', $this->encrypt_decrypt('encrypt', $auto_password));
                update_user_meta($new_user_id, "sk_login_" . $network, $soclall_id);
                update_user_meta($new_user_id, "sk_login_" . $network . "_username", $username);
                wp_update_user(array('ID' => $new_user_id, 'display_name' => $username, 'user_url' => $this->gen_user_url($soclall_user)));
            }

            return $new_user_id;
        }

        function new_user_email_notifi($user_id, $password = '')
        {
            $user = new WP_User($user_id);
            $blogname = get_option('blogname');

            $message  = sprintf(__('New user registration by SoclAll Login on your site %s:'), $blogname) . "\r\n\r\n";
            $message .= sprintf(__('Username: %s'), stripslashes($user->user_login)) . "\r\n\r\n";
            $message .= sprintf(__('E-mail: %s'), stripslashes($user->user_email)) . "\r\n";

            @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration by SoclAll Login'), $blogname), $message);

            if (empty($password))
                return;

            $message  = sprintf(__('Hi, welcome to %s! Thanks for logged in with SoclAll Login!'), $blogname) . "\r\n\r\n" . __('Here is your login information:') . "\r\n";
            $message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n";
            $message .= sprintf(__('Password: %s'), $password) . "\r\n\r\n";
            $message .= __('Have fun!');

            wp_mail($user->user_email, sprintf(__('[%s] Your login information'), $blogname), $message);
        }

        public static function gen_display_name($soclall_user)
        {
            if (!empty($soclall_user['display_name']))
                return $soclall_user['display_name'];
            elseif (!empty($soclall_user['full_name']))
                return $soclall_user['full_name'];
            elseif (!empty($soclall_user['first_name']) && !empty($soclall_user['last_name']))
                return sprintf('%s %s', $soclall_user['first_name'], $soclall_user['last_name']);
            elseif (!empty($soclall_user['first_name']))
                return $soclall_user['first_name'];
            elseif (!empty($soclall_user['last_name']))
                return $soclall_user['last_name'];
            elseif (!empty($soclall_user['username']))
                return $soclall_user['username'];
            else
                return substr($soclall_user['email'], 0, strpos($soclall_user['email'], '@') - 1);
        }

        function gen_user_url($soclall_user)
        {
            if (!empty($soclall_user['website']))
                return $soclall_user['website'];
            elseif (!empty($soclall_user['profile_url']))
                return $soclall_user['profile_url'];
            else return '';
        }

        function login_socl_user($user_id, $register_email = false)
        {
            if (
                !isset($secure_cookie)
                && is_ssl()
                && force_ssl_login()
                && !force_ssl_admin()
                && (0 !== strpos($redirect_to, 'https'))
                && (0 === strpos($redirect_to, 'http'))
            ) {
                $secure_cookie = false;
            }

            if (!force_ssl_admin()) {
                if (get_user_option('use_ssl', $user_id)) {
                    $secure_cookie = true;
                    force_ssl_admin(true);
                }
            }
            
            $login_data = array(
                'user_login' => $this->get_user_info($user_id, 'user_login'),
                'user_password' => $this->encrypt_decrypt('decrypt', get_user_meta($user_id, 'sk_login_pass', true)),
                'remember' => (isset($_GET['remember_me'])) ? $_GET['remember_me'] : ''
            );
            $user = wp_signon($login_data, isset($secure_cookie) ? $secure_cookie : true);

            if (empty($_COOKIE[LOGGED_IN_COOKIE])) {
                if (headers_sent()) {
                    $user = new WP_Error('test_cookie', sprintf(
                        __('<strong>ERROR</strong>: Cookies are blocked due to unexpected output. For help, please see <a href="%1$s">this documentation</a> or try the <a href="%2$s">support forums</a>.'),
                        __('https://codex.wordpress.org/Cookies'),
                        __('https://wordpress.org/support/')
                    ));
                } elseif (empty($_COOKIE[TEST_COOKIE])) {
                    // If cookies are disabled we can't log in even with a valid user+pass
                    $user = new WP_Error('test_cookie', sprintf(
                        __('<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must <a href="%s">enable cookies</a> to use WordPress.'),
                        __('https://codex.wordpress.org/Cookies')
                    ));
                }
            }

            if (!is_wp_error($user)) {
                $basic_settings = get_option(SK_SOCLALL_LOGIN_BASIC_SETTINGS);
                if ($basic_settings['login_redirect_option'] == 'home')
                    $callback_url = home_url();
                elseif ($basic_settings['login_redirect_option'] == 'current') {
                    if (isset($_GET['sk_soclall_display']) && $_GET['sk_soclall_display'] == 'shortcode') {
                        $callback_url = remove_query_arg(array('sk_soclall_login', 'sk_soclall_display', 'token', 'network', 'expired_time'));

                        if (isset($secure_cookie) && false !== strpos($callback_url, 'wp-admin'))
                            $callback_url = preg_replace('|^http://|', 'https://', $callback_url);
                    } else
                        $callback_url = home_url();
                } elseif ($basic_settings['login_redirect_option'] == 'custom') {
                    if ($basic_settings['login_redirect_link'] != '')
                        $callback_url = site_url('/') . $basic_settings['login_redirect_link'];
                    else
                        $callback_url = home_url();
                }

                $user_login_url = apply_filters('login_redirect', $callback_url, $callback_url, $user);

                if (!$register_email)
                    exit(wp_safe_redirect($callback_url));
                else
                    return $callback_url;
            } else return false;
        }

        function get_user_info($user_id, $field)
        {
            global $wpdb;
            $result = $wpdb->get_col("SELECT `{$field}` FROM `{$wpdb->users}` WHERE `ID` = '{$user_id}'");
            if ($result)
                return $result[0];
            else return false;
        }

        function show_message_error($message)
        {
            return '<div style="width: 100%; padding: 20px 0px; border-radius: 8px; background: #f1f1f1; border: 3px solid #999; text-align: center; text-transform: uppercase; font-weight: bold; color: #666">'
                . $message .
                '</div>
                    <div style="width: 100%; text-align: center; margin-top: 40px; color: #ff0000;">The page will auto redirect to your Home page after 5 seconds...</div>    
                    <script>
        				setTimeout(function(){
        					window.location = "' . home_url() . '";
        				}, 5000);
        			</script>';
        }

        function register_email_form($soclall_user)
        {
            ob_start();
            include('register-email-form.php');
            $contents = ob_get_contents();
            ob_get_clean();

            return $contents;
        }

        function complete_register_email()
        {
            if (!isset($_POST['sk_soclall_login_email']) || !isset($_POST['token']) || !isset($_POST['network'])) {
                exit(json_encode(array('success' => '0', 'error' => 'Missing required parameters!')));
            }

            $soclall_user = $this->get_soclall_user($_POST['token']);

            if (is_array($soclall_user) && !empty($soclall_user)) {
                $soclall_user['email'] = $_POST['sk_soclall_login_email'];
                $wp_user_id = $this->add_new_wp_user($soclall_user, $_POST['network']);

                if ($wp_user_id) {
                    $login_success = $this->login_socl_user($wp_user_id, true);
                    if ($login_success)
                        exit(json_encode(array('success' => '1', 'url' => $login_success)));
                    else
                        exit(json_encode(array('success' => '0', 'error' => 'Login Fail! Please try again!')));
                } else
                    exit(json_encode(array('success' => '0', 'error' => 'An error occurred while register account! Please try again!')));
            } else {
                exit(json_encode(array('success' => '0', 'error' => 'An error occurred while get information of social account!')));
            }
        }

        function check_email_is_existed()
        {
            if (!isset($_POST['sk_soclall_login_email']) || !isset($_POST['network']))
                exit(json_encode(array('success' => '0', 'error' => 'Missing required parameters! Please try again!')));

            $wp_user_id = $this->check_socl_user_by_email($_POST['sk_soclall_login_email']);
            if ($wp_user_id)
                exit(json_encode(array('success' => '0', 'error' => sprintf(__('The email is existed! You can re-enter other email OR login and connect to %s', SK_SOCIALALL_LOGIN_TEXT_DOMAIN), ucfirst($_POST['network'])))));
            else exit(json_encode(array('success' => '1')));
        }

        function remove_connection()
        {
            if (!isset($_GET['network']))
                exit(json_encode(array('success' => '0', 'error' => 'Missing required parameters! Please try again!')));

            $current_user = wp_get_current_user();
            if ($current_user) {
                delete_user_meta($current_user->ID, "sk_login_" . $_GET['network']);
                delete_user_meta($current_user->ID, "sk_login_" . $_GET['network'] . "_username");

                exit(wp_safe_redirect(get_edit_user_link()));
            } else
                exit($this->show_message_error('An error occurred while get current user!'));
        }

        function update_soclall_user_meta($confirm_pass = false)
        {
            if (!isset($_REQUEST['token']) || !isset($_REQUEST['network'])) {
                exit($this->show_message_error("An error occurred while connect to SoclAll!"));
            }

            $current_user = wp_get_current_user();
            $sk_pass = get_user_meta($current_user->ID, 'sk_login_pass', true);
            if (empty($sk_pass))
                exit($this->confirm_pass_form());

            $soclall_user = $this->get_soclall_user($_REQUEST['token']);

            if (is_array($soclall_user) && !empty($soclall_user)) {
                $soclall_id = ($_REQUEST['network'] == 'tumblr')
                    ? $soclall_user['profile_url']
                    : $soclall_user['id'];

                // check if soclall id existed
                $wp_user_id = $this->check_socl_user_by_id($soclall_id, $_REQUEST['network']);
                if ($wp_user_id) {
                    if (!$confirm_pass)
                        exit($this->show_message_error(sprintf(__('This %s account is already connected. Please choose other account!', SK_SOCIALALL_LOGIN_TEXT_DOMAIN), ucfirst($_REQUEST['network']))));
                    else
                        return json_encode(array(
                            'success' => '0',
                            'error' => sprintf(
                                __(
                                    'This %s account is already connected. Please choose other account!',
                                    SK_SOCIALALL_LOGIN_TEXT_DOMAIN
                                ),
                                ucfirst($_REQUEST['network'])
                            )
                        ));
                }

                update_user_meta($current_user->ID, "sk_login_" . $_REQUEST['network'], $soclall_id);
                update_user_meta($current_user->ID, "sk_login_" . $_REQUEST['network'] . "_username", $this->gen_display_name($soclall_user));

                if (!$confirm_pass)
                    exit(wp_safe_redirect(get_edit_user_link()));
                else
                    return json_encode(array('success' => '1', 'url' => get_edit_user_link()));
            } else {
                if (!$confirm_pass)
                    exit($this->show_message_error('An error occurred while get information of social account!'));
                else
                    return json_encode(array('success' => '0', 'error' => 'An error occurred while get information of social account!'));
            }
        }

        function get_soclall_user($token)
        {
            $api_settings = get_option(SK_SOCLALL_LOGIN_ACTIVATION_SETTINGS);
            $api = array('app_id' => $api_settings['api']['app_id'], 'secret_key' => $api_settings['api']['secret_key']);
            $soclall = new Soclall($api);

            $soclall_user = $soclall->getUser($token);

            if (isset($soclall_user['error']))
                exit($this->show_message_error($soclall_user['error']));

            if (isset($soclall_user['success']))
                return $soclall_user['result'];
        }

        function confirm_pass_form()
        {
            ob_start();
            include('confirm-pass-form.php');
            $contents = ob_get_contents();
            ob_get_clean();

            return $contents;
        }

        function complete_confirm_pass()
        {
            if (!isset($_POST['token']) || !isset($_POST['network']) || !isset($_POST['sk_soclall_login_pass'])) {
                exit(json_encode(array('success' => '0', 'error' => 'Missing required parameters! Please try again!')));
            }

            $current_user = wp_get_current_user();
            if ($current_user && wp_check_password($_POST['sk_soclall_login_pass'], $current_user->data->user_pass, $current_user->ID)) {
                update_user_meta($current_user->ID, 'sk_login_pass', $this->encrypt_decrypt('encrypt', $_POST['sk_soclall_login_pass']));
                $connected = $this->update_soclall_user_meta(true);
                exit($connected);
            } else
                exit(json_encode(array('success' => '0', 'error' => 'Wrong Password! Please try again!')));
        }
    }
}

new Sk_SoclAll_Login_Check();

?>