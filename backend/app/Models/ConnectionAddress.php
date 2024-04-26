<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectionAddress extends Model
{
    use HasFactory,ModelTrait;
    public $table = "connection_address";
}
