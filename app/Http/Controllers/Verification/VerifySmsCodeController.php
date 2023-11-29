<?php

namespace App\Http\Controllers\Verification;

use App\Http\Controllers\Controller;
use App\Models\VerificationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VerifySmsCodeController extends Controller
{
    public function verifySmsCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|digits:10',
            'code' => 'required|digits:6',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('phone', $request->phone)->first();
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $verificationCode = VerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('finished', false)  
            ->first();

        if (!$verificationCode) {
            return response()->json(['error' => 'Invalid code'], 422);
        }

        $createdAt = $verificationCode->created_at;
        $expirationTime = now()->subMinutes(5);
    
        if ($createdAt->diffInMinutes($expirationTime) <= 0) {
            return response()->json(['error' => 'Code has expired'], 422);
        }
    
        $verificationCode->update(['finished' => true]);
    
        $token = $user->createToken('verification-token')->accessToken;
    
        return response()->json(['token' => $token]);
    }
    
    
}
