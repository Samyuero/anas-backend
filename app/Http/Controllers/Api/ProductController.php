<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // ==================== PUBLIC SHOP METHODS ====================

    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        // Filter by multiple categories (comma-separated names)
        if ($request->has('categories')) {
            $categoryNames = explode(',', $request->input('categories'));
            $query->whereHas('category', function ($q) use ($categoryNames) {
                $q->whereIn('categoryName', $categoryNames);
            });
        }

        // Filter by multiple brands (comma-separated names)
        if ($request->has('brands')) {
            $brandNames = explode(',', $request->input('brands'));
            $query->whereHas('brand', function ($q) use ($brandNames) {
                $q->whereIn('brandName', $brandNames);
            });
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('regular_price', '>=', $request->input('min_price'));
        }
        if ($request->has('max_price')) {
            $query->where('regular_price', '<=', $request->input('max_price'));
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $sort = $request->input('sort', 'default');
        switch ($sort) {
            case 'date-new':
                $query->orderBy('created_at', 'desc');
                break;
            case 'date-old':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price-low':
                $query->orderBy('regular_price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('regular_price', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        // Pagination
        $perPage = $request->input('per_page', 12);
        $products = $query->paginate($perPage);

        // Transform image URLs and prices for frontend
        $products->getCollection()->transform(function ($product) {
            if ($product->image) {
                $product->image = str_starts_with($product->image, 'http') ? $product->image : asset('uploads/products/' . $product->image);
            }
            
            $product->wholesale_price = $product->regular_price;
            $product->retail_price = $product->sale_price;
            return $product;
        });

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function show($id)
    {
        // Support both numeric IDs and slugs
        if (is_numeric($id)) {
            $product = Product::with(['category', 'brand', 'reviews'])->findOrFail($id);
        } else {
            $product = Product::with(['category', 'brand', 'reviews'])->where('slug', $id)->firstOrFail();
        }
        
        // Transform image URL
        $imageUrl = $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('uploads/products/' . $product->image)) : null;
        
        // Parse gallery images into array - check both gallery_images (JSON) and images (comma-separated) columns
        $galleryImages = [];
        
        // Check new gallery_images column (JSON format with full paths)
        if ($product->gallery_images) {
            $galleryArray = json_decode($product->gallery_images, true) ?? [];
            foreach ($galleryArray as $img) {
                $galleryImages[] = str_starts_with($img, 'http') ? $img : asset($img);
            }
        }
        
        // Fallback: check legacy images column (comma-separated filenames)
        if (empty($galleryImages) && $product->images) {
            $legacyImages = array_filter(array_map('trim', explode(',', $product->images)));
            foreach ($legacyImages as $img) {
                if ($img && $img !== $product->image) {
                    $galleryImages[] = str_starts_with($img, 'http') ? $img : asset('uploads/products/' . $img);
                }
            }
        }
        
        // Combine main image + gallery for frontend
        $allImages = array_values(array_filter(array_merge([$imageUrl], $galleryImages)));
        
        // Map stock status properly
        $stockQty = (int) $product->quantity;
        $isInStock = $product->stock_status === 'inStock' && $stockQty > 0;
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->SKU,
                'SKU' => $product->SKU,
                
                'description' => $product->description,
                'short_description' => $product->short_description,
                
                // Prices
                'regular_price' => (float) $product->regular_price,
                'sale_price' => (float) $product->sale_price,
                'wholesale_price' => (float) $product->regular_price,
                'retail_price' => (float) $product->sale_price,
                
                // Stock
                'quantity' => $stockQty,
                'stock_quantity' => $stockQty,
                'stock_status' => $product->stock_status,
                'in_stock' => $isInStock,
                
                // Images
                'image' => $imageUrl,
                'images' => $allImages,
                'gallery_images' => $galleryImages,
                
                // Category & Brand
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->categoryName,
                    'categoryName' => $product->category->categoryName,
                ] : null,
                'brand' => $product->brand ? [
                    'id' => $product->brand->id,
                    'name' => $product->brand->brandName,
                    'brandName' => $product->brand->brandName,
                ] : null,
                
                // Additional info
                'weight' => $product->weight ? (float) $product->weight : null,
                'dimensions' => $product->dimensions,
                
                // Tags
                'tags' => null,
                
                // Reviews - now included from relationship
                'reviews' => $product->reviews->map(function($review) {
                    return [
                        'id' => $review->id,
                        'name' => $review->name,
                        'email' => $review->email,
                        'rating' => $review->rating,
                        'review' => $review->review,
                        'created_at' => $review->created_at->format('M d, Y'),
                    ];
                }),
                'reviews_count' => $product->reviews->count(),
                'average_rating' => $product->reviews->count() > 0 
                    ? round($product->reviews->avg('rating'), 1) 
                    : 0,
            ]
        ]);
    }

    // Store Review
    public function storeReview(Request $request, $id)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
        ]);

        $product = Product::findOrFail($id);
        
        $review = $product->reviews()->create([
            'rating' => $validated['rating'],
            'review' => $validated['review'],
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully',
            'data' => $review
        ]);
    }

    // ✅ Public categories for shop page
    public function categories()
    {
        $categories = Category::withCount('products')->get()->map(function ($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->categoryName ?? 'Unknown',
                'categoryName' => $cat->categoryName ?? 'Unknown',
                'image' => $cat->image,
                'count' => $cat->products_count ?? 0
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    // ✅ Public brands for shop page
    public function brands()
    {
        $brands = Brand::withCount('products')->get()->map(function ($brand) {
            return [
                'name' => $brand->brandName ?? 'Unknown',
                'count' => $brand->products_count ?? 0
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    }

    // ==================== ADMIN METHODS ====================

    public function adminIndex(Request $request)
    {
        $query = Product::with(['category', 'brand']);
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|unique:products',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'short_description' => 'required|string|max:100',
            'description' => 'required|string',
            'regular_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'SKU' => 'required|string|unique:products',
            'quantity' => 'required|integer',
            'stock_status' => 'required|in:inStock,outOfStock',
            'featured' => 'required|boolean',
            'weight' => 'nullable|numeric',
            'dimensions' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/products'), $imageName);
            $validated['image'] = $imageName;
        }

        $galleryImages = [];
        if ($request->hasFile('gallery_images')) {
            $idx = 0;
            foreach ($request->file('gallery_images') as $galleryImage) {
                $galleryName = time() . '_' . $idx . '_' . $galleryImage->getClientOriginalName();
                $galleryImage->move(public_path('uploads/products/gallery'), $galleryName);
                $galleryImages[] = 'uploads/products/gallery/' . $galleryName;
                $idx++;
            }
        }
        $validated['gallery_images'] = json_encode($galleryImages);

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'slug' => 'sometimes|string|unique:products,slug,' . $id,
            'category_id' => 'sometimes|exists:categories,id',
            'brand_id' => 'sometimes|exists:brands,id',
            'short_description' => 'sometimes|string|max:100',
            'description' => 'sometimes|string',
            'regular_price' => 'sometimes|numeric',
            'sale_price' => 'sometimes|numeric',
            'SKU' => 'sometimes|string|unique:products,SKU,' . $id,
            'quantity' => 'sometimes|integer',
            'stock_status' => 'sometimes|in:inStock,outOfStock',
            'featured' => 'sometimes|boolean',
            'weight' => 'sometimes|nullable|numeric',
            'dimensions' => 'sometimes|nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
                unlink(public_path('uploads/products/' . $product->image));
            }
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/products'), $imageName);
            $validated['image'] = $imageName;
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            // Keep existing gallery images if any
            if ($product->gallery_images) {
                $galleryImages = json_decode($product->gallery_images, true) ?? [];
            }
            $idx = 0;
            foreach ($request->file('gallery_images') as $galleryImage) {
                $galleryName = time() . '_' . $idx . '_' . $galleryImage->getClientOriginalName();
                $galleryImage->move(public_path('uploads/products/gallery'), $galleryName);
                $galleryImages[] = 'uploads/products/gallery/' . $galleryName;
                $idx++;
            }
            $validated['gallery_images'] = json_encode($galleryImages);
        }

        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
            unlink(public_path('uploads/products/' . $product->image));
        }
        
        if ($product->gallery_images) {
            $galleryImages = json_decode($product->gallery_images, true);
            foreach ($galleryImages as $galleryImage) {
                if (file_exists(public_path($galleryImage))) {
                    unlink(public_path($galleryImage));
                }
            }
        }
        
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
}