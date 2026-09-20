<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'application_id',
        'specification',
        'or_number',
        'ucid',
        'date_of_expiration',
        'amount'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
