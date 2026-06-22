<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\User;

class UserRegistrationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that duplicate phone numbers are caught by validation.
     */
    public function test_duplicate_phone_number_fails_validation(): void
    {
        // 1. Create a user with a specific phone number
        $phone = '9898989898';
        User::create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'phone' => $phone,
            'password' => bcrypt('password'),
        ]);

        // 2. Attempt to register another user with the same phone number but different country code
        $response = $this->postJson(route('api.register'), [
            'name' => 'New User Registration',
            'phone' => $phone,
            'country_code' => '+1', // Different country code to trigger potential original custom rule bypass
            'otp_message_type' => 'whatsapp',
        ]);

        // 3. Assert that validation fails instead of throwing a unique constraint DB error
        $response->assertStatus(201);
        
        $errors = $response->json('errors');
        $this->assertEquals(
            'Mobile number is already linked to another account.',
            $errors['phone'][0]
        );
    }
}
