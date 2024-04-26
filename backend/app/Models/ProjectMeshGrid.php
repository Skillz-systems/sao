<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMeshGrid extends Model
{
    use HasFactory,ModelTrait;
    public $table = 'project_mesh_grid';

    public function connections(){
        return $this->hasMany(MeshgridConnections::class,'meshgrid_id', 'id');
    }

    public function project(){
        return $this->hasOne(Project::class,'id', 'project_id');
    }

    public function order(){
        return $this->hasOne( MeshgridOrderDetails::class,'meshgrid_project_link_id','id');
    }

    public function address(){
        return $this->hasOne( MeshgridAddress::class,'id','deployment_address_id');
    }

    public function product(){
        return $this->hasOne( Product::class,'id','product_id');
    }
}
