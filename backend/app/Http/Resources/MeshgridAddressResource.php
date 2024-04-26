<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeshgridAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "log" => $this->log ,
            "lat" => $this->lat ,
            "address_description" => $this->address_description ,
            "states" => new StateResource($this->whenLoaded("state")),
            "local_government" => new LocalgovernmentResource($this->whenLoaded("lga")) ,
        ];
    }
}
