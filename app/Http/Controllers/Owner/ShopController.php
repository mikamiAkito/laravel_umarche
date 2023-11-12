<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {
            // dd($request->route()->parameter('shop'));//文字列
            // dd(Auth::id());//数字
            $id = $request->route()->parameter('shop');//shopのid取得
            if(!is_null($id)) {//null判定
                $shopsOwnerid = Shop::findOrFail($id)->owner->id;
                $shopid = (int)$shopsOwnerid;//キャスト文字列－＞数値に型変換
                $ownerid = Auth::id();
                if($shopid !== $ownerid) {//同じでなかったら
                    abort(404);//404表示
                }
            }
            return $next($request);
        });
    }

    public function index()
    {
        $ownerid = Auth::id();
        $shops = Shop::where('owner_id', $ownerid)->get();

        return view('owner.shops.index', compact('shops'));
    }

    public function edit($id)
    {
        dd(Shop::findOrFail($id));
    }

    public function update(Request $request, $id) {}
}
