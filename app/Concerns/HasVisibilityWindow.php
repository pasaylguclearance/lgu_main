<?php

namespace App\Concerns;

use App\Scopes\VisibilityWindowScope;

/**
 * Apply the account-level visibility window to a model.
 * Optionally define visibilityParents(): array of relation names that must
 * also be visible for a row to be shown.
 */
trait HasVisibilityWindow
{
    public static function bootHasVisibilityWindow()
    {
        static::addGlobalScope(new VisibilityWindowScope);
    }

    /**
     * Query builder that ignores the visibility window. Only for internal
     * sequence/number generation — never for anything shown to the user.
     */
    public static function unrestricted()
    {
        return static::withoutGlobalScope(VisibilityWindowScope::class);
    }
}
