<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMiscellaneous extends Model
{
    use HasFactory,ModelTrait;
    public $table = 'project_miscellaneous';
    
    public function mainmiscallaneous(){
        return $this->hasOne( Miscellaneous::class,"id","miscellaneous_id");
    }
   
    
}
