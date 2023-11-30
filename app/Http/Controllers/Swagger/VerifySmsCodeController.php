<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Post(
 *      path="/api/v1/verify-sms-code",
 *      operationId="verify",
 *      tags={"Verification"},
 *      summary="Verify SMS Code",
 *      description="Verify the SMS code for a user.",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"phone", "code"},
 *              @OA\Property(property="phone", type="string", format="numeric", example="1234567890"),
 *              @OA\Property(property="code", type="string", format="numeric", example="123456"),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(property="token", type="string", description="Access token"),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="User not found",
 *          @OA\JsonContent(
 *              @OA\Property(property="error", type="string", description="Error message"),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error or invalid code",
 *          @OA\JsonContent(
 *              @OA\Property(property="error", type="string", description="Error message"),
 *          ),
 *      ),
 * )
 */

class VerifySmsCodeController extends Controller{}
