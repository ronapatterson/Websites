<?php
class Test_Eventbrite_API extends WP_UnitTestCase {

    public function test_get_event_returns_null_without_api_key() {
        $api = new AscendMen_Eventbrite_API( '' );
        $result = $api->get_event( '12345' );
        $this->assertNull( $result );
    }

    public function test_build_attendee_payload_includes_required_fields() {
        $api = new AscendMen_Eventbrite_API( 'fake-key' );
        $payload = $api->build_attendee_payload([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => 'john@example.com',
        ]);
        $this->assertArrayHasKey( 'attendees', $payload );
        $this->assertEquals( 'John', $payload['attendees'][0]['profile']['first_name'] );
        $this->assertEquals( 'Doe',  $payload['attendees'][0]['profile']['last_name'] );
        $this->assertEquals( 'john@example.com', $payload['attendees'][0]['profile']['email'] );
    }

    public function test_build_attendee_payload_rejects_missing_email() {
        $api = new AscendMen_Eventbrite_API( 'fake-key' );
        $this->expectException( InvalidArgumentException::class );
        $api->build_attendee_payload([
            'first_name' => 'John',
            'last_name'  => 'Doe',
        ]);
    }

    public function test_build_attendee_payload_rejects_missing_first_name() {
        $api = new AscendMen_Eventbrite_API( 'fake-key' );
        $this->expectException( InvalidArgumentException::class );
        $api->build_attendee_payload([
            'last_name' => 'Doe',
            'email'     => 'john@example.com',
        ]);
    }

    public function test_build_attendee_payload_rejects_missing_last_name() {
        $api = new AscendMen_Eventbrite_API( 'fake-key' );
        $this->expectException( InvalidArgumentException::class );
        $api->build_attendee_payload([
            'first_name' => 'John',
            'email'      => 'john@example.com',
        ]);
    }

    public function test_register_attendee_returns_wp_error_without_api_key() {
        $api = new AscendMen_Eventbrite_API( '' );
        $result = $api->register_attendee( '12345', [
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => 'john@example.com',
        ]);
        $this->assertInstanceOf( WP_Error::class, $result );
        $this->assertEquals( 'no_api_key', $result->get_error_code() );
    }
}
