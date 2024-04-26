<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessories extends Model
{
    use HasFactory,ModelTrait;
    public $table = 'product_accessories';
    
    public function subitem(){
        return $this->hasOne(Subitem::class,"id","subitem_id");
    }
}
