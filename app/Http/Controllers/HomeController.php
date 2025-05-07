<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\Category;
use App\Models\Product;
use App\Models\Contact;

class HomeController extends Controller
{
    
    public function index()
    {
        $slides = Slide::where('status', 1)->get()->take(3);
        $categories = Category::orderBy('categoryName')->get();
        $bestsellers = Product::query()->withSum('orderItems', 'quantity')->orderByDesc('order_items_sum_quantity')->take(8)->get();
        $fproducts = Product::where('featured',1)->get()->take(8);
        return view('index', compact('slides', 'categories', 'bestsellers', 'fproducts'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email',
            'phone' => 'required|numeric|digits:11',
            'comment' => 'required'
        ]);

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->comment = $request->comment;
        $contact->save();

        return redirect()->back()->with('success', 'Your Message has been sent Successfully.');
    }

    public function searchProduct(Request $request)
    {
        $query = $request->input('query');
        $results = Product::where('name', 'LIKE', "%{$query}%")->get()->take(8);

        return response()->json($results);
    }
}
