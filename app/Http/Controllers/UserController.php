<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('user.orders', compact('orders'));
    }

    public function orderDetails($order_id)
    {
        $order = Order::where('user_id', Auth::user()->id)->where('id', $order_id)->first();
        if($order)
        {
            $orderItems = OrderItem::where('order_id', $order_id)->orderBy('id')->paginate(12);
            $transaction = Transaction::where('order_id', $order_id)->first();
            return view('user.order-details', compact('order', 'orderItems', 'transaction'));
        }
        else
        {
            return redirect()->route('login');
        }
    }

    public function orderCancel(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = "cancelled";
        $order->cancelled_date = Carbon::now();
        $order->save();

        return redirect()->back()->with('status', "Order Cancelled Successfully!");
    }

    public function accountDetails()
    {
        return view('user.account-details', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:email,'.$request->id,
            'mobile' => 'nullable|digits:11|unique:mobile,',
        ]);

        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;

        $user->update();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function showChangePasswordForm()
    {

        $user = User::find(Auth::user()->id);
        return view('user.change-password', compact('user'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::find(Auth::user()->id);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Password changed successfully!');
    }
}
