<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class AscendMen_Eventbrite_API {

    private string $api_key;
    private string $base_url = 'https://www.eventbriteapi.com/v3';

    public function __construct( string $api_key ) {
        $this->api_key = $api_key;
    }

    public function get_event( string $event_id ): ?array {
        if ( empty( $this->api_key ) ) {
            return null;
        }
        $response = wp_remote_get(
            "{$this->base_url}/events/{$event_id}/",
            [ 'headers' => [ 'Authorization' => "Bearer {$this->api_key}" ] ]
        );
        if ( is_wp_error( $response ) ) {
            return null;
        }
        return json_decode( wp_remote_retrieve_body( $response ), true );
    }

    public function build_attendee_payload( array $data ): array {
        foreach ( ['first_name', 'last_name', 'email'] as $field ) {
            if ( empty( $data[ $field ] ) ) {
                throw new InvalidArgumentException( "Missing required field: {$field}" );
            }
        }
        return [
            'attendees' => [[
                'profile' => [
                    'first_name' => sanitize_text_field( $data['first_name'] ),
                    'last_name'  => sanitize_text_field( $data['last_name'] ),
                    'email'      => sanitize_email( $data['email'] ),
                ],
            ]],
        ];
    }

    public function register_attendee( string $event_id, array $attendee_data ): array|WP_Error {
        if ( empty( $this->api_key ) ) {
            return new WP_Error( 'no_api_key', 'Eventbrite API key not configured.' );
        }
        $payload = $this->build_attendee_payload( $attendee_data );
        $response = wp_remote_post(
            "{$this->base_url}/events/{$event_id}/attendees/",
            [
                'headers' => [
                    'Authorization' => "Bearer {$this->api_key}",
                    'Content-Type'  => 'application/json',
                ],
                'body' => wp_json_encode( $payload ),
            ]
        );
        if ( is_wp_error( $response ) ) {
            return $response;
        }
        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        $code = wp_remote_retrieve_response_code( $response );
        if ( $code >= 400 ) {
            return new WP_Error(
                'eventbrite_error',
                $body['error_description'] ?? 'Unknown Eventbrite error',
                [ 'status' => $code ]
            );
        }
        return $body;
    }
}
