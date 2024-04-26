<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory,ModelTrait;

     public function Subitem()
     {
         return $this->hasMany(Subitem::class);
     }

    public function Stockaddition()
    {
        return $this->hasMany(Stockaddition::class);
    }
}
