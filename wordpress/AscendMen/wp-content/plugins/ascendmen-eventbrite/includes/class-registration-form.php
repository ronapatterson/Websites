<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class AscendMen_Registration_Form {

    private AscendMen_Eventbrite_API $api;

    public function __construct( AscendMen_Eventbrite_API $api ) {
        $this->api = $api;
    }

    public function init(): void {
        add_shortcode( 'ascendmen_camp_register', [ $this, 'render_form' ] );
        add_action( 'wp_ajax_am_camp_register',        [ $this, 'handle_submission' ] );
        add_action( 'wp_ajax_nopriv_am_camp_register', [ $this, 'handle_submission' ] );
    }

    public function render_form( array $atts ): string {
        $atts    = shortcode_atts( [ 'event_id' => '' ], $atts );
        $nonce   = wp_create_nonce( 'am_camp_register' );
        $ajax    = admin_url( 'admin-ajax.php' );
        ob_start();
        ?>
        <div class="am-camp-register-wrap">
          <form id="am-camp-register-form" data-event-id="<?php echo esc_attr( $atts['event_id'] ); ?>">
            <div class="am-form-group">
              <label>First Name</label>
              <input type="text" name="first_name" required class="am-input">
            </div>
            <div class="am-form-group">
              <label>Last Name</label>
              <input type="text" name="last_name" required class="am-input">
            </div>
            <div class="am-form-group">
              <label>Email Address</label>
              <input type="email" name="email" required class="am-input">
            </div>
            <input type="hidden" name="action"   value="am_camp_register">
            <input type="hidden" name="event_id" value="<?php echo esc_attr( $atts['event_id'] ); ?>">
            <input type="hidden" name="_nonce"   value="<?php echo esc_attr( $nonce ); ?>">
            <button type="submit" class="am-btn-primary">Register for Camp</button>
            <div class="am-form-message" style="display:none;"></div>
          </form>
          <script>
          document.getElementById('am-camp-register-form').addEventListener('submit', function(e) {
            e.preventDefault();
            var form = e.target;
            var msg  = form.querySelector('.am-form-message');
            var data = new FormData(form);
            fetch('<?php echo esc_url($ajax); ?>', { method:'POST', body: data })
              .then(r => r.json())
              .then(function(res) {
                msg.style.display = 'block';
                msg.textContent = res.data.message;
                msg.style.color = res.success ? '#29ABE2' : '#e94560';
                if (res.success) form.reset();
              });
          });
          </script>
        </div>
        <?php
        return ob_get_clean();
    }

    public function handle_submission(): void {
        if ( ! wp_verify_nonce( $_POST['_nonce'] ?? '', 'am_camp_register' ) ) {
            wp_send_json_error( [ 'message' => 'Security check failed.' ] );
        }
        $data = [
            'first_name' => sanitize_text_field( $_POST['first_name'] ?? '' ),
            'last_name'  => sanitize_text_field( $_POST['last_name']  ?? '' ),
            'email'      => sanitize_email(      $_POST['email']       ?? '' ),
        ];
        $event_id = sanitize_text_field( $_POST['event_id'] ?? '' );
        if ( empty( $event_id ) ) {
            wp_send_json_error( [ 'message' => 'Event ID missing.' ] );
        }
        $result = $this->api->register_attendee( $event_id, $data );
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( [ 'message' => $result->get_error_message() ] );
        }
        wp_send_json_success( [ 'message' => 'You\'re registered! Check your email for confirmation.' ] );
    }
}
