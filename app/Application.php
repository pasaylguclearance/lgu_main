<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

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
