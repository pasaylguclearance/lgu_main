<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purpose extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'purpose',
        'cost'
    ];

    public function new_application()
    {
        return $this->hasOne(NewApplication::class);
    }
}
