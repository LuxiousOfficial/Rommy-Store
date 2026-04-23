<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreBallanceResource extends JsonResource
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
            'store' => new StoreResource($this->store),
            'balance' => (float) (string) $this->balance,
            'store_ballance_histories' => StoreBallanceHistoryResource::collection($this->whenLoaded('storeBallanceHistories'))
        ];
    }
}
