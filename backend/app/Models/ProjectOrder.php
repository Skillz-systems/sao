<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectOrder extends Model
{
    use HasFactory,ModelTrait;
    public $table = 'project_order'; 

    public function orderaddress(){
        return $this->hasOne(ProjectAddress::class,'projects_id',"project_id");
    }

    public function orderdetails(){
        return $this->hasMany(ProjectOrderDetails::class,'order_id');
    } 

    public function payments(){
        return $this->hasMany(Payment::class,"order_id","id");
    }
    

    
}
