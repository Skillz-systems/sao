<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeshgridAddress extends Model
{
    use HasFactory,ModelTrait;
    public $table = "meshgrid_address";

    public function client(){
        return $this->hasOne(Client::class,'id', 'client_id');
    }

    public function projectMeshgrid(){
        return $this->hasOne(ProjectMeshGrid::class,'id', 'project_id');
    }

    public function state(){
        return $this->hasOne(State::class,'id', 'states_id');
    }

    public function lga(){
        return $this->hasOne(Lga::class,'id', 'lgas_id');
    }

}
