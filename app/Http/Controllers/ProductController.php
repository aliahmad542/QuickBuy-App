<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{/**
        * @OA\Post(
        *     path="/api/add-products",
        *     summary="Add a new product",
        *     tags={"Products"},
        *     security={{"bearerAuth":{}}},
        *     @OA\RequestBody(
        *         required=true,
        *         @OA\MediaType(
        *             mediaType="multipart/form-data",
        *             @OA\Schema(
        *                 required={"name", "price", "store_id", "description", "quantity"},
        *                 @OA\Property(property="name", type="string", example="iPhone 15"),
        *                 @OA\Property(property="price", type="number", format="float", example=999.99),
        *                 @OA\Property(property="store_id", type="integer", example=1),
        *                 @OA\Property(property="description", type="string", example="The latest iPhone model"),
        *                 @OA\Property(property="quantity", type="integer", example=10),
        *                 @OA\Property(
        *                     property="image",
        *                     type="string",
        *                     format="binary",
        *                     description="Optional product image (jpeg, png, jpg, gif)"
        *                 )
        *             )
        *         )
        *     ),
        *     @OA\Response(
        *         response=200,
        *         description="Product added successfully",
        *         @OA\JsonContent(
        *             @OA\Property(property="message", type="string", example="Your product has been added successfully"),
        *             @OA\Property(property="product", type="object",
        *                 @OA\Property(property="id", type="integer", example=12),
        *                 @OA\Property(property="name", type="string", example="iPhone 15"),
        *                 @OA\Property(property="price", type="number", example=999.99),
        *                 @OA\Property(property="store_id", type="integer", example=1),
        *                 @OA\Property(property="description", type="string", example="The latest iPhone model"),
        *                 @OA\Property(property="quantity", type="integer", example=10),
        *                 @OA\Property(property="image", type="string", example="products/iphone15.jpg")
        *             )
        *         )
        *     ),
        *     @OA\Response(
        *         response=422,
        *         description="Validation error",
        *         @OA\JsonContent(
        *             @OA\Property(property="errors", type="object")
        *         )
        *     )
        * )
        */
    public function addProduct(Request $request)
    { 
       
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'store_id' => 'required|exists:stores,id',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Your product has been added successfully',
            'product' => $product
        ]);
    }
    public function add_Product(Request $request){
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'store_id' => 'required|exists:stores,id',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }
        $product = Product::create($validated);
        return redirect()->route('get_stores');
    }
    public function show(){
        $stores = Store::all();
      return view('product-add',['stores' => $stores]);
    }
    public function show_product_store(){
        return view('product-add');
    }
    /**
 * @OA\Put(
 *     path="/api/update-order/{cart_item}/{new_quantity}",
 *     summary="Update quantity of a cart item",
 *     description="Allows an authenticated user to update the quantity of a product in their cart, ensuring product stock and order status are valid.",
 *     tags={"Cart"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="cart_item",
 *         in="path",
 *         required=true,
 *         description="Cart item ID to update",
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Parameter(
 *         name="new_quantity",
 *         in="path",
 *         required=true,
 *         description="New quantity for the product",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Cart item updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Cart item updated successfully."),
 *             @OA\Property(property="product_name", type="string", example="Product A"),
 *             @OA\Property(property="updated_quantity", type="integer", example=3),
 *             @OA\Property(property="total_price", type="number", format="float", example=149.97)
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input or stock error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Invalid input data."),
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Order is not pending",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The order is not editable as it is not pending."),
 *             @OA\Property(property="order_status", type="string", example="complete")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Cart item or product not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Cart item not found.")
 *         )
 *     )
 * )
 */





    public function update_order($cartItemId,$newQuantity)
    {

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }


        if (!is_numeric($cartItemId) || $cartItemId <= 0 || !is_numeric($newQuantity) || $newQuantity <= 0) {
            return response()->json(['message' => 'Invalid input data.'], 400);
        }


        $cartItem = Cart::where('id', $cartItemId)
            ->where('user_id', $user->id)
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }


        $product = $cartItem->product;
        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }


        $order = Order::where('id', $cartItem->order_id)->first();
        if ($order && $order->is_pending != 1) {
            return response()->json([
                'message' => 'The order is not editable as it is not pending.',
                'order_status' => $order->status
            ], 403);
        }


        if ($product->quantity < $newQuantity) {
            return response()->json([
                'message' => 'Not enough stock for product.',
                'product_name' => $product->name,
                'available_quantity' => $product->quantity,
                'requested_quantity' => $newQuantity
            ], 400);
        }


        $cartItem->update(['quantity' => $newQuantity]);

        return response()->json([
            'message' => 'Cart item updated successfully.',
            'product_name' => $product->name,
            'updated_quantity' => $newQuantity,
            'total_price' => $newQuantity * $product->price
        ]);
    }
    /**
 * @OA\Delete(
 *     path="/api/delete-order/{orderId}",
 *     summary="Delete a pending order",
 *     tags={"Orders"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="orderId",
 *         in="path",
 *         required=true,
 *         description="ID of the order to delete",
 *         @OA\Schema(type="integer", example=15)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Order deleted successfully."),
 *             @OA\Property(property="order_id", type="integer", example=15)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Order cannot be deleted because it is not pending",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Order cannot be deleted as it is no longer pending."),
 *             @OA\Property(property="order_status", type="integer", example=0)
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Order not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Order] 99")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="An error occurred while deleting the order."),
 *             @OA\Property(property="error", type="string", example="SQLSTATE[...]: ...")
 *         )
 *     )
 * )
 */
public function deleteOrder($orderId){



        try {

            $order = Order::findOrFail($orderId);


            if ($order->is_pending != 1) {
                return response()->json([
                    'message' => 'Order cannot be deleted as it is no longer pending.',
                    'order_status' => $order->is_pending
                ], 400);
            }


            $order->delete();

            return response()->json([
                'message' => 'Order deleted successfully.',
                'order_id' => $orderId
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the order.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete_product($productId,$storeId){
        $product = Product::where('id', $productId)
            ->where('store_id', $storeId)
            ->first();
        $product->delete();
        return redirect()->back();
    }
    public function get_products_from_controllers($id){
        $getproduct=Store::find($id);
        $products=Product::where('store_id',$id)->get();
        foreach ($products as $product) {
            $product->image = asset('storage/' . $product->image);


            if (!empty($product->products) && is_iterable($product->products)) {
                foreach ($product->products as $producte) {
                    $producte->image = asset('storage/' . $producte->image);
                }
            }
        }
        return view('store-details',compact('products','getproduct'));

    }
    public  function  get_orders(){
        $orders = Order::with(['user', 'product'])->get();

        return view('order-show', compact('orders'));
    }
    public  function get_product_insert(){
        $stores=Store::all(
        );
        return view('product-add',compact('stores'));
    }
}

