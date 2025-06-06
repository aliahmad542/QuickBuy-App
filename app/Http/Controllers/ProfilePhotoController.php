<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfilePhotoController extends Controller
{ /**
    * @OA\Put(
    *     path="/api/profile/photo",
    *     summary="Update user's profile photo",
    *     tags={"Users"},
    *     security={{"bearerAuth":{}}},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 required={"photo"},
    *                 @OA\Property(
    *                     property="photo",
    *                     type="string",
    *                     format="binary",
    *                     description="The new profile photo to upload"
    *                 )
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Profile photo updated successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Profile photo updated successfully"),
    *             @OA\Property(property="photo_url", type="string", example="https://yourdomain.com/storage/profile_photos/1/avatar.png")
    *         )
    *     ),
    *     @OA\Response(
    *         response=422,
    *         description="Validation error",
    *         @OA\JsonContent(
    *             @OA\Property(property="errors", type="object",
    *                 @OA\Property(
    *                     property="photo",
    *                     type="array",
    *                     @OA\Items(type="string", example="The photo must be an image.")
    *                 )
    *             )
    *         )
    *     )
    * )
    */
   
    public function updateProfilePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        $folderPath = 'profile_photos/' . $user->id;

        $filePath = $request->file('photo')->store($folderPath, 'public');

        $user->profile_photo_path = $filePath;
        $user->save();

        return response()->json([
            'message' => 'Profile photo updated successfully',
            'photo_url' => asset('storage/' . $filePath),
        ], 200);
    }
}