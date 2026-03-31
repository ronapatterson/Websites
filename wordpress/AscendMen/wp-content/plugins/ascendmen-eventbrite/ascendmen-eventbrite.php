<?php
/**
 * Plugin Name:  AscendMen Eventbrite Integration
 * Description:  Camp registration via Eventbrite API with custom branded form.
 * Version:      1.0.0
 * Author:       AscendMen
 * Text Domain:  ascendmen-eventbrite
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'AM_EB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once AM_EB_PLUGIN_DIR . 'includes/class-eventbrite-api.php';
require_once AM_EB_PLUGIN_DIR . 'includes/class-registration-form.php';

add_action( 'plugins_loaded', function() {
    $api_key = get_option( 'am_eventbrite_api_key', '' );
    $api     = new AscendMen_Eventbrite_API( $api_key );
    $form    = new AscendMen_Registration_Form( $api );
    $form->init();
});

// Admin setting for API key
add_action( 'admin_menu', function() {
    add_options_page(
        'Eventbrite Settings',
        'Eventbrite',
        'manage_options',
        'am-eventbrite',
        function() {
            if ( isset( $_POST['am_eb_nonce'] ) && current_user_can( 'manage_options' ) && wp_verify_nonce( $_POST['am_eb_nonce'], 'am_eb_save' ) ) {
                update_option( 'am_eventbrite_api_key', sanitize_text_field( $_POST['am_eventbrite_api_key'] ) );
                echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
            }
            $key = get_option( 'am_eventbrite_api_key', '' );
            ?>
            <div class="wrap">
              <h1>Eventbrite Integration</h1>
              <form method="post">
                <?php wp_nonce_field( 'am_eb_save', 'am_eb_nonce' ); ?>
                <table class="form-table">
                  <tr>
                    <th>Eventbrite API Key</th>
                    <td><input type="text" name="am_eventbrite_api_key" value="<?php echo esc_attr($key); ?>" class="regular-text"></td>
                  </tr>
                </table>
                <?php submit_button(); ?>
              </form>
            </div>
            <?php
        }
    );
});
