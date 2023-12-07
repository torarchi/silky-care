<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
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
        ]);

        $response = $this->postJson('/api/v1/verify-sms-code', [
            'phone' => $user->phone,
            'code' => $verificationCode->code,
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure(['token']);
    }

    public function test_invalid_code()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/verify-sms-code', [
            'phone' => $user->phone,
            'code' => '123456',
        ]);

        $response->assertStatus(422);
        $response->assertJson(['error' => 'Неверный код.']);
    }

    public function test_expired_code()
    {
        $user = User::factory()->create();

        $verificationCode = VerificationCode::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subMinutes(10), 
        ]);

        $response = $this->postJson('/api/v1/verify-sms-code', [
            'phone' => $user->phone,
            'code' => $verificationCode->code,
        ]);

        $response->assertStatus(423);
        $response->assertJson(['error' => 'Срок действия кода истек']);
    }

    public function test_max_attempts_exceeded()
    {
        $user = User::factory()->create([
            'attempts' => 7
        ]);

        $verificationCode = VerificationCode::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->postJson('/api/v1/verify-sms-code', [
            'phone' => $user->phone,
            'code' => $verificationCode->code,
        ]);

        $response->assertStatus(424);
        $response->assertJson(['error' => 'Превышено количество попыток.']);
    }
}
