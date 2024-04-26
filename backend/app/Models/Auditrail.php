<?php

namespace App\Models;

use App\Http\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditrail extends Model
{
    use HasFactory,ModelTrait;

    public function User()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    protected $hidden = [
        'object',
    ];
}
