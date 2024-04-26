<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeshgridOrderDetails extends Model
{
    use HasFactory,ModelTrait;
    public $table = 'meshgrid_order_details';

    public function client(){
        return $this->hasOne(Client::class,'id', 'client_id');
    }

    public function meshgrid(){
        return $this->hasOne(ProjectMeshGrid::class,'id', 'meshgrid_project_link_id');
    }

    public function project(){
        return $this->hasOne(Project::class,'id', 'project_id');
    }
    
    public function product(){
        return $this->hasOne(Stockaddition::class,'id', 'product_id');
    }
}
