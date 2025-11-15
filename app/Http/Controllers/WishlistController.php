<?php

namespace App\Http\Controllers;

use App\Http\Requests\WishlistRequest;
use App\Http\Resources\WishlistResource;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{

    public function index()
    {
        $customerId = auth()->id();

        $wishlists = Wishlist::query()
            ->where('customer_id', $customerId)
            ->with(['product.media'])
            ->get();

        return WishlistResource::collection($wishlists);
    }

    public function store(WishlistRequest $request)
    {
        $customerId = auth()->id();
        $exists = Wishlist::query()
            ->where('customer_id', $customerId)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'The product already exists in wishlist'
            ], 409);
        }

        Wishlist::create([
            'customer_id' => $customerId,
            'product_id' => $request->product_id
        ]);

        return response()->json([
            'message' => 'Successfully added product to wishlist'
        ]);
    }

    public function destroy($id)
    {
        $customer = auth()->id();
        Wishlist::query()
            ->where('customer_id', $customer)
            ->where('product_id', $id)
            ->delete();

        return response()->json([
            'message' => 'Item deleted successfully'
        ]);

    }
}
