<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeshgridProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            
            "productname" => $this->productname ,
            "project_id" => $this->project_id ,
            "maxmum_connection" => $this->maxmum_connection ,
            "mesh_grid_price" => $this->mesh_grid_price ,
            "pod_installation_price" => $this->pod_installation_price ,
        ];
    }
}
