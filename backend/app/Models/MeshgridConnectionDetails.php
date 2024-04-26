<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeshgridConnectionDetails extends Model
{
    use HasFactory,ModelTrait;
    public $table = "meshgrid_connection_details";

    public function product(){
        return $this->hasOne(Stockaddition::class,'id', 'product_id');
    }

    public function connection(){
        return $this->hasOne(MeshgridConnections::class,'id', 'order_id');
    }

    public function meshgrid(){
        return $this->hasOne(ProjectMeshGrid::class,'id', 'project_id');
    }

    public function client(){
        return $this->hasOne(Client::class,'id', 'client_id');
    }
}
