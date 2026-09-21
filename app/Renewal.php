<?php

namespace App;

use App\Concerns\TolerantDates;
use App\Concerns\HasVisibilityWindow;
use Illuminate\Database\Eloquent\Model;

class Renewal extends Model
{
    use HasVisibilityWindow, TolerantDates;

    protected $fillable = [
        'application_id',
        'or_no',
        'issued_or_date',
        'cedula_no',
        'issued_date',
        'date_renew',
        'date_expiry',
        'created_by',
        'updated_by'
    ];
}
