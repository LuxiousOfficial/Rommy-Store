<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'buyer' => new BuyerResource($this->buyer),
            'store' => new StoreResource($this->store),
            'address' => $this->address,
            'address_id' => $this->address_id,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'shipping' => $this->shipping,
            'shipping_type' => $this->shipping_type,
            'shipping_cost' => (float)(string) $this->shipping_cost,
            'tracking_number' => $this->tracking_number,
            'delivery_proof' => asset('storage/' . $this->delivery_proof),
            'delivery_status' => $this->delivery_status,
            'tax' => (float)(string) $this->tax,
            'grand_total' => (float)(string) $this->grand_total,
            'payment_status' => $this->payment_status,
            'snap_token' => $this->snap_token,
            'transaction_details' => TransactionDetailResource::collection($this->transactionDetails),
            'created_at' => $this->created_at
        ];
    }
}
