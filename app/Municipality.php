<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipality extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'municipality'
    ];

    public function new_application()
    {
        return $this->hasMany(NewApplication::class);
    }
}
