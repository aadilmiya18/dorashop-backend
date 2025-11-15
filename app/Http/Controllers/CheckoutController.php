<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    // Secret key for eSewa UAT
    private $esewaSecret = '8gBm/:&EnhH.1/q';
    private $esewaProductCode = 'EPAYTEST';

    /**
     * Step 1: Create temporary order before redirecting to eSewa
     */
    public function createOrder(Request $request)
    {
        $user = auth()->user();

        // Generate unique transaction UUID
        $pid = "DS-" . time();

        $order = Order::create([
            'user_id' => $user->id,
            'order_id' => $pid,
            'name' => $request->name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'address' => $request->address,
            'subtotal' => $request->subtotal,
            'shipping' => $request->shipping,
            'total' => $request->total,
        ]);

        // Save order items
        foreach ($request->items as $item) {
            $order->items()->create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        // Generate HMAC signature
        $data = "total_amount={$order->total},transaction_uuid={$pid},product_code={$this->esewaProductCode}";
        $signature = base64_encode(hash_hmac('sha256', $data, $this->esewaSecret, true));

        return response()->json([
            'pid' => $pid,
            'total' => $order->total,
            'signature' => $signature,
        ]);
    }

    /**
     * Step 2: Handle eSewa success callback
     */
    public function esewaSuccess(Request $request)
    {
        $decoded = json_decode(base64_decode($request->query('data')), true);

        // Build string using signed_field_names
        $fields = explode(',', $decoded['signed_field_names']);
        $data = [];
        foreach ($fields as $field) {
            if ($field === 'signature') continue; // exclude signature itself
            $data[] = $field . '=' . $decoded[$field];
        }
        $dataString = implode(',', $data);

        $generatedSignature = base64_encode(hash_hmac('sha256', $dataString, $this->esewaSecret, true));

        if ($generatedSignature !== $decoded['signature']) {
            return response()->json(['message' => 'Invalid signature!'], 400);
        }

        // Update order
        $order = Order::where('order_id', $decoded['transaction_uuid'])->firstOrFail();
        $order->update([
            'status' => 'paid',
            'ref_id' => $decoded['transaction_code']
        ]);

        return redirect("http://localhost:9002/esewa/success?order={$order->id}");
    }


    /**
     * Step 3: Handle eSewa failure callback
     */
    public function esewaFailure(Request $request)
    {
        $decoded = json_decode(base64_decode($request->query('data')), true);

        $order = Order::where('order_id', $decoded['transaction_uuid'])->first();
        if ($order) {
            $order->update(['status' => 'failed']);
        }

        return redirect("http://localhost:9002/esewa/failure?order={$order->id}");
    }


    /**
     * Step 4: Optional status check (if needed)
     */
    public function checkStatus($transaction_uuid, $total_amount)
    {
        $url = "https://rc.esewa.com.np/api/epay/transaction/status/?product_code={$this->esewaProductCode}&total_amount={$total_amount}&transaction_uuid={$transaction_uuid}";

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        return $data; // returns status like COMPLETE, PENDING, etc.
    }
}
