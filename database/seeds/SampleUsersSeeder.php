<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

/**
 * Sample login accounts for testing / demos.
 *
 * The system has no roles: every user has the same access. The only
 * per-user setting is `status`, which is the header mode toggle
 * (PNP logo → 1, Pasay logo → 0) and decides which Dashboard /
 * Printing screen the user lands on. One pair of accounts per mode.
 *
 * Idempotent: re-running resets these accounts (incl. passwords).
 *   php artisan db:seed --class=SampleUsersSeeder
 */
class SampleUsersSeeder extends Seeder
{
    public function run()
    {
        $accounts = [
            // PNP mode (status 1) → /dashboard, /application/completed
            ['firstname' => 'Ramon',   'lastname' => 'Villanueva', 'email' => 'pnp.officer01@pasaypolice.local', 'password' => 'Pnp@2026!',   'status' => '1'],
            ['firstname' => 'Liza',    'lastname' => 'Santos',     'email' => 'pnp.officer02@pasaypolice.local', 'password' => 'Pnp@2026!',   'status' => '1'],
            // Pasay mode (status 0) → /dashboard/record, /application/completed/record
            ['firstname' => 'Carlo',   'lastname' => 'Dimaano',    'email' => 'pasay.staff01@pasaypolice.local', 'password' => 'Pasay@2026!', 'status' => '0'],
            ['firstname' => 'Andrea',  'lastname' => 'Reyes',      'email' => 'pasay.staff02@pasaypolice.local', 'password' => 'Pasay@2026!', 'status' => '0'],
        ];

        foreach ($accounts as $a) {
            User::updateOrCreate(
                ['email' => $a['email']],
                [
                    'firstname'   => $a['firstname'],
                    'middlename'  => '',
                    'lastname'    => $a['lastname'],
                    'suffix'      => '',
                    'profile_img' => 'default.jpg',
                    'status'      => $a['status'],
                    'created_by'  => '1',
                    'updated_by'  => '1',
                    'password'    => Hash::make($a['password']),
                ]
            );
        }
    }
}
