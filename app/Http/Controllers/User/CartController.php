<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\User;

class CartController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $products = $user->products;
        $totalPrice = 0;

        foreach($products as $product) {
            $totalPrice += $product->price * $product->pivot->quantity;
        }

        // dd($totalPrice, $products);

        return view(
            'user.cart',
            compact('products', 'totalPrice')
        );
    }

    public function add(Request $request)
    {
        $itemInCart = Cart::where('user_id', Auth::id())
        ->where('product_id', $request->product_id)->first();

        if($itemInCart) {
            $itemInCart->quantity += $request->quantity;
            $itemInCart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }
        return redirect()->route('user.cart.index');
    }

    public function delete($id)
    {
        Cart::where('user_id', Auth::id())->where('product_id', $id)
        ->delete();

        return redirect()->route('user.cart.index');
    }

    public function checkout()
    {
        $user = User::findOrFail(Auth::id());
        $products = $user->products;

        $lineItems = [];
        foreach($products as $product)
        {
            $lineItem = [
                'name' => $product->name,
                'description' => $product->information,
                'name' => $product->price,
                'currency' => 'jpy',
                'quantity' => $product->pivot->quantity,
            ];
            array_push($lineItems, $lineItem);
        }
        
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = \Stripe\Checkout\Session::create([ 
            'payment_method_types' => ['card'],
            'line_items' => [[$lineItems]],
            'mode' => 'payment',
            'success_url' => route('user.items.index'),
            'cancel_url' => route('user.cart.index'),
        ]);

        $publicKey = env('STRIPE_PUBLIC_KEY');

        return view('user.checkout', compact('session', 'publicKey'));

    }
}