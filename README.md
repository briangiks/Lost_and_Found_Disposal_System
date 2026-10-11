# Strathmore Lost & Found — Disposal System (Laravel)

A full rebuild of the Strathmore University Lost & Found Disposal System as a
real, working **Laravel 11** application — admin login, a filterable lost-items
registry, a four-stage quarterly disposal workflow, email logging/preview, and
an audit log of every disposal batch. No third-party AI-builder branding or
watermarks (Lovable, Claude, or otherwise) are present anywhere in this project.

## What's actually implemented (not just a UI mock)

- **Auth** — session-based login, two seeded demo accounts: `admin@strathmore.edu`
  (full access) and `officer@strathmore.edu` (desk staff — see Roles below).
- **Items registry** — full CRUD: log a new found item with a photo, edit it,
  mark it as collected by its owner, or delete it. Photo uploads are validated
  as real image bytes (not just a spoofed extension), capped at 4 MB, with
  sane dimension bounds. The dashboard's search/category/color/location/
  markings/date filters all run real database queries, plus a 6-month
  logged/claimed/disposed chart.
- **Disposal workflow** — a genuine 4-stage, database-backed cycle (review →
  notice → 7-day pickup window → release), either clicked through manually by
  an officer **or fully automated**: `php artisan disposal:process-cycle`
  (scheduled daily in `routes/console.php`) sends the Day-90 notice and
  releases any batch whose pickup window has elapsed with zero human
  interaction. Both paths share one implementation —
  `app/Services/DisposalWorkflowService.php`.
- **Real email** — three `Mailable` classes + queued jobs actually send via
  SMTP/Mailgun (configure in `.env`) and log every send to `sent_emails` for
  the Email Preview page, whether or not real delivery is configured.
- **Notifications** — a real notification center (bell icon dropdown +
  `/notifications`): officers are notified when an item enters the 83–89 day
  warning band, and when a disposal batch's stage changes.
- **QR codes** — every item has a scannable QR code and a printable desk card
  (`/items/{id}/qr-card`) linking straight to its detail page, for fast
  intake/claim lookup.
- **Reports** — `/reports` exports the audit log as PDF or Excel for a
  selected period (today / week / month / quarter / year / custom range).
- **Roles** — `admin` vs `officer` via Laravel Policies: both can log, edit
  and claim items and send disposal notices; only `admin` can delete an item
  or confirm a batch handover to Community Outreach.
- **Audit log** — every completed disposal batch with the full list of items
  that were handed over.

See **`IMPROVEMENTS.md`** for an exact file-by-file map of this update, and
which new Composer packages it needs.

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

- **Admin:** `admin@strathmore.edu` / `Demo1234` — full access, including
  deleting items and confirming a disposal batch handover.
- **Officer:** `officer@strathmore.edu` / `Demo1234` — day-to-day desk staff:
  can log/edit/claim items and send disposal notices, but the Delete button
  and the handover confirmation are hidden (and blocked server-side via
  `app/Policies/`).

(The admin email/password are pre-filled on the login screen for convenience
during grading/demo — switch to the officer account to show the permission
split.)

### Running the automated disposal cycle

The whole workflow can also run itself, with no officer clicking through the
stepper:

```bash
php artisan disposal:process-cycle
```

This is safe to run as often as you like — it only acts on a batch that's
actually ready for that step (sends the Day-90 notice once, releases a batch
once its 7-day window has elapsed). In production, this needs to run on a
schedule: either add the Laravel scheduler's cron entry
(`* * * * * php artisan schedule:run`) on Linux, or use Windows Task Scheduler
to run the same command once a minute on XAMPP. See the comment in
`routes/console.php` for the exact setup.

To actually send email for real (rather than just logging it to
`sent_emails`), set `MAIL_MAILER` and the SMTP/Mailgun credentials in `.env`
— see the commented block there. Sending happens through queued jobs
(`app/Jobs/`), so for production also set `QUEUE_CONNECTION=database` and run
`php artisan queue:work` (the demo default, `QUEUE_CONNECTION=sync`, runs
jobs immediately with zero extra setup).

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

See the chat response that shipped alongside this update for a full
live-readiness assessment of everything implemented so far. Beyond that,
worth considering next:

1. **Student self-service lookup** — a public, unauthenticated page where a
   student can search by category/color/location/date to check if their item
   has been found, without seeing the admin tooling.
2. **Email verification / 2FA for admin accounts** — since admin can delete
   records and release university property to a third party.
3. **Soft deletes + an "undo"** on item deletion, since that action is
   currently permanent.
4. **Rate limiting** on login and on the item-creation form, to blunt brute
   force and spam.
5. **Audit trail on edits** — who changed what on an item record, not just
   its final state (useful if an item is ever disputed).
6. **Image moderation** — virus/malware scanning on uploads, and perhaps a
   thumbnail-generation step (e.g. via Intervention Image) so large photos
   don't bloat page loads.
7. **A real `queue:work` supervisor** (e.g. Supervisor on Linux, or a Windows
   service wrapper) so queued emails keep flowing without someone leaving a
   terminal window open.
8. **Database backups** — scheduled `mysqldump`/`sqlite` backups before each
   quarterly disposal run, given that run is destructive to `active` item
   status.
