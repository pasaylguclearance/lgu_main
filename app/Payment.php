<?php

namespace App;

use App\Concerns\HasVisibilityWindow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes, HasVisibilityWindow;

    /** Rows are hidden for a restricted account unless the application is visible too. */
    public function visibilityParents()
    {
        return ['application'];
    }

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
