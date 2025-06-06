<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
/**
 * @OA\Put(
 *     path="/api/user/location",
 *     summary="Update user's location",
 *     tags={"Users"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"location"},
 *             @OA\Property(
 *                 property="location",
 *                 type="string",
 *                 example="Damascus, Syria"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Location updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Location updated successfully!"),
 *             @OA\Property(property="location", type="string", example="Damascus, Syria")
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
 *                     property="location",
 *                     type="array",
 *                     @OA\Items(type="string", example="The location field is required.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */

class UserLocationController extends Controller
{

    public function updateLocation(Request $request)
    {
        $request->validate([
            'location' => 'required|string|max:255',
        ]);

        $user = $request->user();
    
        $user->location = $request->location;
        $user->save();

        return response()->json([
            'message' => 'Location updated successfully!',
            'location' => $user->location
        ], 200);
    }
}