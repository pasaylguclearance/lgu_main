<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nationality extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nationality'
    ];

    public function new_application()
    {
        return $this->hasMany(NewApplication::class);
    }
}
