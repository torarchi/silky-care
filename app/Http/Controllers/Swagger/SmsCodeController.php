<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;

/**
* @OA\Post(
*      path="/api/v1/send-sms-code",
*      operationId="sendSmsCode",
*      tags={"Verification"},
*      summary="Send SMS Code",
*      description="Send SMS code for user registration.",
*      @OA\RequestBody(
*          required=true,
*          @OA\JsonContent(
*              required={"phone", "terms"},
*              @OA\Property(property="phone", type="string", format="numeric", example="1234567890"),
*              @OA\Property(property="terms", type="boolean", example=true),
*          ),
*      ),
*      @OA\Response(
*          response=200,
*          description="Successful operation",
*          @OA\JsonContent(
*              @OA\Property(property="message", type="string", description="Success message"),
*          ),
*      ),
*      @OA\Response(
*          response=422,
*          description="Validation error or phone number already registered",
*          @OA\JsonContent(
*              @OA\Property(property="errors", type="object", description="Validation errors"),
*          ),
*      ),
* )
*/

class SmsCodeController extends Controller{}

