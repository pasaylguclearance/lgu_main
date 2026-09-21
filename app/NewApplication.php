<?php

namespace App;

use App\Concerns\TolerantDates;
use App\Concerns\HasVisibilityWindow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewApplication extends Model
{
    use SoftDeletes, HasVisibilityWindow, TolerantDates;

    protected $fillable = [
            'application_no',
            'or_no',
            'cedula_no',
            'issued_at',
            'issued_date',
            'issued_or_date',
            'firstname',
            'middlename',
            'lastname',
            'suffix',
            'purpose_id',
            'application_type',
            'house_no',
            'street',
            'barangay',
            'municipality_id',
            'province',
            'country',
            'birthdate',
            'birth_place',
            'gender',
            'nationality_id',
            'civil_status',
            'religion_id',
            'height',
            'weight',
            'hair_color',
            'eye_color',
            'contact_number',
            'mole',
            'scar',
            'tattoo',
            'birthmark',
            'harelip',
            'skin_tag',
            'occupation_id',
            'occupation',
            'employer',
            'ucid',
            'employer_address',
            'blood_type',
            'contact_person',
            'contact_no',
            'contact_address',
            'finger_print_right',
            'finger_print_left',
            'fingerprint_right_data',
            'fingerprint_left_data',
            'picture',
            'signature',
            'status',
            'reference_num',
            'type',
            'stage'
    ];

    public function application()
    {
        return $this->hasMany(Inventory::class);
    }

    public function purpose()
    {
        return $this->belongsTo(Purpose::class, 'purpose_id');
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class, 'nationality_id');
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class, 'religion_id');
    }
    
    public function renew()
    {
        return $this->belongsTo(Renewal::class, 'id', 'application_id')->orderBy('renewals.date_renew', 'asc');
    }
}
