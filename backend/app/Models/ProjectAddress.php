<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAddress extends Model
{
    use HasFactory,ModelTrait;
    public $table = 'project_address';

    public function lga(){
        return $this->hasOne(Lga::class,'lgaid','lgas_id');
    }
    
    public function state(){
        return $this->hasOne(State::class,'stateid', 'states_id');
    }

}
