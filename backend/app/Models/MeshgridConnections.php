<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeshgridConnections extends Model
{
    use HasFactory,ModelTrait;
    public $table = "meshgrid_connections";

    public function projectMeshgrid(){
        return $this->hasOne(ProjectMeshGrid::class,'id', 'meshgrid_id');
    }

    public function client(){
        return $this->hasOne(Client::class,'id', 'client_id');
    }

    public function address(){
        return $this->hasOne(MeshgridConnectionAddress::class,'id', 'address_id');
    }

    public function connectonDetails(){
        return $this->hasMany(MeshgridConnectionDetails::class,'project_id', 'id');
    }

    public function payments(){
        return $this->hasMany(Payment::class,"meshgrid_id","id");
    }

    public function project(){
        return $this->hasOne(ProjectMeshGrid::class,"id","meshgrid_id");
    }

    public function scopeWithAll($query) 
{
    $query->with('project', 'payments', 'connectonDetails','address','client','address.state',"address.lga");
}
}
