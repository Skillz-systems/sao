<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeshgridConnectionAddress extends Model
{
    use HasFactory,ModelTrait;
    public $table = "meshgrid_connection_address";

    public function state(){
        return $this->hasOne(State::class,'stateid', 'states_id');
    }

    public function lga(){
        return $this->hasOne(Lga::class,'lgaid', 'lgas_id');
    }

    public function client(){
        return $this->hasOne(Client::class,'id', 'client_id');
    }
}
