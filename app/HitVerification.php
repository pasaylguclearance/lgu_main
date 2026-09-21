<?php

namespace App;

use App\Concerns\TolerantDates;
use App\Concerns\HasVisibilityWindow;
use Illuminate\Database\Eloquent\Model;

class HitVerification extends Model
{
    use HasVisibilityWindow, TolerantDates;

    //
}
