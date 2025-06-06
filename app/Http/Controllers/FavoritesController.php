<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{ 
    /**
* @OA\Post(
*     path="/favorites/{productId}",
*     summary="Add product to favorites",
*     description="Adds a specific product to the authenticated user's favorites list.",
*     operationId="addToFavorites",
*     tags={"Favorites"},
*     security={{"bearerAuth":{}}},
*
*     @OA\Parameter(
*         name="productId",
*         in="path",
*         required=true,
*         description="ID of the product to add to favorites",
*         @OA\Schema(type="integer")
*     ),
*
*     @OA\Response(
*         response=200,
*         description="Product added to favorites",
*         @OA\JsonContent(
*             @OA\Property(property="message", type="string", example="Product added to favorites")
*         )
*     ),
*     @OA\Response(
*         response=400,
*         description="Product already in favorites or bad request",
*         @OA\JsonContent(
*             @OA\Property(property="message", type="string", example="Product already in your favorites")
*         )
*     ),
*     @OA\Response(
*         response=401,
*         description="Unauthorized access",
*         @OA\JsonContent(
*             @OA\Property(property="message", type="string", example="Unauthorized")
*         )
*     ),
*     @OA\Response(
*         response=404,
*         description="Product not found",
*         @OA\JsonContent(
*             @OA\Property(property="message", type="string", example="Product not found")
*         )
*     )
* )
*/
    public function addToFavorites($productId)
    { 

        $product = Product::findOrFail($productId);
        $user = Auth::user();

        $favorite = Favorite::where('user_id', $user->id)
                            ->where('product_id', $product->id)
                            ->first();

        if ($favorite) {
            return response()->json(['message' => 'Product already in your favorites'], 400);
        }

        Favorite::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return response()->json(['message' => 'Product added to favorites']);
    }
/**
 * @OA\Delete(
 *     path="/favorites/{productId}",
 *     summary="Remove a product from favorites",
 *     description="Deletes a product from the authenticated user's favorites list.",
 *     operationId="removeFromFavorites",
 *     tags={"Favorites"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="productId",
 *         in="path",
 *         required=true,
 *         description="ID of the product to be removed from favorites",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product removed from favorites successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Product removed from favorites")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Product not found in favorites",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Product not found in favorites")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
    public function removeFromFavorites($productId)
    {
        $user = Auth::user();
        $favorite = Favorite::where('user_id', $user->id)
                            ->where('product_id', $productId)
                            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['message' => 'Product removed from favorites']);
        }

        return response()->json(['message' => 'Product not found in favorites'], 404);
    }
/**
 * @OA\Get(
 *     path="/favorites",
 *     summary=" show the favourite products for this user",
 * description="Displays a list of products that the user has added to favorites with product details such as name, price, and image.",
 *     operationId="showFavorites",
 *     tags={"Favorites"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description=" list of favourites products",
 *         @OA\JsonContent(
 *             @OA\Property(property="favorites", type="array", @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="perfect product"),
 *                 @OA\Property(property="price", type="number", format="float", example=49.99),
 *                 @OA\Property(property="image_url", type="string", format="url", example="http://yourapp.com/storage/image.jpg")
 *             ))
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - you have to log in"
 *     )
 * )
 */

    public function showFavorites()
    {
        $user = Auth::user();
        
        $favorites = Favorite::where('user_id', $user->id)
                             ->with('product')
                             ->get();
    
        $favoriteProductsWithImages = $favorites->map(function($favorite) {
            return [
                'id' => $favorite->product->id,
                'name' => $favorite->product->name,
                'price' => $favorite->product->price,
                'image_url' => url('storage/' . $favorite->product->image),
            ];
        });
    
        return response()->json(['favorites' => $favoriteProductsWithImages]);
    }
}