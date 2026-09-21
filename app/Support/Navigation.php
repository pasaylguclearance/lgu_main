<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;

/**
 * Resolves config/navigation.php for the header (tab rail + burger).
 *
 * Presentation only. The one "gate" here — status == 1 picks a different
 * dashboard / printing URL — is the exact condition the old sidebar used.
 * No authorization decisions are made here; routes stay protected server-side.
 */
class Navigation
{
    /**
     * Navigation tree with hrefs resolved for the current user and
     * `active` flags computed from the current request.
     *
     * @return array
     */
    public static function items()
    {
        $isStatus1 = Auth::check() && (string) Auth::user()->status === '1';

        $items = [];
        foreach (config('navigation', []) as $item) {
            $items[] = static::resolve($item, $isStatus1);
        }

        return $items;
    }

    /**
     * @param  array  $item
     * @param  bool   $isStatus1
     * @return array
     */
    protected static function resolve(array $item, $isStatus1)
    {
        $item['href'] = static::hrefFor($item, $isStatus1);
        $item['active'] = static::matches($item);

        if (!empty($item['children'])) {
            $children = [];
            foreach ($item['children'] as $child) {
                $child = static::resolve($child, $isStatus1);
                $children[] = $child;
                if ($child['active']) {
                    $item['active'] = true;
                }
            }
            $item['children'] = $children;
        }

        return $item;
    }

    /**
     * @param  array  $item
     * @param  bool   $isStatus1
     * @return string|null
     */
    protected static function hrefFor(array $item, $isStatus1)
    {
        if ($isStatus1 && !empty($item['href_status1'])) {
            return $item['href_status1'];
        }

        return isset($item['href']) ? $item['href'] : null;
    }

    /**
     * @param  array  $item
     * @return bool
     */
    protected static function matches(array $item)
    {
        $patterns = isset($item['match']) ? $item['match'] : [];
        if (!empty($item['href'])) {
            $patterns[] = trim($item['href'], '/');
        }

        foreach ($patterns as $pattern) {
            if (request()->is($pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Two-letter initials for the avatar pill.
     *
     * @return string
     */
    public static function initials()
    {
        $user = Auth::user();
        if (!$user) {
            return '?';
        }

        $first = trim((string) $user->firstname);
        $last = trim((string) $user->lastname);
        $initials = mb_substr($first, 0, 1) . mb_substr($last, 0, 1);

        if ($initials === '') {
            $initials = mb_substr((string) $user->email, 0, 2);
        }

        return mb_strtoupper($initials);
    }

    /**
     * Display name for the account pill.
     *
     * @return string
     */
    public static function displayName()
    {
        $user = Auth::user();
        if (!$user) {
            return '';
        }

        $name = trim(implode(' ', array_filter([
            trim((string) $user->firstname),
            trim((string) $user->lastname),
        ])));

        return $name !== '' ? $name : (string) $user->email;
    }
}
