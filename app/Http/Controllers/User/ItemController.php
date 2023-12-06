<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Jobs\SendThanksMail;
use App\Models\PrimaryCategory;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');

        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('item');
            if(!is_null($id)) {//null判定
                $itemsId = Product::availableItems()->where('products.id', $id)->exists();
                if(!$itemsId) {
                    abort(404);//404表示
                }
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        //非同期処理
        SendThanksMail::dispatch();

        //同期処理
        // Mail::to('test@example.com')
        // ->send(new TestMail());

        $categories = PrimaryCategory::with('secondary')
        ->get();
        $products = Product::availableItems()
        ->selectCategory($request->category ?? '0')
        ->searchKeyword($request->keyword)
        ->sortOrder($request->sort)
        ->paginate($request->pagination ?? '20');

        return view('user.index',
            compact(
                'categories',
                'products'
        ));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');

        if($quantity > 9) {
            $quantity = 9;
        }

        return view('user.show', compact('product', 'quantity'));
    }
}
