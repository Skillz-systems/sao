<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeshgridConectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "connection_number" => $this->connection_number,
            "order_description" => $this->order_description,
            "meshgridproject" => new MeshgridProjectResource($this->whenLoaded('project')),
            "client" => new ClientResource($this->whenLoaded('client')),
            "address" => new MeshgridAddressResource($this->whenLoaded('address')),
            "amount" => $this->amount,
            "actual_amount"  => $this->actual_amount,
            "discount" => $this->discount,
        ];
    }
}
