<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SendSmsCodeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_receive_sms_code()
    {
        $response = $this->postJson('/api/v1/send-sms-code', [
            'phone' => '7777777777',
            'terms' => true,
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure(['success']);

        $this->assertDatabaseHas('verification_codes', [
            'user_id' => User::first()->id,
        ]);
    }
    
    public function test_phone_validation_fail()
    {
        $response = $this->postJson('/api/v1/send-sms-code', [
            'phone' => '12345',
            'terms' => true,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['phone']);
    }

    public function test_terms_validation_fail()
    {
        $response = $this->postJson('/api/v1/send-sms-code', [
            'phone' => '7777777777',
            'terms' => false,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['terms']);
    }
}
