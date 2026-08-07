<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\coupon;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use App\Models\OrderItem;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }
    public function add_to_cart(Request $request)
    {
        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return response()->json([
            'status' => 'success',
            'message' => 'Product added successfully',
            'count' => Cart::instance('cart')->content()->count(),
        ]);
    }
    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);

        $qty = $product->qty + 1;

        Cart::instance('cart')->update($rowId, $qty);

        $item = Cart::instance('cart')->get($rowId);

        return response()->json([
            'success'  => true,
            'qty'      => $item->qty,
            'subtotal' => number_format((float) str_replace(',', '', $item->subtotal()), 0),
            'cartSubtotal' => number_format((float) str_replace(',', '', Cart::instance('cart')->subtotal()), 0),
            'cartTax'      => number_format((float) str_replace(',', '', Cart::instance('cart')->tax()), 0),
            'cartTotal'    => number_format((float) str_replace(',', '', Cart::instance('cart')->total()), 0),
            'count'        => Cart::instance('cart')->content()->count(),
        ]);
    }
    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);

        if ($product->qty > 1) {

            $qty = $product->qty - 1;

            Cart::instance('cart')->update($rowId, $qty);

            $item = Cart::instance('cart')->get($rowId);

            return response()->json([
                'success'      => true,
                'qty'          => $item->qty,
                'subtotal' => number_format((float) str_replace(',', '', $item->subtotal()), 0),
                'cartSubtotal' => number_format((float) str_replace(',', '', Cart::instance('cart')->subtotal()), 0),
                'cartTax'      => number_format((float) str_replace(',', '', Cart::instance('cart')->tax()), 0),
                'cartTotal'    => number_format((float) str_replace(',', '', Cart::instance('cart')->total()), 0),
                'count'        => Cart::instance('cart')->content()->count(),
            ]);
        }

        return response()->json([
            'success' => false
        ]);
    }
    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);

        return response()->json([
            'success'      => true,
            'count'        => Cart::instance('cart')->content()->count(),
            'cartSubtotal' => number_format((float) str_replace(',', '', Cart::instance('cart')->subtotal()), 0),
            'cartTax'      => number_format((float) str_replace(',', '', Cart::instance('cart')->tax()), 0),
            'cartTotal'    => number_format((float) str_replace(',', '', Cart::instance('cart')->total()), 0),
        ]);
    }
    public function empty_cart()
    {
        Cart::instance('cart')->destroy();

        return response()->json([
            'success' => true,
            'count' => 0,
        ]);
    }
    public function apply_coupon_code(Request $request)
    {
        $coupon_code = $request->coupon_code;

        if (!$coupon_code) {
            return redirect()->back()->with('error', 'Invalid coupon code!');
        }

        // subtotal (تحويله رقم عشان مشاكل الفواصل)
        $subtotal = floatval(str_replace(',', '', Cart::instance('cart')->subtotal()));

        // جلب الكوبون
        $coupon = coupon::where('code', $coupon_code)
            ->where('expiry_date', '>=', Carbon::today())
            ->where('cart_value', '<=', $subtotal)
            ->first();

        // لو مفيش كوبون
        if (!$coupon) {
            return redirect()->back()->with('error', 'Invalid coupon code!');
        }

        // حفظ الكوبون في السيشن
        Session::put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,   // fixed OR percent
            'value' => $coupon->value,
            'cart_value' => $coupon->cart_value,
            'expiry_date' => $coupon->expiry_date,
        ]);

        // حساب الخصم
        $discount = 0;

        if ($coupon->type == 'fixed') {
            $discount = $coupon->value;
        } else {
            $discount = ($subtotal * $coupon->value) / 100;
        }

        $subtotalAfterDiscount = $subtotal - $discount;
        $taxAfterDiscount = ($subtotalAfterDiscount * config('cart.tax')) / 100;
        $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

        // حفظ الحسابات
        Session::put('discounts', [
            'discount' => number_format($discount, 2, '.', ''),
            'subtotal' => number_format($subtotalAfterDiscount, 2, '.', ''),
            'tax' => number_format($taxAfterDiscount, 2, '.', ''),
            'total' => number_format($totalAfterDiscount, 2, '.', ''),
        ]);

        return redirect()->back()->with('success', 'Coupon has been applied');
    }
    public function remove_coupon_code()
    {
        Session::forget('coupon');
        Session::forget('discounts');
        return back()->with('success', 'Coupon has been removed!');
    }

    //////////////////////checkout/////////////
    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $address = Address::where('user_id', Auth::user()->id)->where('isdefault', 1)->first();
        return view('checkout', compact('address'));
    }
    public function place_an_order(Request $request)
    {
        $user_id = Auth::user()->id;
        $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();
        if (!$address) {
            $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|numeric|digits:11',
                'zip' => 'required|numeric|digits:6',
                'state' => 'required',
                'city' => 'required',
                'address' => 'required',
                'locality' => 'required',
                'landmark' => 'required',
            ]);
            $address = new Address();
            $address->name  = $request->name;
            $address->phone  = $request->phone;
            $address->zip  = $request->zip;
            $address->state  = $request->state;
            $address->city  = $request->city;
            $address->address  = $request->address;
            $address->locality  = $request->locality;
            $address->landmark  = $request->landmark;
            $address->country  = 'Egypt';
            $address->user_id = $user_id;
            $address->isdefault = true;
            $address->save();
        }
        $this->setAmountforcheckout();

        $order = new Order();
        $order->user_id = $user_id;
        $order->subtotal = Session::get('checkout')['subtotal'];
        $order->discount = Session::get('checkout')['discount'];
        $order->tax = Session::get('checkout')['tax'];
        $order->total = Session::get('checkout')['total'];
        $order->name = $address->name;
        $order->phone = $address->phone;
        $order->locality =  $address->locality;
        $order->address =  $address->address;
        $order->city = $address->city;
        $order->state =  $address->state;
        $order->country =  $address->country;
        $order->landmark =  $address->landmark;
        $order->zip =  $address->zip;
        $order->save();
        if ($request->mode == "card") {

            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            $transaction->mode = "card";
            $transaction->status = "pending";
            $transaction->save();
        } elseif ($request->mode == "paypal") {

            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            $transaction->mode = "paypal";
            $transaction->status = "pending";
            $transaction->save();
        } elseif ($request->mode == "cod") {

            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            $transaction->mode = "cod";
            $transaction->status = "pending";
            $transaction->save();
        }
        foreach (Cart::instance('cart')->content() as $item) {

            $orderItem = new OrderItem();
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            $orderItem->save();
        }

        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::forget('coupon');
        Session::forget('discounts');
        Session::put('order_id', $order->id);

        return redirect()->route('cart.order.confirmation');
    }
    public function setAmountforcheckout()
    {
        if (!Cart::instance('cart')->content()->count() > 0) {
            Session::forget('checkout');
            return;
        }
        if (Session::has('coupon')) {
            Session::put('checkout', [
                'discount' => Session::get('discounts')['discount'],
                'subtotal' => floatval(str_replace(',', '', Cart::instance('cart')->subtotal())),
                'tax' => floatval(str_replace(',', '', Cart::instance('cart')->tax())),
                'total' => floatval(str_replace(',', '', Cart::instance('cart')->total())),
            ]);
        } else {
            Session::put('checkout', [
                'discount' => 0,
                'subtotal' => floatval(str_replace(',', '', Cart::instance('cart')->subtotal())),
                'tax' => floatval(str_replace(',', '', Cart::instance('cart')->tax())),
                'total' => floatval(str_replace(',', '', Cart::instance('cart')->total())),
            ]);
        }
    }
    public function order_confirmation()
    {
        if (Session::has('order_id')) {
            $order = Order::find(Session::get('order_id'));
            return view('order-confirmation', compact('order'));
        }
        return redirect()->route('cart.index');
    }
}
