# Strathmore Lost & Found — Disposal System (Laravel)

A full rebuild of the Strathmore University Lost & Found Disposal System as a
real, working **Laravel 11** application — admin login, a filterable lost-items
registry, a four-stage quarterly disposal workflow, email logging/preview, and
an audit log of every disposal batch. No third-party AI-builder branding or
watermarks (Lovable, Claude, or otherwise) are present anywhere in this project.

## What's actually implemented (not just a UI mock)

- **Auth** — session-based admin login (`App\Http\Controllers\AuthController`), seeded demo account.
- **Items registry** — full CRUD (`App\Http\Controllers\ItemController`): log a new
  found item with a photo, edit it, mark it as collected by its owner, or delete it.
  The dashboard's search/category/color/location/markings/date filters all run
  real database queries.
- **Disposal workflow** (`App\Http\Controllers\DisposalController`) — a genuine
  4-stage, database-backed cycle:
  1. Review the batch of items that have reached the 90-day cut-off.
  2. Send the disposal notice to Strathmore Communications (this is logged as a
     real row in `sent_emails`, not just shown on screen).
  3. A real 7-day pickup-window countdown (`handover_due_at` on the batch),
     with a "Skip wait (demo)" button so you don't have to wait a week to
     demo/grade it.
  4. Confirm handover to the Community Outreach Club — this actually updates
     every remaining item's status to `donated`, closes the batch, logs the
     handover email, and opens the next quarterly cycle automatically.
- **Email preview** (`App\Http\Controllers\EmailController`) — shows the real,
  logged copy of an email once it's been sent for the current cycle, or a live
  draft preview before that.
- **Audit log** (`App\Http\Controllers\AuditController`) — every completed
  disposal batch with the full list of items that were handed over.

## Requirements

- PHP 8.2+
- Composer
- SQLite (bundled with PHP — zero extra setup) **or** MySQL/MariaDB

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate

# SQLite is the default — just create the file:
touch database/database.sqlite

php artisan migrate --seed
php artisan serve
```

Then open **http://127.0.0.1:8000** and sign in with:

- **Email:** `admin@strathmore.edu`
- **Password:** `Demo1234`

(Both are pre-filled on the login screen for convenience during grading/demo.)

### Using MySQL instead of SQLite

If you'd rather run this on XAMPP/Laragon/WAMP, open `.env`, comment out the
`DB_CONNECTION=sqlite` line, uncomment the `mysql` block underneath it, fill in
your database name/credentials, create that database, then run
`php artisan migrate --seed`.

### Uploaded item photos

Item photos are stored on the `public` disk. After `composer install`, run:

```bash
php artisan storage:link
```

so `storage/app/public/items/...` is reachable at `public/storage/items/...`.

## Project structure (the parts worth reading first)

```
app/Http/Controllers/   AuthController, DashboardController, ItemController,
                         DisposalController, EmailController, AuditController
app/Models/              User, Item, DisposalBatch, SentEmail
config/lostandfound.php  Business rules: 90-day disposal cutoff, 7-day pickup
                         window, office/communications/outreach email addresses
database/migrations/     items, disposal_batches, sent_emails, users
database/seeders/        Demo admin account + a realistic set of items across
                         every age band, plus 3 historical disposal batches
resources/views/         Blade templates (Tailwind via CDN + Alpine.js for the
                         theme toggle, countdown timer and accordion — no build
                         step required)
routes/web.php           All application routes
```

## Notes for your defense / report

- The business rule constants (90-day disposal window, 83-day early warning,
  7-day pickup window, office/communications/outreach email addresses) live in
  `config/lostandfound.php` and can be overridden per-environment via `.env` —
  this is the standard Laravel pattern for configurable business rules.
- `Item::getAgeBandAttribute()` / `getIsDueForDisposalAttribute()` compute an
  item's disposal eligibility **on the fly** from `date_lost`, so the dashboard
  and disposal workflow are always correct without a scheduled job re-stamping
  every row.
- Sending an email in this build logs a real row to `sent_emails` (visible on
  the Email Preview page) instead of connecting to a live SMTP server, which
  keeps the demo self-contained. Swapping in real `Illuminate\Mail` Mailable
  classes and a mail driver (e.g. Gmail SMTP, Mailgun) would be a natural next
  step — see the suggested improvements below.

## Suggested next improvements

1. **Role-based access** — separate `officer` vs `admin` abilities (e.g. only
   admins can delete items or release a disposal batch) using Laravel Policies.
2. **Real outgoing email** — turn the three email templates into proper
   `Illuminate\Mail\Mailable` classes and wire up an SMTP/Mailgun driver so
   Communications and Outreach genuinely receive the notices.
3. **Student self-service lookup** — a public, unauthenticated page where a
   student can search by category/color/location/date to check if their item
   has been found, without seeing the admin tooling.
4. **QR/barcode tagging** — generate a QR code per item at intake that links
   straight to its `items.show` page for fast desk lookup.
5. **Notifications** — a bell-icon notification center backed by Laravel's
   notification system for "item approaching disposal" and "batch ready for
   release" events, instead of only the dashboard banners.
6. **Export & reporting** — CSV/PDF export of the audit log per cycle for
   university records, and a simple chart (e.g. items logged vs. claimed vs.
   disposed per month) on the dashboard.
7. **Tests** — PHPUnit/Pest feature tests around the disposal workflow's state
   transitions, since that's the part most likely to be probed in a viva.
8. **Image moderation / size limits** — stricter upload validation (dimensions,
   virus scanning) if this is ever deployed for real student use.
