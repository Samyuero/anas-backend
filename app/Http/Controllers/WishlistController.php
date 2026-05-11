<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WishlistItem;
use App\Models\CartItem;
use App\Models\Product;

class WishlistController extends Controller
{
    // ==================== API METHODS FOR REACT ====================

    public function indexApi()
    {
        $items = WishlistItem::with('product')
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($item) {
                $product = $item->product;
                return [
                    'id' => $item->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->sale_price ?: $product->regular_price,
                    'image' => $product->image,
                    'stock_status' => $product->stock_status,
                    'quantity' => $product->quantity,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $items,
            'count' => $items->count()
        ]);
    }

    public function addToWishlistApi(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        // Check if already in wishlist
        $existing = WishlistItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'Item already in wishlist'
            ]);
        }

        WishlistItem::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item added to wishlist'
        ]);
    }

    public function removeFromWishlistApi($id)
    {
        $item = WishlistItem::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$item) {
            // Also try by product_id for flexibility
            $item = WishlistItem::where('user_id', Auth::id())
                ->where('product_id', $id)
                ->first();
        }

        if ($item) {
            $item->delete();
        }

        return response()->json(['success' => true, 'message' => 'Item removed from wishlist']);
    }

    public function clearWishlistApi()
    {
        WishlistItem::where('user_id', Auth::id())->delete();
        return response()->json(['success' => true, 'message' => 'Wishlist cleared']);
    }

    public function moveToCartApi($id)
    {
        $userId = Auth::id();

        $wishlistItem = WishlistItem::with('product')
            ->where('user_id', $userId)
            ->where('id', $id)
            ->first();

        if (!$wishlistItem) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        $product = $wishlistItem->product;
        $price = $product->sale_price ?: $product->regular_price;

        // Add to database cart
        $existingCartItem = CartItem::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        if ($existingCartItem) {
            $existingCartItem->update([
                'quantity' => $existingCartItem->quantity + 1,
                'price' => $price,
            ]);
        } else {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $price,
            ]);
        }

        $wishlistItem->delete();

        return response()->json(['success' => true, 'message' => 'Item moved to cart']);
    }

    // Check if a product is in user's wishlist
    public function checkApi(Request $request)
    {
        $request->validate(['product_id' => 'required|integer']);

        $exists = WishlistItem::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->exists();

        return response()->json([
            'success' => true,
            'in_wishlist' => $exists
        ]);
    }
}