<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
/**
 * @OA\Post(
 *     path="/api/login",
 *     summary=" Login with number ",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"number", "password", "password_confirmation"},
 *             @OA\Property(property="number", type="string", example="0938762024"),
 *             @OA\Property(property="password", type="string", format="password", example="aliahmad1234"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", example="aliahmad1234")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description=" You have successfully logged in"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description=" validation failed ",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="number",
 *                     type="array",
 *                     @OA\Items(type="string", example="The number field is required.")
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="array",
 *                     @OA\Items(type="string", example="The password must be at least 8 characters.")
 *                 ),
 *                 @OA\Property(
 *                     property="password_confirmation",
 *                     type="array",
 *                     @OA\Items(type="string", example="The password confirmation does not match.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'number' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('number', $request->number)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'Login successful', 'token' => $token], 200);
    }
/**
 * @OA\Post(
 *     path="/logout",
 *     summary="Logout the authenticated user",
 *     description="Revokes the current access token to log the user out.",
 *     operationId="logoutUser",
 *     tags={"Authentication"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="User logged out successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Logged out successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}