<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;  
use App\Models\Product;  
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\Slide;
use App\Models\Contact;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    // ============================================
    // WEB METHODS (for Blade views if needed)
    // ============================================
    
    public function index()
    {
        return view('admin.index');
    }

    // ============================================
    // API METHODS FOR REACT FRONTEND
    // ============================================

    public function apiDashboardStats()
    {
        $dashbordDatas = DB::select("Select sum(subTotal) As TotalAmount,
                                    sum(if(status='processing',subTotal,0)) As TotalOrderedAmount,
                                    sum(if(status='delivered',subTotal,0)) As TotalDeliveredAmount,
                                    sum(if(status='cancelled',subTotal,0)) As TotalCancelledAmount,
                                    Count(*) as Total,
                                    sum(if(status='processing',1,0)) As TotalOrdered,
                                    sum(if(status='delivered',1,0)) As TotalDelivered,
                                    sum(if(status='cancelled',1,0)) As TotalCancelled
                                    From orders
                                    ");

        $monthlyDatas = DB::select("SELECT M.id As MonthNo, LEFT(M.name, 3) As MonthName,
                                    IFNULL(D.TotalAmount,0) As TotalAmount,
                                    IFNULL(D.TotalOrderedAmount,0) As TotalOrderedAmount,
                                    IFNULL(D.TotalDeliveredAmount,0) As TotalDeliveredAmount,
                                    IFNULL(D.TotalCancelledAmount,0) As TotalCancelledAmount FROM month_names M
                                    LEFT JOIN (Select DATE_FORMAT(created_at, '%b') As MonthName,
                                    MONTH(created_at) As MonthNo,
                                    sum(subTotal) As TotalAmount,
                                    sum(if(status='processing', subTotal,0)) As TotalOrderedAmount,
                                    sum(if(status='delivered', subTotal,0)) As TotalDeliveredAmount,
                                    sum(if(status='cancelled', subTotal,0)) As TotalCancelledAmount
                                    From orders WHERE YEAR(created_at) = YEAR(NOW()) GROUP BY YEAR(created_at), MONTH(created_at), DATE_FORMAT(created_at, '%b')
                                    Order By MONTH(created_at)) D On D.MonthNo=M.id");

        $stats = [
            'totalOrders' => collect($dashbordDatas)->sum('Total'),
            'deliveredOrders' => collect($dashbordDatas)->sum('TotalDelivered'),
            'pendingOrders' => collect($dashbordDatas)->sum('TotalOrdered'),
            'cancelledOrders' => collect($dashbordDatas)->sum('TotalCancelled'),
            'totalAmount' => collect($dashbordDatas)->sum('TotalAmount'),
            'deliveredAmount' => collect($dashbordDatas)->sum('TotalDeliveredAmount'),
            'pendingAmount' => collect($dashbordDatas)->sum('TotalOrderedAmount'),
            'cancelledAmount' => collect($dashbordDatas)->sum('TotalCancelledAmount'),
            'monthlyData' => $monthlyDatas
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function apiLowStock()
    {
        $products = Product::where('quantity', '<=', 200)
            ->orderBy('quantity', 'asc')
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->SKU,
                    'currentStock' => $product->quantity,
                    'status' => 'Low Stock'
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function apiOrders()
    {
        $orders = Order::orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'name' => $order->name ?? 'Guest',
                    'phone' => $order->phone ?? 'N/A',
                    'subtotal' => $order->subTotal,
                    'status' => ucfirst($order->status),
                    'orderDate' => $order->created_at ? Carbon::parse($order->created_at)->format('Y-m-d') : '',
                    'time' => $order->created_at ? Carbon::parse($order->created_at)->format('H:i:s') : '',
                    'totalItems' => OrderItem::where('order_id', $order->id)->sum('quantity'),
                    'deliveredOn' => $order->delivery_date ? Carbon::parse($order->delivery_date)->format('Y-m-d') : ''
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function apiOrderDetails($order_id)
    {
        $order = Order::findOrFail($order_id);
        $orderItems = OrderItem::with(['product.category', 'product.brand'])
            ->where('order_id', $order_id)
            ->orderBy('id')
            ->get();
        $transaction = Transaction::where('order_id', $order_id)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'order' => $order,
                'items' => $orderItems,
                'transaction' => $transaction
            ]
        ]);
    }

    public function apiUpdateOrderStatus(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        
        // Accept both field names from frontend
        $newStatus = $request->status ?? $request->order_status;
        
        if (!$newStatus || !in_array($newStatus, ['processing', 'delivered', 'cancelled'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status. Must be: processing, delivered, or cancelled'
            ], 400);
        }

        $order->status = $newStatus;

        if($newStatus == 'delivered') {
            $order->delivery_date = Carbon::now();
        } else if($newStatus == 'cancelled') {
            $order->cancelled_date = Carbon::now();
        }

        $order->save();

        if($newStatus == 'delivered') {
            $transaction = Transaction::where('order_id', $order_id)->first();
            if($transaction) {
                $transaction->status = 'paid';
                $transaction->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    public function apiProducts()
    {
        $products = Product::with(['category', 'brand'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function apiStoreProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'SKU' => 'required|unique:products,SKU',
            'stock_status' => 'required|in:inStock,outOfStock',
            'featured' => 'required|in:0,1',
            'quantity' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured == '1' ? 1 : 0;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if (config('cloudinary.cloudinary_url') || env('CLOUDINARY_URL')) {
                $product->image = $image->storeOnCloudinary('products')->getSecurePath();
            } else {
                $file_extension = $image->getClientOriginalExtension();
                $imageName = $current_timestamp . '.' . $file_extension;
                
                // Ensure directory exists
                $dest = public_path('uploads/products');
                if (!File::exists($dest)) {
                    File::makeDirectory($dest, 0755, true);
                }
                
                $this->generateProductsThumbnailsImage($image, $imageName);
                $product->image = $imageName;
            }
        }

        $gallery_arr = array();
        $files = null;
        if ($request->hasFile('images')) {
            $files = $request->file('images');
        } elseif ($request->hasFile('gallery_images')) {
            $files = $request->file('gallery_images');
        }

        if ($files) {
            if (config('cloudinary.cloudinary_url') || env('CLOUDINARY_URL')) {
                foreach ($files as $file) {
                    $uploaded = $file->storeOnCloudinary('products/gallery');
                    array_push($gallery_arr, $uploaded->getSecurePath());
                }
            } else {
                $counter = 1;
                foreach ($files as $file) {
                    $file_extension = $file->getClientOriginalExtension();
                    $gFileName = $current_timestamp . '-' . $counter . '.' . $file_extension;
                    $this->generateProductsThumbnailsImage($file, $gFileName);
                    array_push($gallery_arr, $gFileName);
                    $counter++;
                }
            }
            $product->images = implode(',', $gallery_arr);
        }

        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    public function apiShowProduct($id)
    {
        $product = Product::with(['category', 'brand', 'reviews'])->findOrFail($id);
        
        // Append review stats
        $product->average_rating = $product->reviews->count() > 0 
            ? round($product->reviews->avg('rating'), 1) 
            : 0;
        
        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }
    public function apiUpdateProduct(Request $request, $id)
{
    $product = Product::findOrFail($id);
    
    if ($request->has('name')) $product->name = $request->name;
    if ($request->has('slug')) $product->slug = $request->slug;
    if ($request->has('short_description')) $product->short_description = $request->short_description;
    if ($request->has('description')) $product->description = $request->description;
    if ($request->has('regular_price')) $product->regular_price = $request->regular_price;
    if ($request->has('sale_price')) $product->sale_price = $request->sale_price;
    if ($request->has('SKU')) $product->SKU = $request->SKU;
    
    // Handle stock_status - normalize to lowercase
    if ($request->has('stock_status')) {
        $status = strtolower($request->stock_status);
        // Handle both: instock, inStock, outofstock, outOfStock
        if (str_contains($status, 'in')) {
            $product->stock_status = 'instock';
        } else {
            $product->stock_status = 'outofstock';
        }
    }
    
    if ($request->has('featured')) {
        $product->featured = $request->featured == '1' ? 1 : 0;
    }
    if ($request->has('quantity')) $product->quantity = $request->quantity;
    if ($request->has('category_id')) $product->category_id = $request->category_id;
    if ($request->has('brand_id')) $product->brand_id = $request->brand_id;

    $current_timestamp = Carbon::now()->timestamp;

    if ($request->hasFile('image')) {
        if(File::exists(public_path('uploads/products/' . $product->image))) {
            File::delete(public_path('uploads/products/' . $product->image));
        }
        if(File::exists(public_path('uploads/products/thumbnails/' . $product->image))) {
            File::delete(public_path('uploads/products/thumbnails/' . $product->image));
        }
        
        $image = $request->file('image');
        if (config('cloudinary.cloudinary_url') || env('CLOUDINARY_URL')) {
            $product->image = $image->storeOnCloudinary('products')->getSecurePath();
        } else {
            $file_extension = $image->getClientOriginalExtension();
            $imageName = $current_timestamp . '.' . $file_extension;
            $this->generateProductsThumbnailsImage($image, $imageName);
            $product->image = $imageName;
        }
    }

    if($request->hasFile('images')) {
        if ($product->images) {
            foreach(explode(',', $product->images) as $ofile) {
                if(File::exists(public_path('uploads/products/' . $ofile))) {
                    File::delete(public_path('uploads/products/' . $ofile));
                }
                if(File::exists(public_path('uploads/products/thumbnails/' . $ofile))) {
                    File::delete(public_path('uploads/products/thumbnails/' . $ofile));
                }
            }
        }

        $gallery_arr = array();
        $files = $request->file('images');
        if (config('cloudinary.cloudinary_url') || env('CLOUDINARY_URL')) {
            foreach ($files as $file) {
                $uploaded = $file->storeOnCloudinary('products/gallery');
                array_push($gallery_arr, $uploaded->getSecurePath());
            }
        } else {
            $counter = 1;
            foreach ($files as $file) {
                $file_extension = $file->getClientOriginalExtension();
                $gFileName = $current_timestamp . '-' . $counter . '.' . $file_extension;
                $this->generateProductsThumbnailsImage($file, $gFileName);
                array_push($gallery_arr, $gFileName);
                $counter++;
            }
        }
        $product->images = implode(',', $gallery_arr);
    }

    $product->save();

    return response()->json([
        'success' => true,
        'message' => 'Product updated successfully',
        'data' => $product
    ]);
}

    

    public function apiDeleteProduct($id)
    {
        $product = Product::findOrFail($id);
        
        if(File::exists(public_path('uploads/products/' . $product->image))) {
            File::delete(public_path('uploads/products/' . $product->image));
        }
        if(File::exists(public_path('uploads/products/thumbnails/' . $product->image))) {
            File::delete(public_path('uploads/products/thumbnails/' . $product->image));
        }
        
        foreach(explode(',', $product->images) as $ofile) {
            if(File::exists(public_path('uploads/products/' . $ofile))) {
                File::delete(public_path('uploads/products/' . $ofile));
            }
            if(File::exists(public_path('uploads/products/thumbnails/' . $ofile))) {
                File::delete(public_path('uploads/products/thumbnails/' . $ofile));
            }
        }
        
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    public function apiCategories()
    {
        $categories = Category::orderBy('categoryName')->get();
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function apiStoreCategory(Request $request)
    {
        $request->validate([
            'categoryName' => 'required|string|max:255',
            'categorySlug' => 'required|string|max:255|unique:categories,slug',
        ]);

        $category = new Category();
        $category->categoryName = $request->categoryName;
        $category->slug = $request->categorySlug;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if (config('cloudinary.cloudinary_url') || env('CLOUDINARY_URL')) {
                $category->image = $image->storeOnCloudinary('categories')->getSecurePath();
            } else {
                $imageName = Carbon::now()->timestamp . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/categories');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $imageName);
                $category->image = $imageName;
            }
        }

        $category->save();
        return response()->json(['success' => true, 'message' => 'Category created', 'data' => $category], 201);
    }

    public function apiShowCategory($id)
    {
        $category = Category::findOrFail($id);
        return response()->json(['success' => true, 'data' => $category]);
    }

    public function apiUpdateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        if ($request->has('categoryName')) $category->categoryName = $request->categoryName;
        if ($request->has('categorySlug')) $category->slug = $request->categorySlug;

        if ($request->hasFile('image')) {
            if ($category->image && File::exists(public_path('uploads/categories/' . $category->image))) {
                File::delete(public_path('uploads/categories/' . $category->image));
            }
            $image = $request->file('image');
            if (config('cloudinary.cloudinary_url') || env('CLOUDINARY_URL')) {
                $category->image = $image->storeOnCloudinary('categories')->getSecurePath();
            } else {
                $imageName = Carbon::now()->timestamp . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/categories');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $imageName);
                $category->image = $imageName;
            }
        }

        $category->save();
        return response()->json(['success' => true, 'message' => 'Category updated', 'data' => $category]);
    }

    public function apiDeleteCategory($id)
    {
        $category = Category::findOrFail($id);
        if ($category->image && File::exists(public_path('uploads/categories/' . $category->image))) {
            File::delete(public_path('uploads/categories/' . $category->image));
        }
        $category->delete();
        return response()->json(['success' => true, 'message' => 'Category deleted']);
    }

    public function apiBrands()
    {
        $brands = Brand::orderBy('brandName')->get();
        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    }

    public function apiStoreBrand(Request $request)
    {
        $request->validate([
            'brandName' => 'required|string|max:255',
            'brandSlug' => 'required|string|max:255|unique:brands,slug',
        ]);

        $brand = new Brand();
        $brand->brandName = $request->brandName;
        $brand->slug = $request->brandSlug;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if (config('cloudinary.cloudinary_url') || env('CLOUDINARY_URL')) {
                $brand->image = $image->storeOnCloudinary('brands')->getSecurePath();
            } else {
                $imageName = Carbon::now()->timestamp . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/brands');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $imageName);
                $brand->image = $imageName;
            }
        }

        $brand->save();
        return response()->json(['success' => true, 'message' => 'Brand created', 'data' => $brand], 201);
    }

    public function apiShowBrand($id)
    {
        $brand = Brand::findOrFail($id);
        return response()->json(['success' => true, 'data' => $brand]);
    }

    public function apiUpdateBrand(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        if ($request->has('brandName')) $brand->brandName = $request->brandName;
        if ($request->has('brandSlug')) $brand->slug = $request->brandSlug;

        if ($request->hasFile('image')) {
            if ($brand->image && File::exists(public_path('uploads/brands/' . $brand->image))) {
                File::delete(public_path('uploads/brands/' . $brand->image));
            }
            $image = $request->file('image');
            if (config('cloudinary.cloudinary_url') || env('CLOUDINARY_URL')) {
                $brand->image = $image->storeOnCloudinary('brands')->getSecurePath();
            } else {
                $imageName = Carbon::now()->timestamp . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/brands');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $imageName);
                $brand->image = $imageName;
            }
        }

        $brand->save();
        return response()->json(['success' => true, 'message' => 'Brand updated', 'data' => $brand]);
    }

    public function apiDeleteBrand($id)
    {
        $brand = Brand::findOrFail($id);
        if ($brand->image && File::exists(public_path('uploads/brands/' . $brand->image))) {
            File::delete(public_path('uploads/brands/' . $brand->image));
        }
        $brand->delete();
        return response()->json(['success' => true, 'message' => 'Brand deleted']);
    }

    // ============================================
    // SLIDES API
    // ============================================

    public function apiSlides()
    {
        $slides = Slide::orderBy('created_at', 'desc')->get()->map(function($slide) {
            $slide->image_url = $slide->image ? (str_starts_with($slide->image, 'http') ? $slide->image : asset('uploads/slides/' . $slide->image)) : null;
            return $slide;
        });
        return response()->json(['success' => true, 'data' => $slides]);
    }

    public function apiStoreSlide(Request $request)
    {
        $request->validate([
            'tagline' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'subTitle' => 'required|string|max:255',
            'link' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $slide = new Slide();
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subTitle = $request->subTitle;
        $slide->link = $request->link;
        $slide->status = $request->status ?? 1;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if (config('cloudinary.cloudinary_url') || env('CLOUDINARY_URL')) {
                $slide->image = $image->storeOnCloudinary('slides')->getSecurePath();
            } else {
                $imageName = Carbon::now()->timestamp . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/slides');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $imageName);
                $slide->image = $imageName;
            }
        }

        $slide->save();

        return response()->json([
            'success' => true,
            'message' => 'Slide created successfully',
            'data' => $slide
        ], 201);
    }

    public function apiShowSlide($id)
    {
        $slide = Slide::findOrFail($id);
        $slide->image_url = $slide->image ? (str_starts_with($slide->image, 'http') ? $slide->image : asset('uploads/slides/' . $slide->image)) : null;
        return response()->json(['success' => true, 'data' => $slide]);
    }

    public function apiUpdateSlide(Request $request, $id)
    {
        $slide = Slide::findOrFail($id);

        if ($request->has('tagline')) $slide->tagline = $request->tagline;
        if ($request->has('title')) $slide->title = $request->title;
        if ($request->has('subTitle')) $slide->subTitle = $request->subTitle;
        if ($request->has('link')) $slide->link = $request->link;
        if ($request->has('status')) $slide->status = $request->status;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($slide->image && File::exists(public_path('uploads/slides/' . $slide->image))) {
                File::delete(public_path('uploads/slides/' . $slide->image));
            }
            $image = $request->file('image');
            if (config('cloudinary.cloudinary_url') || env('CLOUDINARY_URL')) {
                $slide->image = $image->storeOnCloudinary('slides')->getSecurePath();
            } else {
                $imageName = Carbon::now()->timestamp . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/slides');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $imageName);
                $slide->image = $imageName;
            }
        }

        $slide->save();

        return response()->json(['success' => true, 'message' => 'Slide updated successfully', 'data' => $slide]);
    }

    public function apiDeleteSlide($id)
    {
        $slide = Slide::findOrFail($id);
        if ($slide->image && File::exists(public_path('uploads/slides/' . $slide->image))) {
            File::delete(public_path('uploads/slides/' . $slide->image));
        }
        $slide->delete();
        return response()->json(['success' => true, 'message' => 'Slide deleted successfully']);
    }
    
    public function generateProductsThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/products');
        $thumbnailPath = public_path('uploads/products/thumbnails');
        
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }
        if (!File::exists($thumbnailPath)) {
            File::makeDirectory($thumbnailPath, 0755, true);
        }

        $image->move($destinationPath, $imageName);
        
        $img = Image::read($destinationPath . '/' . $imageName);
        $img->scaleDown(400, 400);
        $img->save($thumbnailPath . '/' . $imageName);
    }

    /**
     * Generate report data for admin printable reports
     */
    public function apiReport(Request $request)
    {
        $type = $request->query('type', 'sales'); // sales, inventory, orders
        $from = $request->query('from');
        $to = $request->query('to');

        // Build date range
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : Carbon::now()->startOfMonth();
        $toDate = $to ? Carbon::parse($to)->endOfDay() : Carbon::now()->endOfDay();

        $data = [];

        if ($type === 'sales' || $type === 'all') {
            // Sales summary
            $orders = Order::whereBetween('created_at', [$fromDate, $toDate])->get();
            $data['sales'] = [
                'totalOrders' => $orders->count(),
                'totalRevenue' => round($orders->sum('total'), 2),
                'totalSubtotal' => round($orders->sum('subTotal'), 2),
                'processingOrders' => $orders->where('status', 'processing')->count(),
                'deliveredOrders' => $orders->where('status', 'delivered')->count(),
                'cancelledOrders' => $orders->where('status', 'cancelled')->count(),
                'processingAmount' => round($orders->where('status', 'processing')->sum('total'), 2),
                'deliveredAmount' => round($orders->where('status', 'delivered')->sum('total'), 2),
                'cancelledAmount' => round($orders->where('status', 'cancelled')->sum('total'), 2),
            ];

            // Orders list
            $data['orders'] = $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'name' => $order->name,
                    'phone' => $order->phone,
                    'total' => $order->total,
                    'status' => ucfirst($order->status),
                    'date' => Carbon::parse($order->created_at)->format('M d, Y'),
                    'items_count' => $order->orderItems->count(),
                    'address' => implode(', ', array_filter([
                        $order->sitio, $order->barangay, $order->city
                    ])),
                ];
            });
        }

        if ($type === 'inventory' || $type === 'all') {
            // Inventory report
            $products = Product::with(['category', 'brand'])->orderBy('name')->get();
            $data['inventory'] = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->SKU,
                    'category' => $product->category->categoryName ?? 'N/A',
                    'brand' => $product->brand->brandName ?? 'N/A',
                    'regular_price' => $product->regular_price,
                    'sale_price' => $product->sale_price,
                    'quantity' => $product->quantity,
                    'stock_status' => $product->stock_status,
                ];
            });

            $data['inventorySummary'] = [
                'totalProducts' => $products->count(),
                'totalStock' => $products->sum('quantity'),
                'lowStock' => $products->where('quantity', '<=', 200)->where('quantity', '>', 0)->count(),
                'outOfStock' => $products->where('quantity', '<=', 0)->count(),
                'totalValue' => round($products->sum(function ($p) {
                    return $p->regular_price * $p->quantity;
                }), 2),
            ];
        }

        if ($type === 'orders' || $type === 'all') {
            // Detailed orders with items
            $ordersDetailed = Order::with(['orderItems.product', 'user', 'transaction'])
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->orderBy('created_at', 'desc')
                ->get();

            $data['ordersDetailed'] = $ordersDetailed->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer' => $order->name,
                    'phone' => $order->phone,
                    'address' => implode(', ', array_filter([
                        $order->sitio, $order->barangay, $order->city
                    ])),
                    'landmark' => $order->landmark,
                    'subtotal' => $order->subTotal,
                    'total' => $order->total,
                    'status' => ucfirst($order->status),
                    'date' => Carbon::parse($order->created_at)->format('M d, Y h:i A'),
                    'delivery_date' => $order->delivery_date ? Carbon::parse($order->delivery_date)->format('M d, Y') : null,
                    'payment_method' => $order->transaction->payment_method ?? 'N/A',
                    'payment_status' => ucfirst($order->transaction->status ?? 'N/A'),
                    'items' => $order->orderItems->map(function ($item) {
                        return [
                            'product' => $item->product->name ?? 'Deleted Product',
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'total' => round($item->price * $item->quantity, 2),
                        ];
                    }),
                ];
            });
        }

        return response()->json([
            'success' => true,
            'reportType' => $type,
            'dateRange' => [
                'from' => $fromDate->format('M d, Y'),
                'to' => $toDate->format('M d, Y'),
            ],
            'generatedAt' => Carbon::now()->format('M d, Y h:i A'),
            'data' => $data,
        ]);
    }

    // ==================== ADMIN ACCOUNTS ====================

    public function apiAccounts()
    {
        $users = \App\Models\User::orderByRaw("FIELD(utype, 'ADM', 'USR')")
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->first_name . ' ' . $u->last_name,
                    'email' => $u->email,
                    'mobile' => $u->mobile,
                    'utype' => $u->utype,
                    'created_at' => $u->created_at,
                ];
            });

        return response()->json(['success' => true, 'data' => $users]);
    }

    public function apiStoreAccount(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'mobile' => 'nullable|string',
        ]);

        $user = \App\Models\User::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'name' => $request->firstName . ' ' . $request->lastName,
            'email' => $request->email,
            'mobile' => $request->mobile ?? '',
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'utype' => 'ADM',
        ]);

        return response()->json(['success' => true, 'message' => 'Admin account created', 'data' => $user]);
    }

    public function apiDeleteAccount($id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete your own account'], 403);
        }

        $user->delete();
        return response()->json(['success' => true, 'message' => 'Account deleted']);
    }
}