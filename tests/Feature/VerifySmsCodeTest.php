<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VerifySmsCodeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    
    public function test_verify_sms_code_and_get_token()
    {
        $user = User::factory()->create();

        $verificationCode = VerificationCode::factory()->create([
            'user_id' => $user->id,
            'finished' => false,
        ]);

        $response = $this->postJson('/api/v1/verify-sms-code', [
            'phone' => $user->phone,
            'code' => $verificationCode->code,
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure(['token']);

        $user->refresh();
    }

    public function test_invalid_code()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/verify-sms-code', [
            'phone' => $user->phone,
            'code' => '123456', // Недопустимый код
        ]);

        $response->assertStatus(422);
        $response->assertJson(['error' => 'Invalid code']);
    }

    public function test_user_not_found()
    {
        $response = $this->postJson('/api/v1/verify-sms-code', [
            'phone' => '1234567890', // Номер телефона несуществующего пользователя
            'code' => '654321',
        ]);

        $response->assertStatus(404);
        $response->assertJson(['error' => 'User not found']);
    }
}
