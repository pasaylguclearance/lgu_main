<?php

namespace App;

use App\Concerns\HasVisibilityWindow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes, HasVisibilityWindow;

    /** Rows are hidden for a restricted account unless the applicant is visible too. */
    public function visibilityParents()
    {
        return ['new_application'];
    }

    protected $fillable = [
        'new_application_id',
        'type',
        'date',
        'status',
        'finding',
        'derogatory',
    ];

    public function new_application()
    {
        return $this->belongsTo(NewApplication::class, 'new_application_id');
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }
    
    public function renew()
    {
        return $this->hasOne(Renewal::class, 'application_id')->orderBy('date_renew', 'asc');
    }
}
