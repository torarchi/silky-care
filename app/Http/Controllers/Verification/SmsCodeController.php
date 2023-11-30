<?php

namespace App\Http\Controllers\Verification;

use App\Http\Controllers\Controller;
use App\Models\VerificationCode;
use App\Models\User;
use App\Http\Requests\SmsCodeRequest;

class SmsCodeController extends Controller
{
    public function send(SmsCodeRequest $request)
    {
        $data = $request->validated();

        $existingUser = User::where('phone', $request->phone)->first();

        if ($existingUser && $existingUser->verification) {
            return response()->json(['message' => 'Номер уже верифицирован. Воспользуйтесь восстановлением доступа.']);
        }

        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            ['terms' => true]
        );

        $code = random_int(100000, 999999);

        VerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
        ]);

        return response()->json(['success' => true]);
    }
}
