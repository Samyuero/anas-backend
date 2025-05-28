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
use App\Models\User;
use App\Models\Transaction;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->id);
    
        // Get total quantity of this product in the cart
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

    public function checkout()
    {
        if(!Auth::check())
        {
            return redirect()->route('login');
        }
        $address = Address::where('user_id', Auth::user()->id)->where('isDefault', 1)->first();
        return view('checkout', compact('address'));
    }

    public function editAddress()
    {
        if(!Auth::check())
        {
            return redirect()->route('login');
        }
        $address = Address::where('user_id', Auth::user()->id)->first();
        return view('edit-address', compact('address'));
    }
    public function updateAddress(Request $request)
    {
        $request->validate([
            'city' => '',
            'barangay' => '',
            'sitio' => '',
            'landmark' => ''
        ]);     

        $user_id = Auth::user()->id;
        $address = Address::where('user_id', $user_id);
        $address->city = $request->city;
        $address->barangay = $request->barangay;
        $address->sitio = $request->sitio;
        $address->landmark = $request->landmark;
        $address->update();

        return redirect()->back()->with('success', 'Address updated successfully.');
    }

    public function placeOrder(Request $request)
    {
        $validatedData = $request->validate([
            'payment_method' => 'required|in:cod',
        ], [
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in' => 'The selected payment method is invalid.',
        ]);

        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_mobile = Auth::user()->mobile;
        $address = Address::where('user_id', $user_id)->where('isDefault', true)->first();

        if(!$address)
        {
            $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|numeric|digits:11',
                'city' => 'required',
                'barangay' => 'required',
                'sitio' => 'required',
                'landmark' => 'required'
            ]);

            $address = new Address();

            $address->user_id = $user_id;
            $address->name = $user_name;
            $address->phone = $user_mobile;
            $address->city = $request->city;
            $address->barangay = $request->barangay;
            $address->sitio = $request->sitio;
            $address->landmark = $request->landmark;
            $address->isDefault = true;
            $address->save();
        }

        $this->setAmountForCheckout();

        $order = new Order();

        $order->user_id = $user_id;
        $order->subTotal = Session::get('checkout')['subTotal'];
        $order->total = Session::get('checkout')['total'];        
        $order->name = $address->name;
        $order->phone = $address->phone;
        $order->city = $address->city;
        $order->barangay = $address->barangay;
        $order->sitio = $address->sitio;
        $order->landmark = $address->landmark;
        $order->save();        

        foreach(Cart::instance('cart')->content() as $item)
        {
            $orderItem  = new OrderItem();            
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            $orderItem->save();

            Product::where('id', $item->id)->decrement('quantity', $item->qty);
        }
                
            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            $transaction->payment_method = $validatedData['payment_method'];
            $transaction->status = 'pending';
            $transaction->save();
        
        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::put('order_id', $order->id);
        
        return redirect()->route('cart.order.confirmation');

    }

    public function setAmountForCheckout()
    {
        if(!Cart::instance('cart')->content()->count() > 0 )
        {
            Session::forget('checkout');
            return;
        }
        else
        {
            Session::put('checkout', [
                'subTotal' => (float) str_replace(',', '', Cart::instance('cart')->subTotal()),
                'total' => (float) str_replace(',', '', Cart::instance('cart')->total())
            ]);
        }
    }

    public function orderConfirmation()
    {
        if(Session::has('order_id'))
        {
            $order = Order::find(Session::get('order_id'));
            return view('order-confirmation', compact('order'));
        }
        return redirect()->route('cart.index');
    }
}