<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $customer = auth()->user();

        $cart = Cart::query()
            ->where('customer_id', $customer->id)
            ->where('status', 1)
            ->with(['items.product.media', 'items.product.brand', 'items.product.category'])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty',
                'data' => []
            ], 200);
        }

        return response()->json([
            'message' => 'Cart retrieved successfully',
            'data' => CartItemResource::collection($cart->items)
        ], 200);
    }


//    public function store(CartRequest $request)
//    {
//        $customer = auth()->user();
//        $cart = $customer->cart;
//
//        if (!$cart) {
//            $cart = Cart::create([
//                'customer_id' => $customer->id,
//                'status' => 1
//            ]);
//        }
//
//        $addedItems = [];
//
//        foreach ($request->items as $item) {
//            $prod = CartItem::query()
//                ->where('cart_id', $cart->id)
//                ->where('product_id', $item['product_id'])
//                ->first();
//
//            if ($prod) {
//                $prod->quantity += $item['quantity'] ?? 1;
//                $prod->save();
//
//                $addedItems[] = $prod;
//            } else {
//                $newItem = CartItem::create([
//                    'cart_id' => $cart->id,
//                    'product_id' => $item['product_id'],
//                    'quantity' => $item['quantity'] ?? 1,
//                    'price' => $item['price']
//                ]);
//
//                $addedItems[] = $newItem;
//            }
//        }
//
//        return response()->json([
//            'message' => 'Item added to cart successfully',
//            'data' => CartResource::collection($addedItems),
//        ]);
//    }

    public function store(CartRequest $request)
    {
        $customer = auth()->user();

        $cart = $customer->cart()->where('status', 1)->first();

        if (!$cart) {
            $cart = Cart::create([
                'customer_id' => $customer->id,
                'status' => 1,
            ]);
        }

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $request->quantity ?? 1;
            $existingItem->price = $request->price;
            $existingItem->save();

            return response()->json([
                'message' => 'Cart item updated successfully',
                'data' => $existingItem,
            ]);
        }

        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity ?? 1,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'Item added to cart successfully',
            'data' => $cartItem,
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'integer|exists:products,id'
        ]);

        $customer = auth()->user();
        $cart = Cart::query()
            ->where('customer_id',$customer->id)
            ->where('status',1)
            ->first();

        if(!$cart) {
            return response()->json([
                'message' => 'No active cart found'
            ],404);
        }

        CartItem::query()
            ->where('cart_id',$cart->id)
            ->whereIn('product_id',$request->product_ids)
            ->delete();

        return response()->json([
            'message' => 'Selected items removed from cart successfully.'
        ]);
    }
}
