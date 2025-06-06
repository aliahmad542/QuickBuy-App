<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class StoreController extends Controller
{ 
    /**
* @OA\Post(
*     path="/api/create-store",
*     summary="Create a new store",
*     tags={"Stores"},
*     security={{"bearerAuth":{}}},
*     @OA\RequestBody(
*         required=true,
*         @OA\MediaType(
*             mediaType="multipart/form-data",
*             @OA\Schema(
*                 required={"name"},
*                 @OA\Property(
*                     property="name",
*                     type="string",
*                     example="My Electronics Store"
*                 ),
*                 @OA\Property(
*                     property="image",
*                     type="string",
*                     format="binary",
*                     description="Optional store image (jpeg, png, jpg, gif)"
*                 )
*             )
*         )
*     ),
*     @OA\Response(
*         response=201,
*         description="Store created successfully",
*         @OA\JsonContent(
*             @OA\Property(property="message", type="string", example="Your store has been created successfully"),
*             @OA\Property(property="store", type="object",
*                 @OA\Property(property="id", type="integer", example=1),
*                 @OA\Property(property="name", type="string", example="My Electronics Store"),
*                 @OA\Property(property="image", type="string", example="stores/1/store_image.jpg")
*             )
*         )
*     ),
*     @OA\Response(
*         response=422,
*         description="Validation error",
*         @OA\JsonContent(
*             @OA\Property(property="errors", type="object",
*                 @OA\Property(
*                     property="name",
*                     type="array",
*                     @OA\Items(type="string", example="The name field is required.")
*                 ),
*                 @OA\Property(
*                     property="image",
*                     type="array",
*                     @OA\Items(type="string", example="The image must be a file of type: jpeg, png, jpg, gif.")
*                 )
*             )
*         )
*     )
* )
*/

    public function createStore(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|unique:stores,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {

            $imagePath = $request->file('image')->store('stores', 'public');
            $validated['image'] = $imagePath;
        }

        $store = Store::create($validated);

        return response()->json([
            'message' => 'Your store has been created successfully',
            'store' => $store
        ], 201);
    }
/**
        * @OA\Get(
        *     path="/api/get-store-with-products",
        *     summary="Get all stores with their products",
        *     tags={"Stores"},
        *     security={{"bearerAuth":{}}},
        *     @OA\Response(
        *         response=200,
        *         description="List of stores with their products",
        *         @OA\JsonContent(
        *             type="array",
        *             @OA\Items(
        *                 type="object",
        *                 @OA\Property(property="id", type="integer", example=1),
        *                 @OA\Property(property="name", type="string", example="Fashion Store"),
        *                 @OA\Property(property="image", type="string", example="stores/fashion.jpg"),
        *                 @OA\Property(property="image_url", type="string", example="https://yourdomain.com/storage/stores/fashion.jpg"),
        *                 @OA\Property(
        *                     property="products",
        *                     type="array",
        *                     @OA\Items(
        *                         type="object",
        *                         @OA\Property(property="id", type="integer", example=101),
        *                         @OA\Property(property="name", type="string", example="T-shirt"),
        *                         @OA\Property(property="price", type="number", format="float", example=19.99),
        *                         @OA\Property(property="description", type="string", example="Comfortable cotton t-shirt"),
        *                         @OA\Property(property="quantity", type="integer", example=50),
        *                         @OA\Property(property="image", type="string", example="products/tshirt.png"),
        *                         @OA\Property(property="image_url", type="string", example="https://yourdomain.com/storage/products/tshirt.png")
        *                     )
        *                 )
        *             )
        *         )
        *     )
        * )
        */
    public function getStoreWithProducts()
    { 
       
        $stores = Store::with('products')->get();

        foreach ($stores as $store) {
            $store->image_url = asset('storage/' . $store->image);

            foreach ($store->products as $product) {
                $product->image_url = asset('storage/' . $product->image);
            }
        }

        $storesWithoutTimestamps = $stores->map(function ($store) {
            return [
                'id' => $store->id,
                'name' => $store->name,
                'image' => $store->image,
                'image_url' => $store->image_url,
                'products' => $store->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'store_id' => $product->store_id,
                        'description' => $product->description,
                        'image' => $product->image,
                        'quantity' => $product->quantity,
                        'image_url' => $product->image_url,
                    ];
                }),
            ];
        });

        return response()->json([
            'message' => 'Here are the stores with their products',
            'data' => $storesWithoutTimestamps,
        ]);
    }

    /**
 * @OA\Delete(
 *     path="/api/delete-store/{id}",
 *     summary="Delete a store by ID",
 *     tags={"Stores"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the store to delete",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Store deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The store has been deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Store not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Store] 123")
 *         )
 *     )
 * )
 */
    public function deleteStore($id)
{



    $store = Store::findOrFail($id);

    if ($store->image) {
        Storage::disk('public')->delete($store->image);
    }

    $store->delete();

    return response()->json([
        'message' => 'The store has been deleted successfully'
    ], 200);
}
public function get_Store_With_Products(){
    $stores = Store::with('products')->get();

    foreach ($stores as $store) {
        $store->image = asset('storage/' . $store->image);

        foreach ($store->products as $product) {
            $product->image = asset('storage/' . $product->image);
        }
    }
return view('stores-show',['stores' => $stores]);
    $storesWithoutTimestamps = $stores->map(function ($store) {
        return [
            'id' => $store->id,
            'name' => $store->name,
            'image' => $store->image,
            'image_url' => $store->image_url,
            'products' => $store->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'store_id' => $product->store_id,
                    'description' => $product->description,
                    'image' => $product->image,
                    'quantity' => $product->quantity,
                    'image_url' => $product->image_url,
                ];
            }),
        ];
    });

}
public function create_Store(Request $request){
    $validated = $request->validate([
        'name' => 'required|unique:stores,name',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('stores', 'public');
        $validated['image'] = $imagePath;
    }

    $store = Store::create($validated);

}
public function store(){
        return view('store-create');
}
public function delete_Store($id){
    $store = Store::findOrFail($id);

    if ($store->image) {
        Storage::disk('public')->delete($store->image);
    }

    $store->delete();
}
public function show_store_create(){
        return view('store-create');
}

}
