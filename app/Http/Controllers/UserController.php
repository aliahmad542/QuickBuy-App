<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{/**
 * @OA\Get(
 *     path="/api/user",
 *     summary="Get authenticated user's info",
 *     tags={"Users"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="User info retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="firstName", type="string", example="ali"),
 *             @OA\Property(property="lastName", type="string", example="ahmad"),
 *             @OA\Property(property="number", type="string", example="0999123456"),
 *             @OA\Property(property="location", type="string", example="Damascus, Syria"),
 *             @OA\Property(property="profile_photo_url", type="string", example="https://yourdomain.com/storage/profile_photos/user123.jpg")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */

    public function getUserInfo(Request $request)
    {
        $user = $request->user();

        $profilePhotoUrl = $user->profile_photo_path 
            ? asset('storage/' . $user->profile_photo_path) 
            : asset('storage/default_profile_photo.jpg');

        return response()->json([
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'number' => $user->number,
            'location' => $user->location,
            'profile_photo_url' => $profilePhotoUrl,
        ]);
    }
/**
 * @OA\Put(
 *     path="/api/user/password",
 *     summary="Update user's password",
 *     tags={"Users"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"current_password", "new_password", "new_password_confirmation"},
 *             @OA\Property(property="current_password", type="string", format="password", example="oldpassword123"),
 *             @OA\Property(property="new_password", type="string", format="password", example="newpassword456"),
 *             @OA\Property(property="new_password_confirmation", type="string", format="password", example="newpassword456")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Password updated successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Current password is incorrect",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Current password is incorrect")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="new_password",
 *                     type="array",
 *                     @OA\Items(type="string", example="The new password must be at least 8 characters.")
 *                 ),
 *                 @OA\Property(
 *                     property="new_password_confirmation",
 *                     type="array",
 *                     @OA\Items(type="string", example="The new password confirmation does not match.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 401);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully']);
    }
}