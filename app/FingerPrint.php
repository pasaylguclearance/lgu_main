<?php

namespace App;

use App\Concerns\TolerantDates;
use App\Concerns\HasVisibilityWindow;
use Illuminate\Database\Eloquent\Model;

class FingerPrint extends Model
{
    use HasVisibilityWindow, TolerantDates;

    //
}
