<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory,ModelTrait;
    public $oneOff = 1;
    public $installmental = 2;
    public $cardPayment = 1;
    public $transfer = 2;
    public function installations(){
        return $this->hasMany(ProjectOrder::class,'project_id');
    }
    public function payments(){
        return $this->hasMany(Payment::class,"project_id","id");
    }
    public function projectmeshgrid(){
        return $this->hasMany(ProjectMeshGrid::class,"project_id","id");
    }
    public function miscallaneous(){
        return $this->hasMany(ProjectMiscellaneous::class,"project_id","id");
    }
}

