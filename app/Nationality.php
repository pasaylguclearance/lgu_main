<?php

namespace App;

use App\Concerns\TolerantDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nationality extends Model
{
    use SoftDeletes, TolerantDates;

    protected $fillable = [
        'nationality'
    ];

    public function new_application()
    {
        return $this->hasMany(NewApplication::class);
    }
}
