<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Transaction;
use App\Repositories\StoreBallanceHistoryRepository;
use App\Repositories\StoreBallanceRepository;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.serverKey');
        $hashedKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if($hashedKey !== $request->signature_key) {
            return response()->json(['message' => 'invalid signature key'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $transactionCode = $request->order_id;
        $transaction = Transaction::where('code', $transactionCode)->first();

        if(!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        switch($transactionStatus) {
            case 'capture';
            if($request->payment_type == 'credit_card') {
                if($request->fraud_status == 'challenge') {
                    $transaction->update(['payment_status' => 'unpaid']);
                }else {
                    $transaction->update(['payment_status' => 'paid']);

                    $store = Store::find($transaction->store_id);

                    $storeBallanceRepository = new StoreBallanceRepository;
                    $storeBallanceRepository->credit($store->storeBallance->id, $transaction->grand_total - $transaction->shipping_cost);

                    $storeBallanceHistoryRepository = new StoreBallanceHistoryRepository;
                    $storeBallanceHistoryRepository->create([
                        'type' => 'income',
                        'reference_id' => $transaction->id,
                        'reference_type' => Transaction::class,
                        'amount' => $transaction->grand_total - $transaction->shipping_cost,
                        'remakrs' => 'Payment received'
                    ]);
                }
            }
            break;
            case 'settlement';
            $transaction->update(['payment_status' => 'paid']);

            $store = Store::find($transaction->store_id);

            $storeBallanceRepository = new StoreBallanceRepository;
            $storeBallanceRepository->credit($store->storeBallance->id, $transaction->grand_total - $transaction->shipping_cost);
        
            $storeBallanceHistoryRepository = new StoreBallanceHistoryRepository;
            $storeBallanceHistoryRepository->create([
                'type' => 'income',
                'reference_id' => $transaction->id,
                'reference_type' => Transaction::class,
                'amount' => $transaction->grand_total - $transaction->shipping_cost,
                'remakrs' => 'Payment received'
            ]);
            break;
            case 'pending';
            $transaction->update(['payment_status' => 'unpaid']);
            break;
            case 'deny';
            $transaction->update(['payment_status' => 'failde']);
            break;
            case 'expire';
            $transaction->update(['payment_status' => 'failed']);
            break;
            case 'cancel';
            $transaction->update(['payment_status' => 'failed']);
            break;
            default;
            $transaction->update(['payment_status' => 'failed']);
            break;
        }
        return response()->json(['message' => 'Payment status updated successfully'], 200);
    }
}
