<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class WishlistController extends Controller
{
    public function index(){
        $items =Cart::instance('wishlist')->content();
        return view('wishlist',compact('items'));
    }
 public function toggleWishlist(Request $request)
{
    $wishlist = Cart::instance('wishlist');

    $item = $wishlist->content()->where('id', $request->id)->first();

    if ($item) {

        $wishlist->remove($item->rowId);

        return response()->json([
            'success' => true,
            'action'  => 'removed',
            'count'   => $wishlist->count(),
        ]);
    }

    $newItem = $wishlist->add(
        $request->id,
        $request->name,
        $request->quantity,
        $request->price
    );

    $newItem->associate('App\Models\Product');

    return response()->json([
        'success' => true,
        'action'  => 'added',
        'count'   => $wishlist->count(),
    ]);
}
    public function empty_wishlist(){
        Cart::instance('wishlist')->destroy();
        return redirect()->back();
    }
   public function move_to_cart($rowId)
{
    $item = Cart::instance('wishlist')->get($rowId);

    Cart::instance('wishlist')->remove($rowId);

    Cart::instance('cart')
        ->add($item->id, $item->name, $item->qty, $item->price)
        ->associate('App\Models\Product');

    return response()->json([
        'success' => true,
        'cart_count' => Cart::instance('cart')->count(),
        'wishlist_count' => Cart::instance('wishlist')->count(),
    ]);
}
public function remove($rowId)
{
    Cart::instance('wishlist')->remove($rowId);

    return response()->json([
        'success' => true,
        'count' => Cart::instance('wishlist')->count(),
    ]);
}
}
