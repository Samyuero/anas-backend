<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\CartItem;

class CartController extends Controller
{
    // ==================== API METHODS FOR REACT (Database-backed) ====================

    /**
     * Get cart items (JSON for React)
     */
    public function indexApi()
    {
        $userId = Auth::id();
        $items = CartItem::with('product.category')->where('user_id', $userId)->get();

        $cartItems = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->name ?? 'Unknown',
                'qty' => $item->quantity,
                'price' => (float) $item->price,
                'subtotal' => round($item->price * $item->quantity, 2),
                'image' => $item->product->image ? (str_starts_with($item->product->image, 'http') ? $item->product->image : asset('uploads/products/' . $item->product->image)) : null,
                'category' => $item->product->category->categoryName ?? null,
                'product' => $item->product,
            ];
        });

        $subtotal = $cartItems->sum('subtotal');

        return response()->json([
            'success' => true,
            'data' => $cartItems,
            'count' => $items->count(),
            'subtotal' => round($subtotal, 2),
            'total' => round($subtotal, 2)
        ]);
    }

    /**
     * Add to cart (API for React)
     */
    public function addToCartApi(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric'
        ]);

        $userId = Auth::id();
        $product = Product::findOrFail($request->product_id);

        // Check existing cart quantity for this product
        $existingItem = CartItem::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        $currentQty = $existingItem ? $existingItem->quantity : 0;
        $requestedQuantity = $request->quantity;
        $availableQuantity = $product->quantity;

        if (($currentQty + $requestedQuantity) > $availableQuantity) {
            return response()->json([
                'success' => false,
                'message' => "Not enough stock for {$product->name}. Available: {$availableQuantity}"
            ], 400);
        }

        if ($existingItem) {
            // Update quantity if product already in cart
            $existingItem->update([
                'quantity' => $existingItem->quantity + $requestedQuantity,
                'price' => $request->price,
            ]);
        } else {
            // Create new cart item
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'quantity' => $requestedQuantity,
                'price' => $request->price,
            ]);
        }

        $count = CartItem::where('user_id', $userId)->count();

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart',
            'count' => $count
        ]);
    }

    /**
     * Update quantity (API for React)
     */
    public function updateQtyApi(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = Auth::id();
        $cartItem = CartItem::where('id', $id)->where('user_id', $userId)->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        $product = Product::findOrFail($cartItem->product_id);

        if ($validated['quantity'] > $product->quantity) {
            return response()->json([
                'success' => false,
                'message' => "Requested quantity exceeds available stock ({$product->quantity}) for {$product->name}"
            ], 400);
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return response()->json([
            'success' => true,
            'message' => 'Quantity updated'
        ]);
    }

    /**
     * Remove item (API for React)
     */
    public function removeCartItemApi($id)
    {
        $userId = Auth::id();
        CartItem::where('id', $id)->where('user_id', $userId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart'
        ]);
    }

    /**
     * Clear cart (API for React)
     */
    public function clearCartApi()
    {
        $userId = Auth::id();
        CartItem::where('user_id', $userId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared'
        ]);
    }

    // ==================== EXISTING WEB METHODS (keep for Blade views) ====================

    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->id);
    
        $cartQuantity = Cart::instance('cart')->content()
            ->where('id', $product->id)
            ->sum('qty');

        $requestedQuantity = $request->quantity;
        $availableQuantity = $product->quantity;

        if (($cartQuantity + $requestedQuantity) > $availableQuantity) {
            return redirect()->back()
                ->with('error', "Not enough stock for {$product->name}. Available: {$availableQuantity}");
        }
        
        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return redirect()->back();
    }

    public function increaseCartItem($rowId)
    {
        $cartItem = Cart::instance('cart')->get($rowId);
        $product = Product::findOrFail($cartItem->id);

        if ($cartItem->qty + 1 > $product->quantity) {
            return redirect()->back()
                ->with('error', "Cannot increase quantity. Only {$product->quantity} available for {$product->name}");
        }

        Cart::instance('cart')->update($rowId, $cartItem->qty + 1);
        return redirect()->back();
    }

    public function decreaseCartItem($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function updateQty(Request $request, $rowId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::instance('cart')->get($rowId);
        $product = Product::findOrFail($cartItem->id);

        if ($validated['quantity'] > $product->quantity) {
            return redirect()->back()
                ->with('error', "Requested quantity exceeds available stock ({$product->quantity}) for {$product->name}");
        }

        Cart::instance('cart')->update($rowId, $validated['quantity']);
        return redirect()->back();
    }

    public function removeCartItem($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function clearCart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back();
    }

    // ... (keep checkout, placeOrder, etc. methods as they are)
}