<?php

namespace App\Http\Controllers\Verification;

use App\Http\Controllers\Controller;
use App\Models\VerificationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SmsCodeController extends Controller
{
    public function sendSmsCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|digits:10|numeric', 
            'terms' => 'required|boolean', 
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $phoneRegex = '/^[0-9]{10}$/';
        if (!preg_match($phoneRegex, $request->phone)) {
            return response()->json(['errors' => ['phone' => ['Неверный формат номера телефона.']]], 422);
        }

        if (!$request->terms) {
            return response()->json(['errors' => ['terms' => ['Необходимо принять условия.']]], 422);
        }

        $existingUser = User::where('phone', $request->phone)->first();
        if ($existingUser) {
            return response()->json(['errors' => ['phone' => ['Номер телефона уже зарегистрирован.']]], 422);
        }

        $user = User::updateOrCreate(
            ['phone' => $request->phone],
            ['terms' => $request->terms]
        );

        $code = random_int(100000, 999999);

        VerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
        ]);

        return response()->json(['message' => 'Код SMS успешно отправлен']);
    }
}
