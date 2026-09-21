<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Manage the per-account data visibility window.
 *
 *   php artisan user:visibility 5 --start="2026-09-21 00:00:00"   # restrict user id 5
 *   php artisan user:visibility user@example.com --start=2026-09-21
 *   php artisan user:visibility 5 --clear                          # remove the restriction
 *   php artisan user:visibility --list                             # show restricted accounts
 *
 * This is the ONLY way to set the field: it is not mass-assignable, so the
 * Users screen (which every account can open) cannot change it.
 */
class UserVisibility extends Command
{
    protected $signature = 'user:visibility
                            {user? : users.id or email of the account}
                            {--start= : Only records created on/after this date-time are visible (Y-m-d or "Y-m-d H:i:s")}
                            {--clear : Remove the restriction from the account}
                            {--list : List accounts that currently have a visibility window}';

    protected $description = 'Set, clear or list the account-level data visibility start date';

    public function handle()
    {
        if ($this->option('list')) {
            return $this->listRestricted();
        }

        $ident = $this->argument('user');
        if ($ident === null) {
            $this->error('Give a users.id or email, or use --list.');
            return 1;
        }

        $user = ctype_digit((string) $ident) ? User::find((int) $ident) : User::where('email', $ident)->first();
        if (!$user) {
            $this->error("No user found for '{$ident}'.");
            return 1;
        }

        if ($this->option('clear')) {
            $user->forceFill(['data_visibility_start_at' => null])->save();
            $this->info("Restriction removed: #{$user->id} {$user->email} now sees all records.");
            return 0;
        }

        $start = $this->option('start');
        if (!$start) {
            $this->line("#{$user->id} {$user->email}: " . ($user->data_visibility_start_at ? 'visible from ' . $user->data_visibility_start_at : 'no restriction'));
            $this->line('Use --start="Y-m-d H:i:s" to set, or --clear to remove.');
            return 0;
        }

        try {
            $at = Carbon::parse($start);
        } catch (\Exception $e) {
            $this->error("Cannot parse '{$start}' as a date.");
            return 1;
        }

        $user->forceFill(['data_visibility_start_at' => $at])->save();
        $this->info("#{$user->id} {$user->email} now only sees records created on/after {$at->format('Y-m-d H:i:s')}.");
        return 0;
    }

    protected function listRestricted()
    {
        $rows = User::whereNotNull('data_visibility_start_at')
            ->orderBy('id')
            ->get(['id', 'firstname', 'lastname', 'email', 'data_visibility_start_at'])
            ->map(function ($u) {
                return [$u->id, trim($u->firstname . ' ' . $u->lastname), $u->email, $u->data_visibility_start_at];
            })->all();

        if (!$rows) {
            $this->line('No accounts have a visibility window.');
            return 0;
        }
        $this->table(['id', 'name', 'email', 'visible from'], $rows);
        return 0;
    }
}
