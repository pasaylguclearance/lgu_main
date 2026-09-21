# Account-level data visibility window

Restricts ONE specific account (by `users.id`) to records created on/after a
start date-time. Every other account is untouched.

## Set / clear (the only way — it is not editable from the Users screen)

```bash
php artisan user:visibility <id-or-email> --start="2026-09-21 00:00:00"   # restrict
php artisan user:visibility <id-or-email> --clear                          # remove
php artisan user:visibility <id-or-email>                                  # show
php artisan user:visibility --list                                         # all restricted accounts
```

Inside Docker: `docker compose exec app php artisan user:visibility 6 --start=2026-09-21`.

## Rule

| Account | Sees |
|---|---|
| `data_visibility_start_at` = NULL (default, every existing user) | everything — unchanged behaviour |
| `data_visibility_start_at` = `2026-09-21 00:00:00` | rows with `created_at >= 2026-09-21 00:00:00` only |

Child records also require a visible parent, so historical data cannot leak
through related records:

- `applications` → visible only if its `new_applications` row is visible
- `payments` → visible only if its `applications` row is visible
- `renewals`, `hit_verifications`, `finger_prints` → by their own `created_at`

Consequence to be aware of: a *new* transaction/payment recorded against a
*historical* applicant is hidden from the restricted account (the applicant is
historical). Unrestricted accounts see it normally.

Not restricted (master data the account needs to work): `users`, `purposes`,
`municipalities`, `nationalities`, `religions`, `fees`, `dashboards`.

## Where it is enforced

`App\Scopes\VisibilityWindowScope` — an Eloquent **global scope** attached via
`App\Concerns\HasVisibilityWindow` to `NewApplication`, `Application`,
`Payment`, `Renewal`, `HitVerification`, `FingerPrint`. It runs on every query
the app makes for those models (all reads go through Eloquent — there is no raw
SQL): list screens, yajra DataTables (server-side + client), lookup/search/
autocomplete endpoints, `with()`/`whereHas()` relations, dashboard counts,
`find()`/`findOrFail()` detail loads, printables and picker modals. Direct URL /
AJAX requests therefore get the same filtered result (hidden IDs → 404 / empty).

Deliberately unscoped (`Model::unrestricted()`), because they are sequence
generators, not data shown to the user:

- `NewApplicationController@store` — next `application_no` / `ucid`
- `PaymentController@process` — next OR number

`PaymentController@store` validates `application_id` through the scoped model,
so a restricted account cannot record a payment against a hidden application by
posting the id directly.

## Files

- `database/migrations/2026_09_21_120000_add_data_visibility_start_at_to_users_table.php` (nullable column, no data touched)
- `app/Scopes/VisibilityWindowScope.php`, `app/Concerns/HasVisibilityWindow.php`
- `app/User.php` (datetime cast + `hasVisibilityWindow()`; column NOT in `$fillable`)
- `app/Console/Commands/UserVisibility.php`
- models: `NewApplication`, `Application`, `Payment`, `Renewal`, `HitVerification`, `FingerPrint`
- `NewApplicationController` (sequence + `findOrFail`), `ApplicationController` (`findOrFail`), `PaymentController` (sequence + scoped validation)

## Verified 2026-09-21 (dev, account #6 restricted from 01:14:12)

- restricted: masterlist/list/dashboard DataTables → 0 rows; search `reyes` → none; `/new_application/lookup/1` → 404; payments list → 0; detail page of a hidden applicant renders no data; POST payment for hidden application → validation error
- restricted creates applicant `PC-0000003` → visible to it (list 1, search hit); sequence continued correctly past hidden rows
- unrestricted users (#1, #3): identical results before/after; see all 3 applicants
- tampering `POST /user/update/6` with `data_visibility_start_at` → field ignored (not mass-assignable)
- full screen sweep as restricted user: all 200, no log errors

## Hardening pass (same day)

- `ApplicationController@store` / `@renewApplication`, `NewApplicationController@store` (`selected_applicant_id`):
  applicant IDs validated through the scoped model, so a restricted account cannot attach a
  transaction/renewal to a hidden applicant by posting its id.
- `fingerprint_right/left`: 404 (not a null-deref 500) when the applicant is hidden/missing.
- `UserController@update`: writes only the form's own fields (blank middlename/suffix → ''), fixing the
  pre-existing 500; `data_visibility_start_at`/`password` can never be set from that form.
- End-to-end as the restricted account: create applicant → transaction → pick in Add Payment → pay →
  shows in Printing Applications and Payments; posting a transaction for hidden applicant #1 → "not found".
- Unrestricted accounts re-probed afterwards: unchanged (see all applicants incl. the restricted user's).
