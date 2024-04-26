<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "clientname" => $this->clientname,
            "clienttype" => $this->clienttype,
            "phone" => $this->phone,
            "email" => $this->email,
            "load" => $this->load,
            "address" => $this->address,
        ];
    }
}
