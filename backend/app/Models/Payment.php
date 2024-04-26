<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory,ModelTrait;
    public $table = 'payment';
    
    public function project(){
        return $this->hasOne(Project::class,"id","project_id");
    }
}
