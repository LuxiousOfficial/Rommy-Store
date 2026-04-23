<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreBallanceHistoryResource extends JsonResource
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
            'store_ballance' => new StoreBallanceResource($this->storeBallance),
            'type' => $this->type,
            'reference_id' => $this->reference_id,
            'reference_type' => $this->reference_type,
            'amount' => (float)(string)$this->amount,
            'remarks' => $this->remarks,
        ];
    }
}
