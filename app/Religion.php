<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Religion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'religion'
    ];

    public function new_application()
    {
        return $this->hasMany(NewApplication::class);
    }
}
