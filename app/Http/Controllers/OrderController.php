<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $rowsPerPage = $request->input('rowsPerPage', 50);
        $sortBy = $request->input('sortBy', 'id');
        $descending = $request->boolean('descending', false);
        $filters = json_decode($request->input('filters', '{}'), true);

        $user = auth()->user();

        $query = Order::query()
            ->where('user_id', $user->id)
            ->with('items');


        if (!empty($filters['query'])) {
            $query->queryFilter($filters['query']);
        }

        if (array_key_exists('status', $filters)) {
            $query->statusFilter($filters['status']);
        }

        $query->orderBy($sortBy, $descending ? 'desc' : 'asc');

        $orders = $query->paginate($rowsPerPage, ['*'], 'page', $page);


        return OrderResource::collection($orders);

    }
}
