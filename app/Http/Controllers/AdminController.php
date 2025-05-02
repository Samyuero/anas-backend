<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;  
use App\Models\Product;  
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
    public function brands()
    {
        $brands = Brand::orderBy('id', 'desc')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function createBrand()
    {
        return view('admin.create_brand');
    }
    public function storeBrand(Request $request)
    {
        $request->validate([
            'brandName' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $brand = new Brand();
        $brand->brandName = $request->brandName;
        $brand->slug = Str::slug($request->brandName);

        $image = $request->file('image');
        $file_extenstion = $request->file('image')->getClientOriginalExtension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extenstion;
        $this->generateBrandThumbnailsImage($image, $file_name);
        $brand->image = $file_name;
        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Brand created successfully.');
    }

    public function editBrand($id)
    {
        $brand = Brand::find($id);
        return view('admin.edit_brand', compact('brand'));
    }

    public function updateBrand(Request $request)
    {
        $request->validate([
            'brandName' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $brand = Brand::find($request->id);
        $brand->brandName = $request->brandName;
        $brand->slug = Str::slug($request->brandName);

        if ($request->hasFile('image')) {
            if(File::exists(public_path('uploads/brands/' . '/' . $brand->image))) {

                File::delete(public_path('uploads/brands/' . '/' . $brand->image));
            }
            $image = $request->file('image');
            $file_extenstion = $request->file('image')->getClientOriginalExtension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extenstion;
            $this->generateBrandThumbnailsImage($image, $file_name);
            $brand->image = $file_name;
        }
        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Brand updated successfully.');

    }

    public function generateBrandThumbnailsImage($image, $imageName)
    {
        $destination_path = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function($constraint) {
            $constraint->aspectRatio();
        })->save($destination_path . '/' . $imageName);
    }

    public function deleteBrand($id)
    {
        $brand = Brand::find($id);
        if ($brand) {
            if(File::exists(public_path('uploads/brands/' . '/' . $brand->image)))
            {
                File::delete(public_path('uploads/brands/' . '/' . $brand->image));
            }
            $brand->delete();
            return redirect()->route('admin.brands')->with('status', 'Brand deleted successfully.');
        }
    }

    public function categories()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function createCategory()
    {
        $brands = Brand::orderBy('id', 'desc')->get();
        return view('admin.create_category', compact('brands'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'categoryName' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            
        ]);

        $category = new Category();
        $category->categoryName = $request->categoryName;
        $category->slug = Str::slug($request->categoryName);

        $image = $request->file('image');
        $file_extenstion = $request->file('image')->getClientOriginalExtension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extenstion;
        $this->generateCategoryThumbnailsImage($image, $file_name);
        $category->image = $file_name;
        
        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Category created successfully.');
    }

    public function generateCategoryThumbnailsImage($image, $imageName)
    {
        $destination_path = public_path('uploads/categories');
        $img = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function($constraint) {
            $constraint->aspectRatio();
        })->save($destination_path . '/' . $imageName);
    }

    public function editCategory($id)
    {
        $category = Category::find($id);
        return view('admin.edit_category', compact('category'));
    }

    public function updateCategory(Request $request)
    {
        $request->validate([
            'categoryName' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:jpeg,png,jpg|max:2048',
        ]);

        $category = Category::find($request->id);
        $category->categoryName = $request->categoryName;
        $category->slug = Str::slug($request->categoryName);

        if ($request->hasFile('image')) {
            if(File::exists(public_path('uploads/categories/' . '/' . $category->image)))
            {
                File::delete(public_path('uploads/categories/' . '/' . $category->image));
            }
            $image = $request->file('image');
            $file_extenstion = $request->file('image')->getClientOriginalExtension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extenstion;
            $this->generateCategoryThumbnailsImage($image, $file_name);
            $category->image = $file_name;
        }
        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Category updated successfully.');
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        if ($category) {
            if(File::exists(public_path('uploads/categories/' . '/' . $category->image)))
            {
                File::delete(public_path('uploads/categories/' . '/' . $category->image));
            }
            $category->delete();
            return redirect()->route('admin.categories')->with('status', 'Category deleted successfully.');
        }
    }

    public function products()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::select('id', 'categoryName')->orderBy('categoryName')->get();
        $brands = Brand::select('id', 'brandName')->orderBy('brandName')->get();
        return view('admin.create_product', compact('categories', 'brands'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required',
            'brand_id' => 'required'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $current_timestamp = Carbon::now()->timestamp;

        if ($request->hasFile('image'))
        {
            $image = $request->file('image');
            $file_extenstion = $image->getClientOriginalExtension();
            $imageName = $current_timestamp . '.' . $file_extenstion;
            $this->generateProductsThumbnailsImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images'))
        {
            $allowedFileExtension = ['jpeg', 'jpg', 'png'];
            $files = $request->file('images');
            foreach ($files as $file)
            {
                $file_extenstions = $file->getClientOriginalExtension();
                $gcheck = in_array($file_extenstions, $allowedFileExtension);
                if ($gcheck)
                {
                    $gFileName = $current_timestamp . '-' . $counter . '.' . $file_extenstions;
                    $this->generateProductsThumbnailsImage($file, $gFileName);
                    array_push($gallery_arr, $gFileName);
                    $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('status', 'Product created successfully.');
    }

    public function generateProductsThumbnailsImage($image, $imageName)
    {
        $destinationPathThumbnail = public_path('uploads/products/thumbnails');
        $destination_path = public_path('uploads/products');
        $img = Image::read($image->path());
        $img->cover(540, 689, "top");
        $img->resize(540, 689, function($constraint) {
            $constraint->aspectRatio();
        })->save($destination_path . '/' . $imageName);

        $img->resize(104, 104, function($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPathThumbnail . '/' . $imageName);
    }

    public  function editProduct($id)
    {
        $product = Product::find($id);
        $categories = Category::select('id', 'categoryName')->orderBy('categoryName')->get();
        $brands = Brand::select('id', 'brandName')->orderBy('brandName')->get();
        return view('admin.edit_product', compact('product', 'categories', 'brands'));
    }

    public function updateProduct(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'unique:products,slug,' . $request->id,
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required',
            'brand_id' => 'required'
        ]);

        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if ($request->hasFile('image'))
        {
            if(File::exists(public_path('uploads/products' . '/' . $product->image)))
            {
                File::delete(public_path('uploads/products' . '/' . $product->image));
            }

            if(File::exists(public_path('uploads/products/thumnails' . '/' . $product->image)))
            {
                File::delete(public_path('uploads/products/thumbnails' . '/' . $product->image));
            }
            $image = $request->file('image');
            $file_extenstion = $image->getClientOriginalExtension();
            $imageName = $current_timestamp . '.' . $file_extenstion;
            $this->generateProductsThumbnailsImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images'))
        {
            foreach(explode(',',$product->images) as $ofile)
            {
                if(File::exists(public_path('uploads/products' . '/' . $ofile)))
                {
                    File::delete(public_path('uploads/products' . '/' . $ofile));
                }

                if(File::exists(public_path('uploads/products/thumnails' . '/' . $ofile)))
                {
                    File::delete(public_path('uploads/products/thumbnails' . '/' . $ofile));
                }
            }

            $allowedFileExtension = ['jpeg', 'jpg', 'png'];
            $files = $request->file('images');
            foreach ($files as $file)
            {
                $file_extenstions = $file->getClientOriginalExtension();
                $gcheck = in_array($file_extenstions, $allowedFileExtension);
                if ($gcheck)
                {
                    $gFileName = $current_timestamp . '-' . $counter . '.' . $file_extenstions;
                    $this->generateProductsThumbnailsImage($file, $gFileName);
                    array_push($gallery_arr, $gFileName);
                    $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
            $product->images = $gallery_images;
        }
        $product->save();
        return redirect()->route('admin.products')->with('status', 'Product updated successfully.');
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if ($product) {
            if(File::exists(public_path('uploads/products' . '/' . $product->image)))
            {
                File::delete(public_path('uploads/products' . '/' . $product->image));
            }

            if(File::exists(public_path('uploads/products/thumnails' . '/' . $product->image)))
            {
                File::delete(public_path('uploads/products/thumbnails' . '/' . $product->image));
            }
            foreach(explode(',',$product->images) as $ofile)
            {
                if(File::exists(public_path('uploads/products' . '/' . $ofile)))
                {
                    File::delete(public_path('uploads/products' . '/' . $ofile));
                }

                if(File::exists(public_path('uploads/products/thumnails' . '/' . $ofile)))
                {
                    File::delete(public_path('uploads/products/thumbnails' . '/' . $ofile));
                }
            }
            $product->delete();
            return redirect()->route('admin.products')->with('status', 'Product deleted successfully.');
        }
    }
}
