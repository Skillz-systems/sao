<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Miscellaneous extends Model
{
    use SoftDeletes,HasFactory,ModelTrait;
    public $table = "miscellaneous";
}
