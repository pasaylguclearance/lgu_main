<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

/**
 * Account-level data visibility window.
 *
 * When the authenticated user (matched by users.id) has
 * `data_visibility_start_at` set, every query on a model carrying this scope
 * only returns rows created on/after that instant. Users without the column
 * set (all existing accounts) are untouched: the scope adds nothing.
 *
 * Child records also require their parent to be visible (declared by the
 * model via visibilityParents()), so a hidden applicant can never surface
 * through a related transaction, payment, search result, count or export.
 *
 * Runs on every Eloquent query for the model — list screens, yajra
 * DataTables, lookups/autocomplete, with()/whereHas() relations, counts,
 * find()/findOrFail(), printables — so direct requests cannot bypass it.
 * Console/queue contexts have no authenticated user and are unaffected.
 */
class VisibilityWindowScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $start = static::startForCurrentUser();
        if ($start === null) {
            return;
        }

        $builder->where($model->getTable() . '.created_at', '>=', $start);

        if (method_exists($model, 'visibilityParents')) {
            foreach ((array) $model->visibilityParents() as $relation) {
                // The parent model carries the same scope, so whereHas()
                // automatically requires the parent to be visible too.
                $builder->whereHas($relation);
            }
        }
    }

    /**
     * The visibility start for the current request's user, or null when the
     * user is unrestricted / no user is authenticated.
     *
     * @return string|null  'Y-m-d H:i:s'
     */
    public static function startForCurrentUser()
    {
        try {
            if (!Auth::check()) {
                return null;
            }
            $user = Auth::user();
        } catch (\Throwable $e) {
            return null; // e.g. no session in console
        }

        $start = $user->data_visibility_start_at ?? null;
        if (!$start) {
            return null;
        }

        return $start instanceof \DateTimeInterface ? $start->format('Y-m-d H:i:s') : (string) $start;
    }
}
