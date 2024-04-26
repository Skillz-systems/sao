<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subitem extends Model
{
    use SoftDeletes, HasFactory, ModelTrait;

    public function Item()
    {
        return $this->belongsTo(Item::class, 'itemid');
    }

    public function Stockaddition()
    {
        return $this->hasMany(Stockaddition::class, "id", "subitemid");
    }
}
