<?php
/*
Plugin Name: Custom Global Login
Description: Custom global login & maintenance mode.
Version: 1.0
Author: gc
*/

if (!defined('ABSPATH')) exit;

// --- LOGIN CSS ---
function cgl_enqueue_login_styles() {
    wp_enqueue_style('cgl-login-css', plugin_dir_url(__FILE__) . 'assets/css/style.css', [], '1.0');
}
add_action('login_enqueue_scripts', 'cgl_enqueue_login_styles');

// --- WP VERSION CHECK ---
function custom_global_login_fail_wp_version() {
    $html_message = sprintf(
        '<div class="notice notice-error"><h3>%1$s</h3><p>%2$s <a href="https://go.elementor.com/wp-dash-update-wordpress/" target="_blank">%3$s</a></p></div>',
        esc_html__('Custom Global Login isn’t running because WordPress is outdated.', 'custom-global-login'),
        sprintf(esc_html__('Update to version %s and get back to creating!', 'custom-global-login'), '6.5'),
        esc_html__('Show me how', 'custom-global-login')
    );
    echo wp_kses_post($html_message);
}
function custom_global_login_check_wp_version() {
    global $wp_version;
    if (version_compare($wp_version, '6.5', '<')) {
        add_action('admin_notices', 'custom_global_login_fail_wp_version');
    }
}
add_action('admin_init', 'custom_global_login_check_wp_version');

// --- SETTINGS MENU ---
function cgl_add_settings_page() {
    add_options_page('Custom Global Login', 'Custom Global Login', 'manage_options', 'custom-global-login', 'cgl_render_settings_page');
}
add_action('admin_menu', 'cgl_add_settings_page');

// --- REGISTER SETTINGS ---
function cgl_register_settings() {
    register_setting('cgl_settings_group', 'cgl_login_logo');
    register_setting('cgl_settings_group', 'cgl_login_hero');
    register_setting('cgl_settings_group', 'cgl_primary_color');
    register_setting('cgl_settings_group', 'cgl_maintenance_mode', 'intval');
    register_setting('cgl_settings_group', 'cgl_maintenance_text');
    register_setting('cgl_settings_group', 'cgl_maintenance_bg_color');
    register_setting('cgl_settings_group', 'cgl_maintenance_text_color');
    register_setting('cgl_settings_group', 'cgl_maintenance_logo');
    register_setting('cgl_settings_group', 'cgl_maintenance_end_date');
    register_setting('cgl_settings_group', 'cgl_maintenance_title');
    register_setting('cgl_settings_group', 'cgl_custom_login_slug');

}
add_action('admin_init', 'cgl_register_settings');

// --- SETTINGS PAGE ---
function cgl_render_settings_page() { ?>
    <div class="wrap">
        <h1>Custom Global Login Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('cgl_settings_group'); ?>
            <table class="form-table">
                <tr>
                    <th>Login Logo</th>
                    <td>
                        <img id="cgl_logo_preview" src="<?php echo esc_url(get_option('cgl_login_logo')); ?>" style="max-width:200px;display:block;margin-bottom:10px;">
                        <input type="hidden" id="cgl_login_logo" name="cgl_login_logo" value="<?php echo esc_attr(get_option('cgl_login_logo')); ?>" />
                        <button class="button cgl-upload-logo">Upload Logo</button>
                    </td>
                </tr>
                <tr>
                    <th>Hero Image</th>
                    <td>
                        <img id="cgl_hero_preview" src="<?php echo esc_url(get_option('cgl_login_hero')); ?>" style="max-width:300px;display:block;margin-bottom:10px;">
                        <input type="hidden" id="cgl_login_hero" name="cgl_login_hero" value="<?php echo esc_attr(get_option('cgl_login_hero')); ?>" />
                        <button class="button cgl-upload-hero">Upload Hero Image</button>
                    </td>
                </tr>
                <tr>
                    <th>Primary Color</th>
                    <td>
                        <input type="text" name="cgl_primary_color" value="<?php echo esc_attr(get_option('cgl_primary_color', '#57215f')); ?>" class="cgl-color-field" data-default-color="#57215f">
                    </td>
                </tr>
               <tr>
                    <th>Maintenance Mode</th>
                    <td>
                        <label>
                            <input type="checkbox" name="cgl_maintenance_mode" value="1" <?php checked(1,get_option('cgl_maintenance_mode')); ?>>
                            Enable maintenance mode
                        </label>
                    </td>
                </tr>
                <tr>
                    <th>Maintenance Titel</th>
                    <td>
                        <input type="text" name="cgl_maintenance_title" value="<?php echo esc_attr(get_option('cgl_maintenance_title','We are working on the site. Come back later.')); ?>" style="width:100%;">
                    </td>
                </tr>
                <tr>
                    <th>Maintenance Text</th>
                    <td>
                        <input type="text" name="cgl_maintenance_text" value="<?php echo esc_attr(get_option('cgl_maintenance_text','We are working on the site. Come back later.')); ?>" style="width:100%;">
                    </td>
                </tr>
                <tr>
                    <th>Maintenance Background Color</th>
                    <td>
                        <input type="text" name="cgl_maintenance_bg_color" value="<?php echo esc_attr(get_option('cgl_maintenance_bg_color','#f2f2f2')); ?>" class="cgl-color-field">
                    </td>
                </tr>
                <tr>
                    <th>Maintenance Text Color</th>
                    <td>
                        <input type="text" name="cgl_maintenance_text_color" value="<?php echo esc_attr(get_option('cgl_maintenance_text_color','#444')); ?>" class="cgl-color-field">
                    </td>
                </tr>
                <tr>
                    <th>Maintenance Logo</th>
                    <td>
                        <img id="cgl_maintenance_logo_preview" src="<?php echo esc_url(get_option('cgl_maintenance_logo')); ?>" style="max-width:200px;display:block;margin-bottom:10px;">
                        <input type="hidden" id="cgl_maintenance_logo" name="cgl_maintenance_logo" value="<?php echo esc_attr(get_option('cgl_maintenance_logo')); ?>" />
                        <button class="button cgl-upload-maintenance-logo">Upload Logo</button>
                    </td>
                </tr>
                <tr>
                    <th>Maintenance End Date</th>
                    <td>
                        <input type="datetime-local"
                          name="cgl_maintenance_end_date"
                          value="<?php echo esc_attr(get_option('cgl_maintenance_end_date')); ?>"
                          style="width:250px;">
                        <p class="description">Choose date & time (ex: +2 hours)</p>
                        <p class="description">If no end date is set, maintenance mode will stay enabled until you turn it off manually.</p>
                    </td>
                </tr>

                <tr>
                 <th>Custom Login URL</th>
                 <td>
                    <input type="text"
                           name="cgl_custom_login_slug"
                           value="<?php echo esc_attr(get_option('cgl_custom_login_slug', 'my-login')); ?>"
                           style="width:200px;">
                    <p class="description">
                        Login URL will be: <code><?php echo home_url('/' . esc_attr(get_option('cgl_custom_login_slug','my-login'))); ?></code>
                    </p>
                </td>
            </tr>

            </table>
            <?php submit_button(); ?>
        </form>
        
        <div class="card" style="margin-top:20px;max-width:600px;padding:15px;">
            <h3>Troubleshooting "Not Found" Errors</h3>
            <p>If you see a <b>"Not Found"</b> error when visiting your custom login URL:</p>
            <ol>
                <li>Go to <b>Settings > Permalinks</b> and click "Save Changes".</li>
                <li>Ensure your server (Apache/Nginx) supports URL rewriting (<code>.htaccess</code>).</li>
                <li>If it still fails, try accessing: <code><?php echo home_url('/index.php/' . esc_attr(get_option('cgl_custom_login_slug','my-login'))); ?></code></li>
            </ol>
        </div>
    </div>
<?php }

// --- CONFLICT CHECK ---
function cgl_check_conflicts() {
    if (is_plugin_active('wps-hide-login/wps-hide-login.php')) {
        echo '<div class="notice notice-error"><p><b>Warning:</b> "WPS Hide Login" is active. This may conflict with "Custom Global Login". Please deactivate one of them.</p></div>';
    }
}
add_action('admin_notices', 'cgl_check_conflicts');

// --- ADMIN SCRIPTS ---
function cgl_admin_media_scripts($hook){
    if($hook !== 'settings_page_custom-global-login') return;
    wp_enqueue_media();
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('cgl-admin-js', plugin_dir_url(__FILE__).'assets/js/admin.js',['jquery'],'1.0',true);
    wp_enqueue_script('cgl-color-picker', plugin_dir_url(__FILE__).'assets/js/color-picker.js',['wp-color-picker'],false,true);
    wp_enqueue_script('cgl-datetimepicker', plugin_dir_url(__FILE__).'assets/js/datetimepicker.js',['jquery','jquery-ui-datepicker'],false,true);
    wp_enqueue_style('jquery-ui-css','https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css');
}
add_action('admin_enqueue_scripts','cgl_admin_media_scripts');

// --- APPLY LOGIN IMAGES & COLORS ---
function cgl_apply_custom_images() {
    $logo = get_option('cgl_login_logo');
    $hero = get_option('cgl_login_hero');
    $color = get_option('cgl_primary_color','#57215f'); ?>
    <style>
        <?php if($logo): ?> #login h1 a {background-image:url('<?php echo esc_url($logo); ?>');background-size:contain;width:320px;height:80px;} <?php endif; ?>
        <?php if($hero): ?> @media(min-width:767px){.login::before{background-image:url('<?php echo esc_url($hero); ?>');background-size:cover;background-position:center;}} <?php endif; ?>
        .login label,.login #backtoblog a,.login #nav a {color:<?php echo esc_html($color); ?> !important;}
        a:active,a:hover,.privacy-policy-link{color:<?php echo esc_html($color); ?> !important;}
        #wp-submit,.language-switcher .button{background-color:<?php echo esc_html($color); ?> !important;color:#fff;}
        #wp-auth-check-wrap .wp-auth-check-close{color:<?php echo esc_html($color); ?> !important;}
    </style>
<?php }
add_action('login_head','cgl_apply_custom_images');

// --- MAINTENANCE MODE ---
/*function cgl_maintenance_mode(){
    if(!get_option('cgl_maintenance_mode')) return;

    $allowed_roles = ['administrator','editor'];
    $current_user = wp_get_current_user();
    if(array_intersect($allowed_roles,$current_user->roles)) return;
    if(is_admin() || strpos($_SERVER['REQUEST_URI'],'wp-login.php')!==false) return;

    status_header(503);
    include plugin_dir_path(__FILE__).'maintenance.php';
    exit;
}
add_action('template_redirect','cgl_maintenance_mode');

/* =====================================================
 * MAINTENANCE MODE
 * ===================================================== */
function cgl_maintenance_mode() {

    // If maintenance not enabled → do nothing
    if (!get_option('cgl_maintenance_mode')) {
        return;
    }

    // Auto-disable ONLY if end date exists AND is expired
    $end = get_option('cgl_maintenance_end_date');
    if ($end && strtotime($end) <= current_time('timestamp')) {
        update_option('cgl_maintenance_mode', 0);
        return;
    }

    // Allow admins
    if (current_user_can('manage_options')) {
        return;
    }

    // Allow login access
    $slug = trim(get_option('cgl_custom_login_slug','my-login'),'/');
    if (
        is_admin() ||
        strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false ||
        strpos($_SERVER['REQUEST_URI'], '/' . $slug) !== false
    ) {
        return;
    }

    // Show maintenance page
    status_header(503);
    include plugin_dir_path(__FILE__) . 'maintenance.php';
    exit;
}
add_action('template_redirect', 'cgl_maintenance_mode');

function cgl_auto_disable_maintenance() {
    $end = get_option('cgl_maintenance_end_date');
    if (!empty($end) && strtotime($end) < current_time('timestamp')) {
        update_option('cgl_maintenance_mode', 0);
    }
}
add_action('init', 'cgl_auto_disable_maintenance');



/* =====================================================
 * CUSTOM LOGIN URL – FORCE ONLY CUSTOM SLUG
 * ===================================================== */
/* =====================================================
 * CUSTOM LOGIN URL – FORCE ONLY CUSTOM SLUG
 * ===================================================== */
function cgl_force_custom_login_only() {
    $slug = trim(get_option('cgl_custom_login_slug', 'my-login'), '/');

    // Allow logged-in admins
    if (is_user_logged_in() && current_user_can('manage_options')) return;

    $request_uri = $_SERVER['REQUEST_URI'];

    // Allow only custom login URL
    if (strpos($request_uri, '/' . $slug) !== false) return;

    // Block wp-login.php (but allow POST for login actions, and GET with 'action' for logout/lostpassword)
    if (strpos($request_uri, 'wp-login.php') !== false || strpos($request_uri, 'wp-admin') !== false) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') return; // Allow form submission
        if (isset($_GET['action'])) return; // Allow actions like logout
        
        // Redirect to custom login with index.php for compatibility
        wp_redirect(home_url('/index.php/' . $slug));
        exit;
    }
}
add_action('init', 'cgl_force_custom_login_only');

/* =====================================================
 * CUSTOM LOGIN REWRITE RULE
 * ===================================================== */
function cgl_add_login_rewrite() {
    $slug = trim(get_option('cgl_custom_login_slug', 'my-login'), '/');
    add_rewrite_rule("^{$slug}/?$", 'index.php?cgl_login=1', 'top');
}
add_action('init', 'cgl_add_login_rewrite');

function cgl_add_query_vars($vars) {
    $vars[] = 'cgl_login';
    return $vars;
}
add_filter('query_vars', 'cgl_add_query_vars');

function cgl_load_custom_login() {
    if (get_query_var('cgl_login') == 1) {
        require_once ABSPATH . 'wp-login.php';
        exit;
    }
}
add_action('template_redirect', 'cgl_load_custom_login');

/* =====================================================
 * FILTER LOGIN URL
 * ===================================================== */
function cgl_custom_login_url($login_url, $redirect, $force_reauth) {
    $slug = trim(get_option('cgl_custom_login_slug', 'my-login'), '/');
    // Force index.php for compatibility
    $login_url = home_url('/index.php/' . $slug);
    if (!empty($redirect)) {
        $login_url = add_query_arg('redirect_to', urlencode($redirect), $login_url);
    }
    return $login_url;
}
add_filter('login_url', 'cgl_custom_login_url', 10, 3);


/* =====================================================
 * EARLY URL DETECTION (AGGRESSIVE FALLBACK)
 * ===================================================== */
function cgl_early_url_detection() {
    // If it's already handled, skip
    if (defined('DOING_AJAX') || defined('DOING_CRON') || is_admin()) return;

    $slug = trim(get_option('cgl_custom_login_slug', 'my-login'), '/');
    $request_uri = $_SERVER['REQUEST_URI'];
    
    // Parse path component
    $path = trim(parse_url($request_uri, PHP_URL_PATH), '/');
    
    // Handle subdirectory installs
    $home_path = trim(parse_url(home_url(), PHP_URL_PATH), '/');
    if (!empty($home_path) && strpos($path, $home_path) === 0) {
        $path = trim(substr($path, strlen($home_path)), '/');
    }
    
    // Strip index.php if present
    if (strpos($path, 'index.php/') === 0) {
        $path = substr($path, 10); // remove index.php/
    } elseif ($path === 'index.php') {
        $path = '';
    }

    // Check robustly
    if ($path === $slug) {
        // Prepare globals for wp-login.php to avoid scope warnings
        global $user_login, $error, $action, $user_identity;
        
        // Force empty username on fresh GET request to avoid auto-filling
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
             $user_login = '';
             $error = '';
        } else {
            // Keep existing values or init if missing
            if (!isset($user_login)) $user_login = '';
            if (!isset($error)) $error = '';
        }
        
        status_header(200);
        require_once ABSPATH . 'wp-login.php';
        exit;
    }
}
add_action('init', 'cgl_early_url_detection', 1);

/* =====================================================
 * ACTIVATE PLUGIN – FLUSH RULES
 * ===================================================== */
function cgl_activate_plugin() {
    cgl_add_login_rewrite();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'cgl_activate_plugin');

function cgl_deactivate_plugin() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'cgl_deactivate_plugin');

/* =====================================================
 * FLUSH ON OPTION SAVE
 * ===================================================== */
function cgl_flush_on_slug_change($old_value, $value) {
    if ($old_value !== $value) {
        cgl_add_login_rewrite();
        flush_rewrite_rules();
    }
}
add_action('update_option_cgl_custom_login_slug', 'cgl_flush_on_slug_change', 10, 2);

/* =====================================================
 * SELF-HEAL REWRITE RULES
 * ===================================================== */
function cgl_check_rewrite_rules() {
    $slug = trim(get_option('cgl_custom_login_slug', 'my-login'), '/');
    $rules = get_option('rewrite_rules');
    if (!isset($rules["^{$slug}/?$"])) {
        cgl_add_login_rewrite();
        flush_rewrite_rules();
    }
}
add_action('admin_init', 'cgl_check_rewrite_rules');


// Redirect to custom login AFTER logout (instead of breaking the logout link)
function cgl_custom_logout_redirect($redirect_to, $requested_redirect_to, $user) {
    $slug = trim(get_option('cgl_custom_login_slug', 'my-login'), '/');
    return home_url('/index.php/' . $slug);
}
add_filter('logout_redirect', 'cgl_custom_logout_redirect', 10, 3);


/* =====================================================
 * EXPLICIT LOGOUT HANDLER (Bypass wp-login.php blockers)
 * ===================================================== */
function cgl_handle_logout_request() {
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        // Verify nonce if present (standard WP logout link)
        // We use check_admin_referer which dies if invalid, securing the action
        check_admin_referer('log-out');
        
        // Execute logout
        wp_logout();
        
        // Redirect to custom login
        $slug = trim(get_option('cgl_custom_login_slug', 'my-login'), '/');
        $redirect_to = home_url('/index.php/' . $slug);
        
        // Preserve redirect_to param if it existed and is safe
        if (!empty($_GET['redirect_to'])) {
            $redirect_to = add_query_arg('redirect_to', urlencode($_GET['redirect_to']), $redirect_to);
        }
        
        wp_redirect($redirect_to);
        exit;
    }
}
add_action('init', 'cgl_handle_logout_request', 1); // Run early


function cgl_fix_wp_login_warnings() {
    global $user_login, $error;

    if (!isset($user_login)) {
        $user_login = '';
    }

    if (!isset($error)) {
        $error = '';
    }
}
add_action('login_init', 'cgl_fix_wp_login_warnings');

