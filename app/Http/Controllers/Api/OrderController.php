<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Transaction;

class OrderController extends Controller
{
    /**
     * List authenticated user's orders
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Create a new order from cart items
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'sitio' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
        ]);

        $userId = Auth::id();

        // Get cart items
        $cartItems = CartItem::with('product')->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty'
            ], 400);
        }

        // Validate stock availability for all items
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => "Product not found for cart item"
                ], 400);
            }
            if ($cartItem->quantity > $product->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Not enough stock for {$product->name}. Available: {$product->quantity}"
                ], 400);
            }
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'user_id' => $userId,
                'subTotal' => round($subtotal, 2),
                'total' => round($subtotal, 2),
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'city' => $validated['city'] ?? '',
                'barangay' => $validated['barangay'] ?? '',
                'sitio' => $validated['sitio'] ?? '',
                'landmark' => $validated['landmark'] ?? null,
                'type' => 'home',
                'status' => 'processing',
                'is_shipping_different' => false,
            ]);

            // Create order items and reduce stock
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                ]);

                // Reduce product stock
                $product = $cartItem->product;
                $product->quantity -= $cartItem->quantity;
                if ($product->quantity <= 0) {
                    $product->stock_status = 'outOfStock';
                }
                $product->save();
            }

            // Create transaction record
            Transaction::create([
                'order_id' => $order->id,
                'user_id' => $userId,
                'payment_method' => 'cod',
                'status' => 'pending',
            ]);

            // Clear cart
            CartItem::where('user_id', $userId)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'data' => [
                    'order_id' => $order->id
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order details
     */
    public function show($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with([
                'orderItems.product.category',
                'orderItems.product.brand',
                'transaction'
            ])
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'order' => $order,
                'orderItems' => $order->orderItems,
                'transaction' => $order->transaction,
            ]
        ]);
    }

    /**
     * Cancel an order
     */
    public function cancel($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        if ($order->status !== 'processing') {
            return response()->json([
                'success' => false,
                'message' => 'Only processing orders can be cancelled'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Restore stock
            foreach ($order->orderItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->quantity += $item->quantity;
                    if ($product->quantity > 0) {
                        $product->stock_status = 'inStock';
                    }
                    $product->save();
                }
            }

            $order->status = 'cancelled';
            $order->cancelled_date = now();
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order'
            ], 500);
        }
    }
}