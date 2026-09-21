<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 
        'middlename', 
        'lastname', 
        'suffix', 
        'profile_img', 
        'status', 
        'email', 
        'created_by', 
        'updated_by', 
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // Account-level data visibility window (see App\Scopes\VisibilityWindowScope).
        // Intentionally NOT in $fillable: it can only be set server-side via
        // `php artisan user:visibility`, never through the Users form / mass assignment.
        'data_visibility_start_at' => 'datetime',
    ];

    /**
     * Whether this account only sees records created on/after a start date.
     *
     * @return bool
     */
    public function hasVisibilityWindow()
    {
        return !empty($this->data_visibility_start_at);
    }
}
