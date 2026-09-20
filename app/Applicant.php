<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'house_no',
        'barangay',
        'municipality',
        'province',
        'zipcode',
        'country',
        'place_of_birth',
        'nationality',
        'email',
        'contact',
        'date_of_birth',
        'gender',
        'civil_status',
        'tin',
        'sss',
        'pagibig',
        'philhealth',
        'photo',
        'right_thumbmark',
        'left_thumbmark',
        'status',
        'created_by',
        'updated_by'
    ];
}
