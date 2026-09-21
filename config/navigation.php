<?php

/*
|--------------------------------------------------------------------------
| Header navigation (replaces backend/partial/sidebar.blade.php)
|--------------------------------------------------------------------------
| Single source of truth for the desktop tab rail AND the mobile burger in
| backend/partial/header.blade.php. Mirrors the old sidebar link-for-link.
|
| 'href'        : path used for every user
| 'href_status1': when set, used instead of 'href' ONLY when
|                 Auth::user()->status == 1 — the exact gate the sidebar used.
|                 This is presentation-only; server-side authorization is
|                 unchanged and still enforced by the routes/controllers.
| 'match'       : extra request()->is() patterns that mark the item active.
*/

return [
    [
        'key'          => 'dashboard',
        'label'        => 'Dashboard',
        'icon'         => 'fa-tachometer-alt',
        'href'         => 'dashboard/record',
        'href_status1' => 'dashboard',
        'match'        => ['dashboard', 'dashboard/*'],
    ],
    [
        'key'      => 'registrations',
        'label'    => 'Registrations',
        'icon'     => 'fa-file-alt',
        'children' => [
            [
                'label' => 'Add Applicant',
                'icon'  => 'fa-user-plus',
                'href'  => 'new_application',
                'match' => ['new_application', 'new_application/*'],
            ],
            [
                'label' => 'Applicant Other Detail',
                'icon'  => 'fa-id-card',
                'href'  => 'application/detail',
                'match' => ['application/detail', 'application/detail/*'],
            ],
            [
                'label' => 'Printable Form',
                'icon'  => 'fa-print',
                'href'  => 'application/printable-form',
                'match' => ['application/printable-form', 'application/printable-form/*'],
            ],
            [
                'label'        => 'Printing Applications',
                'icon'         => 'fa-clipboard-check',
                'href'         => 'application/completed/record',
                'href_status1' => 'application/completed',
                'match'        => ['application/completed', 'application/completed/*'],
            ],
        ],
    ],
    [
        'key'   => 'payment',
        'label' => 'Payment',
        'icon'  => 'fa-money-bill-wave',
        'href'  => 'payment/process',
        'match' => ['payment', 'payment/*'],
    ],
    [
        'key'   => 'hit_verification',
        'label' => 'Hit Verification',
        'icon'  => 'fa-fingerprint',
        'href'  => 'hit-verification',
        'match' => ['hit-verification', 'hit-verification/*', 'hit_verification', 'hit_verification/*'],
    ],
    [
        'key'   => 'users',
        'label' => 'Users',
        'icon'  => 'fa-users',
        'href'  => 'user',
        'match' => ['user', 'user/*'],
    ],
    [
        'key'      => 'maintenance',
        'label'    => 'Maintenance',
        'icon'     => 'fa-cog',
        'children' => [
            ['label' => 'Clearance Purpose', 'icon' => 'fa-list', 'href' => 'purpose',      'match' => ['purpose', 'purpose/*']],
            ['label' => 'Municipality',      'icon' => 'fa-city',       'href' => 'municipality', 'match' => ['municipality', 'municipality/*']],
            ['label' => 'Nationality',       'icon' => 'fa-flag',       'href' => 'nationality',  'match' => ['nationality', 'nationality/*']],
            ['label' => 'Religion',          'icon' => 'fa-church', 'href' => 'religion',  'match' => ['religion', 'religion/*']],
        ],
    ],
];
