<?php

namespace App\Http\Controllers\Verification;

use App\Http\Controllers\Controller;
use App\Models\VerificationCode;
use App\Models\User;
use App\Http\Requests\VerifyCodeRequest;
use Illuminate\Support\Facades\Auth;

class VerifySmsCodeController extends Controller
{
    public function verify(VerifyCodeRequest $request)
    {
        $data = $request->validated();

        $user = User::where('phone', $request->phone)->first();

        $verificationCode = VerificationCode::where([
            ['user_id', '=', $user->id],
            ['code', '=', $request->code],
        ])->first();

        if (!$verificationCode || $verificationCode->code != $request->code) {
            $user->increment('attempts');
            return response()->json(['error' => 'Неверный код.'], 422);
        }

        if ($verificationCode->created_at->addMinutes(5)->isPast()) {
            return response()->json(['error' => 'Срок действия кода истек'], 423);
        }

        if ($user->attempts >= 6) {
            return response()->json(['error' => 'Превышено количество попыток.'], 424);
        }

        $user->update(['verification' => true]);
        $verificationCode->delete();
    
        $token = $user->createToken('verification-token')->accessToken;
    
        return response()->json(['token' => $user->createToken('verification-token')->accessToken]);
    }
}
