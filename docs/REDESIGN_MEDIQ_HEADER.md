# PASAYPOLICE — Full Redesign Plan (MediQ header pattern, PNP red/blue)

**Status:** IMPLEMENTED on branch `design/mediq-header-v1` (2026-09-21) — uncommitted; see §10 for the implementation log
**Created:** 2026-09-21
**Scope:** design + navigation only. No route, controller, migration, or business-logic changes.

---

## 1. Goal

Rebuild the look of the entire Pasay Police Clearance system so it behaves like the
MediQ console:

1. **Remove the left sidebar.** All navigation moves into the header as buttons
   (a horizontal "tab rail" of pills on desktop, a burger menu below 1280 px).
2. **Recolor everything to the PNP logo palette** — red shield + navy-blue outline,
   with the yellow sun as a small accent. Replaces the template's `#3b7ddd` blue and the
   old `#c6262f`/`#222222` overrides.
3. **Every screen under `resources/views/backend/`** (the whole admin "backend" UI) plus
   the auth screens are covered — see the per-screen checklist in §7.

Printables (clearance certificate, ID card, printable form) are **excluded** — their
output must stay pixel-identical for the physical documents.

---

## 2. What we are copying from MediQ

Reference files (C:\PROJECTS\MEDIQ):

| MediQ file | What it shows |
|---|---|
| `resources/views/backend/pages/hms/transaction/patient_management/front_desk.blade.php` L267–360 | The console header: sticky gradient `<header>`, brand logo pill, workspace-switcher pill, **tab rail** of module screens (`pos-header-nav`), right-side actions (search, alerts, action pill, avatar account menu) |
| `resources/views/backend/partial/workspace_switcher.blade.php` | The "pill" button style (46 px tall, `rgba(255,255,255,.1)` fill, `rgba(255,255,255,.16)` border, 14 px radius) and the white dropdown panel (12 px radius, `0 18px 40px rgba(15,23,42,.28)` shadow) |
| `resources/views/backend/partial/_module_workspace_burger.blade.php` | The **mobile burger** (☰) — lists the current module's pages + "Switch Module"; shown below 1280 px, desktop tab rail takes over at ≥1280 px |
| `resources/views/backend/partial/_header_shim.blade.php` | Global rules to inherit: `html,body{overflow-x:clip}` (no sideways scroll ever), `resp-table` card-stacking below 1280 px, 240 ms page-enter fade, 42 px avatar with 13 px radius |

### Behaviour to reproduce

- Header is `position: sticky; top: 0`, full width, gradient background, white text.
- **Left:** logo pill → app title/"workspace" pill → tab rail (one pill per top-level menu
  item; groups like *Registrations* / *Maintenance* become a pill that opens a dropdown).
- **Right:** notifications bell, settings/account avatar with dropdown (Profile, Sign out).
- **Active tab:** `bg rgba(255,255,255,.15)` + `border rgba(255,255,255,.25)`; inactive:
  `text rgba(255,255,255,.85)`, hover `bg rgba(255,255,255,.10)`.
- **< 1280 px:** tab rail hides, burger appears; burger panel lists the same items
  (grouped with small uppercase section titles), closes on outside click / Esc.
- Content area becomes full-width (no 250 px sidebar offset). Page title + breadcrumb stay
  at the top of the content, as now.
- No horizontal scrolling at any width — page, table, card, or container.

---

## 3. Colour palette (from `public/img/logo.png`)

Define once as CSS custom properties in the new `public/css/theme.css`; everything else
references the tokens. Sample the exact hexes from the logo in Task 1.1 — these are the
starting values:

| Token | Value | Source / use |
|---|---|---|
| `--pnp-red` | `#C8102E` | shield red — primary buttons, active states, links |
| `--pnp-red-dark` | `#A31226` | darker shield panel — hover/pressed, gradient end |
| `--pnp-blue` | `#1D2A8C` | outline + ribbon text navy — header gradient, headings |
| `--pnp-blue-dark` | `#111A5C` | header gradient start, footer |
| `--pnp-yellow` | `#FFD400` | sun — small accents only (badges, focus ring, "required" star) |
| `--pnp-surface` | `#F5F6FA` | page background |
| `--pnp-card` | `#FFFFFF` | cards, tables, dropdown panels |
| `--pnp-text` | `#1F2937` | body text |
| `--pnp-muted` | `#6B7280` | helper text, breadcrumbs |
| `--pnp-border` | `#E3E6EE` | card/table borders |
| `--pnp-success` / `--pnp-danger` | `#1E8E3E` / `--pnp-red` | flash messages, status pills |

**Header gradient (MediQ formula with PNP colours):**
`radial-gradient(circle at 12% 0%, rgba(255,255,255,.18), transparent 30%),
 linear-gradient(100deg, var(--pnp-blue-dark) 0%, var(--pnp-blue) 55%, var(--pnp-red) 100%)`

Rules: red is the *action* colour, blue is the *structure* colour. Never use the template's
`#3b7ddd`, the old `#c6262f` / `#222222`, or the login's teal `rgb(11 132 156)` again.

---

## 4. Current shell (what gets replaced)

| File | Role today | Fate |
|---|---|---|
| `resources/views/backend/master/template.blade.php` | Layout: `.wrapper` → `@include sidebar` + `.main` → header/content/footer; loads `docs/css/modern.css` (Bootlab "Modern" 494 KB), `css/global.css`, `css/new_design.css` | Keep as the layout; drop the sidebar include; add `theme.css` after `new_design.css`; add 1280 px shell rules |
| `resources/views/backend/partial/sidebar.blade.php` | Brand logos (with `pnplogo()`/`pasaylogo()` status-toggle clicks), user card, nav (Dashboard, Registrations ▸4, Payment, Hit Verification, Users, Maintenance ▸4) | **Removed** from the layout; its links become `config/navigation.php`; the logo toggle moves into the header brand pill |
| `resources/views/backend/partial/header.blade.php` | Bootlab navbar: hamburger, dummy messages/notifications, settings dropdown | **Rewritten** as the MediQ console header |
| `resources/views/backend/partial/footer.blade.php` | Footer | Recolour only |
| `public/css/new_design.css` | 71 lines, entirely commented-out old red/black override | Delete (superseded by `theme.css`) |
| `public/css/global.css` | Login gradient (blue→teal) + misc | Recolour to palette |
| `public/backend/css/customlogin.css`, `public/css/custom.css` | Login/misc overrides | Recolour to palette |
| `public/docs/js/app.js`, `public/backend/js/app.js` | Bootlab bundle (sidebar toggle, splash screen) | Keep; sidebar toggle becomes a no-op once `#sidebar` is gone — verify no console errors |

---

## 5. Architecture decisions

1. **One theme file, token-driven.** `public/css/theme.css` (new) holds the `:root`
   tokens and every override of the Bootlab template (buttons, cards, tables, forms,
   DataTables, modals, alerts, breadcrumbs, pagination, badges). Loaded last so it wins
   without `!important` wars.
2. **Navigation is data, not markup.** `config/navigation.php` returns the menu tree
   (label, icon, href, children, `status`-gated variants). Both the desktop tab rail and
   the mobile burger render from it, so they can never drift apart.
3. **Header is one partial** (`backend/partial/header.blade.php`) rendering: brand pill,
   tab rail (`≥1280px`), burger (`<1280px`), right actions. No page-specific headers.
4. **Active state is computed**, not hard-coded: `request()->is(...)` against each item's
   `href` (and children) sets the active pill.
5. **Inline styles are migrated, not patched.** The heavy screens (`new_application`
   145 inline styles / 116 hex colours; `completed` / `completed_record` 172 each;
   `hitverification` 115) get their inline colours replaced with theme classes/tokens.
   Layout-only inline styles (widths, margins) may stay.
6. **No JS framework added.** Dropdowns/burger use the Bootstrap 4 collapse/dropdown
   already loaded (the template ships `bootstrap.bundle.js`). Alpine is *not* introduced.
7. **Printables untouched:** `printable_form`, `partial/certificate`, `partial/id`,
   `partial/id_layout` and any `@media print` block keep their current CSS verbatim.

---

## 6. Checklist

### Phase 0 — Baseline (before touching anything)
- [ ] Screenshot every screen in §7 at 1440 px and 375 px (login as `superadmin@gmail.com` / `P@ssw0rd`) into `docs/redesign/before/`
- [x] Note the 3 pre-existing 500s (`/dashboard`, `/rate`, `/applications`) — they are code bugs, not design; decide whether to fix `DashboardController` `$renewalCount` so the Dashboard can be verified
- [x] Create a branch: `design/mediq-header-v1`

### Phase 1 — Theme foundation
- [x] **1.1** Sample exact hexes from `public/img/logo.png`; finalise §3 tokens
- [x] **1.2** Create `public/css/theme.css` with `:root` tokens
- [x] **1.3** Override Bootlab template colours in `theme.css`: `.btn-primary/-secondary/-danger/-outline-*`, `.card`, `.card-header`, `.table`/`.table-striped`, `.form-control:focus` (blue ring), `.custom-select`, `.badge`, `.alert-*`, `.breadcrumb`, `.page-item.active`, `.nav-pills .active`, `.modal-header`, `.dropdown-item:hover/.active`, links
- [x] **1.4** Override DataTables skin (`jquery.dataTables.min.css`, `buttons.dataTables.min.css`): header row, sort arrows, paging buttons, search box, `dt-buttons`
- [x] **1.5** Splash screen (`.splash`, `.splash-icon`) → blue background, red spinner
- [x] **1.6** Add global shell rules from MediQ shim: `html,body{overflow-x:clip;max-width:100%}`, `resp-table` card-stacking `<1280px`, page-enter fade (reduced-motion safe)
- [x] **1.7** Load `theme.css` in `template.blade.php` **after** `new_design.css`; delete `new_design.css` and its `<link>`
- [x] **1.8** Recolour `global.css` login gradient + `customlogin.css` + `custom.css` to tokens; remove all `#3b7ddd`, `#c6262f`, `#222222`, `#3949ab`, teal/blue-gray leftovers (grep to confirm zero)

### Phase 2 — Navigation data
- [x] **2.1** Create `config/navigation.php` mirroring today's sidebar exactly:
  - Dashboard → `dashboard` (status 1) / `dashboard/record` (others) — icon `fa-gauge`
  - Registrations ▸ Add Applicant `new_application`, Applicant Other Detail `application/detail`, Printable Form `application/printable-form`, Printing Applications `application/completed` (status 1) / `application/completed/record`
  - Payment → `payment/process`
  - Hit Verification → `hit-verification`
  - Users → `user`
  - Maintenance ▸ Clearance Purpose `purpose`, Municipality `municipality`, Nationality `nationality`, Religion `religion`
- [x] **2.2** Add a tiny helper (`App\Support\Navigation::items()` / `::isActive($item)`) that resolves the `Auth::user()->status` variants and active state
- [x] **2.3** Verify every href still matches `routes/web.php` (no dead links introduced)

### Phase 3 — Header (replaces sidebar)
- [x] **3.1** Rewrite `backend/partial/header.blade.php` as the MediQ console header (sticky, gradient, white text)
- [x] **3.2** Brand pill: PNP logo + Pasay Police logo; keep the existing `pnplogo()` / `pasaylogo()` click handlers (they POST `/user/update_status_active/{1|0}` and reload — behaviour must not change)
- [x] **3.3** App-title pill ("Police Clearance" / "Pasay City") in the workspace-switcher pill style (no dropdown — single workspace)
- [x] **3.4** Desktop tab rail: one pill per top-level item; *Registrations* and *Maintenance* open a white dropdown panel (MediQ panel style) with their children
- [x] **3.5** Active pill computed from the current URL; parent pill lights up when a child is active
- [x] **3.6** Right actions: notifications bell (keep the existing dummy dropdown for now), account avatar (initials from `Auth::user()` first/last name, 42 px, 13 px radius, 2 px white border) → dropdown: user name + "Super Admin"/status pill, View Profile, Sign out (keeps the CSRF `logout-form`)
- [x] **3.7** Burger (`<1280px`): ☰ button replaces the rail; panel lists every item grouped with uppercase section titles, active item highlighted, closes on outside click and Esc
- [x] **3.8** Header must never wrap or overflow: rail scrolls horizontally *inside the header only* if the viewport is between 1280 and ~1400 px; below 1280 it is hidden
- [x] **3.9** Remove `@include('backend.partial.sidebar')` and the `.wrapper`/`.main` sidebar offset from `template.blade.php`; content is full width with 24 px gutters (16 px on phones)
- [x] **3.10** Delete `sidebar.blade.php` once nothing references it (`grep -r "partial.sidebar" resources/`)
- [x] **3.11** Confirm `docs/js/app.js` / `backend/js/app.js` throw no console errors with `#sidebar` gone (the Bootlab sidebar-toggle code)

### Phase 4 — Screen-by-screen recolour (see §7 for the list)
For every screen:
- [ ] Replace inline `style="color/background/border: #…"` and `<style>` colours with theme classes or tokens
- [ ] Section headers ("1. Transaction Details") → blue text, red left rule (MediQ card-header style)
- [ ] Primary actions red, secondary outline blue, destructive red-outline
- [ ] Tables use the DataTables skin from 1.4; add `resp-table` + `data-label` so rows stack into cards `<1280px`
- [ ] Status pills (PAID / ON-PROCESS / ACTIVE / HIT) mapped to success / yellow / blue / red tokens
- [ ] Check at 1440 px and 375 px: no horizontal scroll, header intact, burger works

### Phase 5 — Auth screens
- [x] `auth/login.blade.php` + `global.css#login` + `customlogin.css`: blue→red gradient backdrop, white card, red Sign-In button, PNP logo on top
- [ ] `auth/passwords/email`, `auth/passwords/reset`, `auth/register`, `auth/verify`, `auth/logindefault`: same card style (even if unused, they must not show the old blue)

### Phase 6 — Verification & handoff
- [x] Zero old colours: `grep -rniE "#3b7ddd|#c6262f|#222222|#3949ab|rgb\(11 132 156\)" resources public/css public/backend/css` returns nothing outside printables/vendor bundles
- [x] Zero `overflow-x: auto|scroll` added anywhere
- [x] Every link in the header hits a 200/302 (script: fetch each `config/navigation.php` href while logged in)
- [x] Console clean on every screen (no JS errors from the removed sidebar)
- [ ] Printables render unchanged: diff `printable_form`, `certificate`, `id`, `id_layout` output before/after (print preview screenshots)
- [ ] "After" screenshots at 1440 / 1024 / 375 px into `docs/redesign/after/`
- [ ] Update `README.md` with a short "Theme" section pointing at `theme.css` + `config/navigation.php`
- [ ] Commit on `design/mediq-header-v1`; push only (no MR/merge without approval)

---

## 7. Per-screen checklist (all of `resources/views/backend/` + auth)

Legend: **I** = inline `style=` count today, **H** = hard-coded hex colours today (both must
reach 0 for colour properties; layout-only inline styles may remain).

| # | Screen (blade) | Route | I | H | Recoloured | Header OK | No h-scroll | Notes |
|---|---|---|---|---|---|---|---|---|
| 1 | `master/template` | — | 1 | 0 | [ ] | [ ] | [ ] | layout; sidebar include removed |
| 2 | `partial/header` | — | 1 | 0 | [ ] | [ ] | [ ] | rewritten (Phase 3) |
| 3 | `partial/sidebar` | — | 2 | 0 | [ ] | — | — | **deleted** |
| 4 | `partial/footer` | — | 0 | 0 | [ ] | [ ] | [ ] | |
| 5 | `partial/flash-message` | — | 0 | 2 | [ ] | — | — | success/danger tokens |
| 6 | `pages/dashboard/dashboard` | `/dashboard` | 0 | 1 | [ ] | [ ] | [ ] | currently 500 (`$renewalCount`) — fix or verify via `/dashboard/record` |
| 7 | `pages/main/applicants` | `/dashboard/record` | 6 | 0 | [ ] | [ ] | [ ] | |
| 8 | `pages/application/new_application` | `/new_application` | 145 | 116 | [ ] | [ ] | [ ] | **heaviest** — sectioned form, step tabs (Applicant Information / Other Details / Printing / Renewal History), lookup panel, derogatory rows |
| 9 | `partial/new_application/picture` | (in 8) | 11 | 1 | [ ] | — | — | webcam capture panel |
| 10 | `partial/new_application/signature` | (in 8) | 1 | 0 | [ ] | — | — | Topaz pad panel |
| 11 | `partial/new_application/fingerprint_left` | (in 8) | 1 | 0 | [ ] | — | — | |
| 12 | `partial/new_application/fingerprint_right` | (in 8) | 1 | 0 | [ ] | — | — | |
| 13 | `pages/application/applicant_detail` | `/application/detail` | 3 | 1 | [ ] | [ ] | [ ] | |
| 14 | `pages/application/application` | `/application` | 2 | 3 | [ ] | [ ] | [ ] | route commented out in sidebar; still recolour |
| 15 | `pages/application/completed` | `/application/completed` | 172 | 50 | [ ] | [ ] | [ ] | heavy DataTable + modals |
| 16 | `pages/application/completed_record` | `/application/completed/record` | 172 | 50 | [ ] | [ ] | [ ] | twin of 15 — keep them identical |
| 17 | `pages/application/old_completed` | (legacy) | 190 | 2 | [ ] | [ ] | [ ] | confirm still routed; if dead, leave and note |
| 18 | `pages/application/printable_form` | `/application/printable-form` | 0 | 11 | **skip** | [ ] | [ ] | printable — chrome only (header), print CSS untouched |
| 19 | `pages/fingerprint/fingerprint` | fingerprint routes | 0 | 7 | [ ] | [ ] | [ ] | |
| 20 | `pages/payment/payment` | `/payment/process` | 25 | 0 | [ ] | [ ] | [ ] | |
| 21 | `pages/payment/addpayment` | `/payment/...` | 27 | 0 | [ ] | [ ] | [ ] | |
| 22 | `pages/main/hitverification` | `/hit-verification` | 115 | 1 | [ ] | [ ] | [ ] | heavy inline layout |
| 23 | `pages/system/users` | `/user` | 2 | 0 | [ ] | [ ] | [ ] | |
| 24 | `pages/maintenance/purpose` | `/purpose` | 2 | 0 | [ ] | [ ] | [ ] | |
| 25 | `pages/maintenance/municipality` | `/municipality` | 2 | 0 | [ ] | [ ] | [ ] | |
| 26 | `pages/maintenance/nationality` | `/nationality` | 2 | 0 | [ ] | [ ] | [ ] | |
| 27 | `pages/maintenance/religion` | `/religion` | 2 | 0 | [ ] | [ ] | [ ] | |
| 28 | `pages/tools/topaz_diagnostics` | `/topaz/diagnostics` | 2 | 4 | [ ] | [ ] | [ ] | |
| 29 | `partial/certificate` | print | 0 | 0 | **skip** | — | — | printable |
| 30 | `partial/id` | print | 0 | 0 | **skip** | — | — | printable |
| 31 | `partial/id_layout` | print | 48 | 18 | **skip** | — | — | printable |
| 32 | `auth/login` | `/login` | 1 | 3 | [ ] | — | [ ] | Phase 5 |
| 33 | `auth/logindefault` | — | 0 | 0 | [ ] | — | [ ] | |
| 34 | `auth/passwords/email` | `/password/reset` | 0 | 0 | [ ] | — | [ ] | |
| 35 | `auth/passwords/reset` | `/password/reset/{t}` | 0 | 0 | [ ] | — | [ ] | |
| 36 | `auth/register` | `/register` | 0 | 0 | [ ] | — | [ ] | |
| 37 | `auth/verify` | `/email/verify` | 0 | 0 | [ ] | — | [ ] | |
| 38 | `home`, `welcome`, `layouts/app` | stock scaffold | 0 | 3 | [ ] | — | — | confirm unused; recolour `welcome` `<style>` or delete |

---

## 8. Out of scope / do not touch

- `routes/web.php`, controllers, models, migrations, seeders (except optionally the
  one-line `$renewalCount` fix so the Dashboard can be verified — flag it separately)
- The `pnplogo()` / `pasaylogo()` status-toggle behaviour (only its placement changes)
- Print output of certificate / ID / printable form
- Vendor bundles: `docs/css/modern.css`, `bootstrap*.css`, DataTables CSS — override in
  `theme.css`, never edit them

---

## 9. Definition of done

- Sidebar gone on every screen; all 6 top-level items (+8 children) reachable from the header on desktop and from the burger below 1280 px
- Header gradient + pills match the MediQ pattern with the PNP palette
- No screen shows the template blue, the old red/black override, or the login teal
- No horizontal scrolling at 1440 / 1024 / 768 / 375 px on any screen in §7
- Printables unchanged (before/after diff)
- `docs/redesign/before/` and `/after/` screenshots committed for review

---

## 10. Implementation log (2026-09-21, branch `design/mediq-header-v1`)

**Done — UI/UX only. No route, controller, model, middleware, migration or seeder was touched. RBAC unchanged.**

| Area | Files |
|---|---|
| Theme foundation | `public/css/theme.css` (new, tokens + every template/DataTables/modal/form override); `public/css/new_design.css` deleted; `public/css/global.css` login gradient + old navbar/sidebar overrides removed |
| Navigation data | `config/navigation.php` (new) — link-for-link mirror of the old sidebar incl. the `status == 1` URL swap; `app/Support/Navigation.php` (new) — resolves hrefs + active state, initials/display name for the avatar. Presentation-only helper. |
| Header / layout | `backend/partial/header.blade.php` rewritten (brand pill with the original `pnplogo()`/`pasaylogo()` handlers, title pill, ≥1280 tab rail with dropdown groups, bell, avatar account menu, <1280 burger); `backend/partial/sidebar.blade.php` deleted; `backend/master/template.blade.php` — sidebar include removed, `theme.css` loaded last, DataTables column re-measure after font load |
| Screens recoloured | `new_application`, `applicant_detail`, `application`, `completed`, `completed_record`, `dashboard`, `fingerprint`, `partial/new_application/picture` — page-level `<style>` hexes mapped to tokens; fixed bottom search bar → header gradient; DataTables `scrollX` → `responsive` (no sideways scroll) |
| Auth | `auth/login.blade.php` + `backend/css/customlogin.css` — gradient panel, red Sign In, yellow focus/invalid |
| Untouched by design | `printable_form`, `partial/certificate`, `partial/id`, `partial/id_layout`, all in-page ID/clearance preview markup, `welcome`/`home` scaffolds |

**Fixes found on the way**
- `/img/pasay-police.png` never existed (broken in the old sidebar) → brand pill uses `backend/img/logos/pasay-logo.png`.
- Header dropdowns use `data-display="static"` (Popper mis-anchored them inside the sticky header).
- DataTables measured column widths before the web font loaded → `columns.adjust()` on `load` / `fonts.ready` / resize.

**Verified in browser** (superadmin): 1440 / 1280 / 1024 / 375 px — `scrollWidth === innerWidth` on new_application, completed, detail, hit-verification, payment, purpose, dashboard/record; rail shows ≥1280, burger <1280; all 12 nav hrefs 200 (`/dashboard` 500 is the pre-existing `$renewalCount` bug, untouched); no JS errors from the removed sidebar.

**Still open**
- Before/after screenshot folders not committed.
- Login carousel images still say "Quezon City" (content, not styling — swap the PNGs in `public/img/slide-*.png`).
- Header notification bell is a static placeholder (as before).
- Optional: `resp-table` card-stacking is available in `theme.css` but DataTables `responsive` already handles narrow widths, so no table was converted.
