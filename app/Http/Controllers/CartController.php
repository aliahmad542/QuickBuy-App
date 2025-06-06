<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CartController extends Controller
{/**
        * @OA\Post(
        *     path="/api/cart-add/{productId}/{quantity}",
        *     summary="Add a product to the cart",
        *     tags={"Cart"},
        *     security={{"bearerAuth":{}}},
        *     @OA\Parameter(
        *         name="productId",
        *         in="path",
        *         required=true,
        *         description="ID of the product to add",
        *         @OA\Schema(type="integer", example=5)
        *     ),
        *     @OA\Parameter(
        *         name="quantity",
        *         in="path",
        *         required=true,
        *         description="Quantity of the product to add",
        *         @OA\Schema(type="integer", example=2)
        *     ),
        *     @OA\Response(
        *         response=200,
        *         description="Product added to cart",
        *         @OA\JsonContent(
        *             @OA\Property(property="message", type="string", example="Product added to cart")
        *         )
        *     ),
        *     @OA\Response(
        *         response=400,
        *         description="Not enough stock",
        *         @OA\JsonContent(
        *             @OA\Property(property="message", type="string", example="Not enough stock for product: iPhone 15")
        *         )
        *     ),
        *     @OA\Response(
        *         response=404,
        *         description="Product not found",
        *         @OA\JsonContent(
        *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Product] 999")
        *         )
        *     )
        * )
        */
    public function addToCart($productId, $quantity)
    { 
       
        $product = Product::findOrFail($productId);
        $user = Auth::user();

        if ($product->quantity < $quantity) {
            return response()->json(['message' => 'Not enough stock for product: ' . $product->name], 400);
        }

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $cartItem->quantity + $quantity]);
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }

        return response()->json(['message' => 'Product added to cart']);
    }
/**
 * @OA\Get(
 *     path="/api/cart",
 *     summary="Get all items in the authenticated user's cart",
 *     description="Returns a list of products that the authenticated user has in their cart.",
 *     tags={"Cart"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Cart items retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="cartItems",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="user_id", type="integer", example=3),
 *                     @OA\Property(property="product_id", type="integer", example=12),
 *                     @OA\Property(property="quantity", type="integer", example=2),
 *                     @OA\Property(
 *                         property="product",
 *                         type="object",
 *                         @OA\Property(property="id", type="integer", example=12),
 *                         @OA\Property(property="name", type="string", example="T-Shirt"),
 *                         @OA\Property(property="price", type="number", format="float", example=19.99)
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     )
 * )
 */
    public function showCart()
    {



        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        return response()->json(['cartItems' => $cartItems]);
    }
/**
 * @OA\Post(
 *     path="/api/buy-from-cart/{cartItemId}/{productId}/{quantity}",
 *     summary="Buy a product from cart and create a pending order",
 *     tags={"Orders"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="cartItemId",
 *         in="path",
 *         required=true,
 *         description="Cart item ID",
 *         @OA\Schema(type="integer", example=5)
 *     ),
 *     @OA\Parameter(
 *         name="productId",
 *         in="path",
 *         required=true,
 *         description="Product ID",
 *         @OA\Schema(type="integer", example=12)
 *     ),
 *     @OA\Parameter(
 *         name="quantity",
 *         in="path",
 *         required=true,
 *         description="Quantity to buy",
 *         @OA\Schema(type="integer", example=2)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order placed successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Order placed successfully. Awaiting approval."),
 *             @OA\Property(property="order", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="user_id", type="integer", example=1),
 *                 @OA\Property(property="product_id", type="integer", example=12),
 *                 @OA\Property(property="quantity", type="integer", example=2),
 *                 @OA\Property(property="total_price", type="number", format="float", example=39.99),
 *                 @OA\Property(property="is_pending", type="integer", example=1)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Not enough quantity in cart",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Not enough quantity in cart."),
 *             @OA\Property(property="product_name", type="string", example="iPhone 14"),
 *             @OA\Property(property="requested_quantity", type="integer", example=3),
 *             @OA\Property(property="cart_quantity", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Cart or product not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="An error occurred during the order process."),
 *             @OA\Property(property="error", type="string", example="SQLSTATE[...]: ...")
 *         )
 *     )
 * )
 */

    public function buyProductFromCart($cartItemId, $productId, $quantity)
    {

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }


        $cartItem = Cart::where('id', $cartItemId)->where('user_id', $user->id)->firstOrFail();


        $product = Product::findOrFail($productId);

        DB::beginTransaction();

        try {

            if ($cartItem->quantity < $quantity) {
                return response()->json([
                    'message' => 'Not enough quantity in cart.',
                    'product_name' => $product->name,
                    'requested_quantity' => $quantity,
                    'cart_quantity' => $cartItem->quantity
                ], 400);
            }


            $order = Order::create([
                'user_id' => $user->id,
                'is_pending' => 1,
                'product_id' => $productId,
                'quantity' => $quantity,
                'total_price' => $quantity * $product->price,
            ]);


            $cartItem->decrement('quantity', $quantity);


            if ($cartItem->quantity == 0) {
                $cartItem->delete();
            }

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully. Awaiting approval.',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred during the order process.',
                'error' => $e->getMessage()
            ], 500);
        }


    }
       /**
 * @OA\Get(
 *     path="/api/orders",
 *     summary="Get all orders for the authenticated user",
 *     description="Returns a list of orders placed by the currently authenticated user, including product name, total price, and status.",
 *     tags={"Orders"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Orders retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="orders",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="product_name", type="string", example="Wireless Mouse"),
 *                     @OA\Property(property="total_price", type="number", format="float", example=39.99),
 *                     @OA\Property(property="status", type="string", example="complete")
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     )
 * )
 */

    public function getUserOrders()
    {

        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->with('products:id,name')->get();

        $ordersWithProductNames = $orders->map(function ($order) {
            return [
                'product_name' => $order->products->pluck('name')->first(),
                'total_price' => $order->total_price,
                'status' => $order->status
            ];
        });

        return response()->json(['orders' => $ordersWithProductNames]);
    }

/**
 * @OA\Delete(
 *     path="/api/cart-remove-item/{cartItemId}",
 *     summary="Remove a specific product from the user's cart",
 *     description="Deletes a specific cart item based on its ID for the authenticated user.",
 *     tags={"Cart"},
 *     security={{"bearerAuth":{}}},
 * 
 *     @OA\Parameter(
 *         name="cartItemId",
 *         in="path",
 *         description="ID of the cart item to be removed",
 *         required=true,
 *         @OA\Schema(type="integer", example=5)
 *     ),
 * 
 *     @OA\Response(
 *         response=200,
 *         description="Product removed from cart successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Product removed from cart")
 *         )
 *     ),
 * 
 *     @OA\Response(
 *         response=404,
 *         description="Cart item not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Cart] ...")
 *         )
 *     ),
 * 
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     )
 * )
 */

    public function removeFromCart($cartItemId)
    {



        $user = Auth::user();
        $cartItem = Cart::where('id', $cartItemId)->where('user_id', $user->id)->firstOrFail();

        $cartItem->delete();

        return response()->json(['message' => 'Product removed from cart']);
    }
/**
 * @OA\Put(
 *     path="/api/admin-accept/{order_id}",
 *     summary="Accept a specific order by ID",
 *     description="Accept a pending order and update product quantity accordingly.",
 *     tags={"Admin"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="order_id",
 *         in="path",
 *         required=true,
 *         description="ID of the order to accept",
 *         @OA\Schema(type="integer", example=123)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Order accepted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Order accepted successfully. Product quantity updated."),
 *             @OA\Property(property="order", type="object",
 *                 @OA\Property(property="id", type="integer", example=123),
 *                 @OA\Property(property="product_id", type="integer", example=5),
 *                 @OA\Property(property="quantity", type="integer", example=2),
 *                 @OA\Property(property="total_price", type="number", format="float", example=59.98),
 *                 @OA\Property(property="status", type="string", example="complete"),
 *                 @OA\Property(property="is_pending", type="integer", example=0)
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=400,
 *         description="Order has already been accepted or processed",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Order has already been accepted or processed.")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Order not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Order] 123")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="An error occurred while accepting the order."),
 *             @OA\Property(property="error", type="string", example="SQLSTATE[...]: ...")
 *         )
 *     )
 * )
 */

    public function accept_request($orderId)
    {

        $order = Order::findOrFail($orderId);

        if ($order->is_pending == 1) {
            DB::beginTransaction();

            try {

                $order->update(['is_pending' => 0, 'status' => 'complete']);


                $product = Product::findOrFail($order->product_id);


                $product->decrement('quantity', $order->quantity);

                DB::commit();

                return response()->json([
                    'message' => 'Order accepted successfully. Product quantity updated.',
                    'order' => $order
                ]);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'message' => 'An error occurred while accepting the order.',
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json(['message' => 'Order has already been accepted or processed.'], 400);
        }



    }
      /**
 * @OA\Delete(
 *     path="/api/admin-reject/{order_id}",
 *     summary="Reject and delete a specific pending order",
 *     description="This endpoint deletes a pending order permanently. If the order is already processed, it returns a message.",
 *     tags={"Admin"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="order_id",
 *         in="path",
 *         required=true,
 *         description="ID of the order to reject and delete",
 *         @OA\Schema(type="integer", example=123)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Order rejected and deleted successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Order rejected and deleted successfully.")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=400,
 *         description="Order has already been processed",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Order has already been processed (approved or rejected).")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Order not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Order] 123")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error while rejecting the order",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Failed to reject and delete order."),
 *             @OA\Property(property="error", type="string", example="SQLSTATE[...]: ...")
 *         )
 *     )
 * )
 */

    public function reject_request($orderId)
    {
  
        try {
            // Find the order by ID
            $order = Order::findOrFail($orderId);


            if ($order->is_pending == 0) {
                return view('orders.status', [
                    'message' => 'Order has already been processed (approved or rejected).',
                ]);
            }

            DB::beginTransaction();


            $order->delete();

            DB::commit();

            return view('orders.status', [
                'message' => 'Order rejected and deleted successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return view('orders.error', [
                'message' => 'Failed to reject and delete order.',
                'error' => $e->getMessage(),
            ]);
        }
    }
/**
 * @OA\Post(
 *     path="/api/buy-all-from-cart",
 *     summary="Buy all products in the authenticated user's cart",
 *     tags={"Orders"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="All products purchased successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="All products purchased successfully. Awaiting approval.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Cart is empty or stock is not enough",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Your cart is empty."),
 *             @OA\Property(property="product_name", type="string", example="Samsung Galaxy"),
 *             @OA\Property(property="requested_quantity", type="integer", example=3),
 *             @OA\Property(property="available_quantity", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="An error occurred during the order process."),
 *             @OA\Property(property="error", type="string", example="SQLSTATE[...]: ...")
 *         )
 *     )
 * )
 */
    public function buy_all_in_cart(Request $request)
    {


        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }


        $cartItems = Cart::where('user_id', $user->id)->get();


        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 400);
        }

        DB::beginTransaction();

        try {

            foreach ($cartItems as $cartItem) {

                $product = Product::findOrFail($cartItem->product_id);


                if ($product->quantity < $cartItem->quantity) {
                    return response()->json([
                        'message' => 'Not enough stock for product.',
                        'product_name' => $product->name,
                        'requested_quantity' => $cartItem->quantity,
                        'available_quantity' => $product->quantity
                    ], 400);
                }


                $order = Order::create([
                    'user_id' => $user->id,
                    'is_pending' => 1,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'total_price' => $cartItem->quantity * $product->price,
                ]);


                $cartItem->decrement('quantity', $cartItem->quantity);


                if ($cartItem->quantity == 0) {
                    $cartItem->delete();
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'All products purchased successfully. Awaiting approval.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred during the order process.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
/**
        * @OA\Post(
        *     path="/api/admin-accept-all",
        *     summary="Accept all pending orders (admin only)",
        *     description="Marks all pending orders as complete and deducts the ordered quantity from the corresponding product stock.",
        *     tags={"Admin"},
        *     security={{"bearerAuth":{}}},
        * 
        *     @OA\Response(
        *         response=200,
        *         description="All pending orders accepted successfully.",
        *         @OA\JsonContent(
        *             @OA\Property(property="message", type="string", example="All pending orders have been accepted and product quantities updated.")
        *         )
        *     ),
        * 
        *     @OA\Response(
        *         response=400,
        *         description="No pending orders to accept.",
        *         @OA\JsonContent(
        *             @OA\Property(property="message", type="string", example="No pending orders to accept.")
        *         )
        *     ),
        * 
        *     @OA\Response(
        *         response=500,
        *         description="Internal server error",
        *         @OA\JsonContent(
        *             @OA\Property(property="message", type="string", example="An error occurred while accepting the orders."),
        *             @OA\Property(property="error", type="string", example="SQLSTATE[...]: ...")
        *         )
        *     )
        * )
        */
       
    public function acceptAllOrders(Request $request)
    { 
        DB::beginTransaction();

        try {

            $orders = Order::where('is_pending', 1)->get();

            if ($orders->isEmpty()) {
                return response()->json(['message' => 'No pending orders to accept.'], 400);
            }


            foreach ($orders as $order) {

                $product = Product::findOrFail($order->product_id);


                $product->decrement('quantity', $order->quantity);


                $order->update(['is_pending' => 0, 'status' => 'complete']);
            }

            DB::commit();

            return response()->json([
                'message' => 'All pending orders have been accepted and product quantities updated.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while accepting the orders.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
  /**
 * @OA\Get(
 *     path="/api/admin-reject",
 *     summary="Logically reject all pending orders (admin use)",
 *     description="Returns all orders with 'is_pending' = 1. No actual deletion or update occurs.",
 *     tags={"Admin"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Pending orders logically rejected (listed only)",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Your orders have been logically rejected. No database changes were made."),
 *             @OA\Property(
 *                 property="rejected_orders",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=12),
 *                     @OA\Property(property="user_id", type="integer", example=5),
 *                     @OA\Property(property="product_id", type="integer", example=3),
 *                     @OA\Property(property="quantity", type="integer", example=2),
 *                     @OA\Property(property="total_price", type="number", format="float", example=149.99),
 *                     @OA\Property(property="is_pending", type="integer", example=1),
 *                     @OA\Property(property="created_at", type="string", example="2025-06-05T14:00:00Z")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="No pending orders found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="No pending orders to reject.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="An error occurred while rejecting the orders."),
 *             @OA\Property(property="error", type="string", example="SQLSTATE[...]: ...")
 *         )
 *     )
 * )
 */

    public function rejectAllOrders(Request $request)
    { 
      
        try {

            $orders = Order::where('is_pending', 1)->get();

            if ($orders->isEmpty()) {
                return response()->json(['message' => 'No pending orders to reject.'], 400);
            }


            return response()->json([
                'message' => 'Your orders have been logically rejected. No database changes were made.',
                'rejected_orders' => $orders // Include details of the pending orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while rejecting the orders.',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function update_order(Request $request, $orderId)
    {

        {
            $request->validate([
                'quantity' => 'required|integer|min:1', // Ensure quantity is valid
            ]);

            $newQuantity = $request->quantity;

            DB::beginTransaction();

            try {

                $order = Order::findOrFail($orderId);

                if ($order->is_pending != 1) {
                    return response()->json(['message' => 'Only pending orders can be updated.'], 400);
                }


                $product = Product::findOrFail($order->product_id);


                if ($newQuantity > $product->quantity) {
                    return response()->json([
                        'message' => 'Not enough stock available for this product.',
                        'product_name' => $product->name,
                        'requested_quantity' => $newQuantity,
                        'available_quantity' => $product->quantity,
                    ], 400);
                }


                $order->update([
                    'quantity' => $newQuantity,
                    'total_price' => $newQuantity * $product->price,
                ]);

                DB::commit();

                return response()->json([
                    'message' => 'Order quantity updated successfully.',
                    'order' => $order,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'message' => 'An error occurred while updating the order quantity.',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    }
/**
        * @OA\Put(
        *     path="/api/update-order-quantity/{orderId}",
        *     summary="Update the quantity of a pending order",
        *     tags={"Orders"},
        *     security={{"bearerAuth":{}}},
        *     @OA\Parameter(
        *         name="orderId",
        *         in="path",
        *         required=true,
        *         description="ID of the order to update",
        *         @OA\Schema(type="integer", example=7)
        *     ),
        *     @OA\RequestBody(
        *         required=true,
        *         @OA\JsonContent(
        *             required={"quantity"},
        *             @OA\Property(property="quantity", type="integer", example=3)
        *         )
        *     ),
        *     @OA\Response(
        *         response=200,
        *         description="Order quantity updated successfully",
        *         @OA\JsonContent(
        *             @OA\Property(property="message", type="string", example="Order quantity updated successfully."),
        *             @OA\Property(
        *                 property="order",
        *                 type="object",
        *                 @OA\Property(property="id", type="integer", example=7),
        *                 @OA\Property(property="product_id", type="integer", example=12),
        *                 @OA\Property(property="quantity", type="integer", example=3),
        *                 @OA\Property(property="total_price", type="number", format="float", example=149.97)
        *             )
        *         )
        *     ),
        *     @OA\Response(
        *         response=400,
        *         description="Order is not pending or not enough stock",
        *         @OA\JsonContent(
        *             @OA\Property(property="message", type="string", example="Not enough stock available for this product."),
        *             @OA\Property(property="product_name", type="string", example="iPhone 15"),
        *             @OA\Property(property="requested_quantity", type="integer", example=10),
        *             @OA\Property(property="available_quantity", type="integer", example=5)
        *         )
        *     ),
        *     @OA\Response(
        *         response=404,
        *         description="Order or Product not found",
        *         @OA\JsonContent(
        *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Order] 99")
        *         )
        *     ),
        *     @OA\Response(
        *         response=500,
        *         description="Internal server error",
        *         @OA\JsonContent(
        *             @OA\Property(property="message", type="string", example="An error occurred while updating the order quantity."),
        *             @OA\Property(property="error", type="string", example="SQLSTATE[...]: ...")
        *         )
        *     )
        * )
        */
    public function update_order_quantity(Request $request, $orderId)
    { 
       
        $request->validate([
            'quantity' => 'required|integer|min:1', // Ensure quantity is valid
        ]);

        $newQuantity = $request->quantity;

        DB::beginTransaction();

        try {

            $order = Order::findOrFail($orderId);


            if ($order->is_pending != 1) {
                return response()->json(['message' => 'Only pending orders can be updated.'], 400);
            }


            $product = Product::findOrFail($order->product_id);


            if ($newQuantity > $product->quantity) {
                return response()->json([
                    'message' => 'Not enough stock available for this product.',
                    'product_name' => $product->name,
                    'requested_quantity' => $newQuantity,
                    'available_quantity' => $product->quantity,
                ], 400);
            }


            $order->update([
                'quantity' => $newQuantity,
                'total_price' => $newQuantity * $product->price,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Order quantity updated successfully.',
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while updating the order quantity.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
