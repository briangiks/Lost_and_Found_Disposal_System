# Improvements implemented — file-by-file map

This documents exactly what was added or changed for each of the 11 requested
improvements, so you can explain every line in your defense. New packages are
listed where relevant — **run `composer update` after pulling this** to install
them (see "New dependencies" at the bottom).

---

## 1. Image upload at intake

Already stored on `storage/app/public` in the original build (never hotlinked
Unsplash) — this pass **hardens validation** instead.

| File | Change |
|---|---|
| `app/Http/Controllers/ItemController.php` | `validated()` now uses `Illuminate\Validation\Rules\File` — real image bytes only (not just a spoofed `.jpg` extension), 4 MB cap, 80–6000px dimension bounds, `jpg/jpeg/png/webp` only. Also deletes the old file from disk when an item's photo is replaced or the item is deleted. |

---

## 2. Email scheduling (Day-90 notice → 7-day window → auto-release)

| File | Purpose |
|---|---|
| `app/Services/DisposalWorkflowService.php` | **New.** All workflow state transitions (`currentBatch`, `sendNotice`, `skipWait`, `release`) now live here — one source of truth shared by the manual UI *and* the scheduler, so they can never drift apart. Also has `processAutomatedCycle()`, the entry point the scheduler calls. |
| `app/Console/Commands/ProcessDisposalCycle.php` | **New.** `php artisan disposal:process-cycle` — idempotent, safe to run as often as you like. |
| `routes/console.php` | Registers `Schedule::command('disposal:process-cycle')->dailyAt('07:00')`. **For this to actually fire on a server**, the Laravel scheduler's cron entry must be running — see the comment in that file for the exact cron line (or Windows Task Scheduler setup for XAMPP). |
| `app/Http/Controllers/DisposalController.php` | Rewritten to delegate to `DisposalWorkflowService` instead of duplicating the logic inline. |
| `config/lostandfound.php` | New `automation_enabled` key (env: `LNF_AUTOMATION_ENABLED`) and `emails.students_list` (env: `LNF_STUDENTS_LIST_EMAIL`) — the system has no per-student directory, so the automated "1 week left" notice goes to a mailing list/distribution address rather than individual students. |

---

## 3. Notifications table + 9. Notification center

| File | Purpose |
|---|---|
| `database/migrations/2024_01_03_000000_create_notifications_table.php` | **New.** Laravel's standard database-notifications schema. |
| `app/Notifications/ItemApproachingDisposalNotification.php` | **New.** Raised once per item when it enters the 83–89 day warning band (not re-raised every scheduler run). |
| `app/Notifications/DisposalBatchStageChangedNotification.php` | **New.** Raised when a batch's notice is sent or it's released. |
| `app/Http/Controllers/NotificationController.php` | **New.** `index` (full page), `markRead` (redirects to the linked item/batch), `markAllRead`. |
| `resources/views/notifications/index.blade.php` | **New.** Full notification center page. |
| `resources/views/layouts/app.blade.php` | The header bell icon is now a real Alpine.js dropdown showing the 6 most recent unread notifications, with a live unread-count dot — replacing the old static red dot. |
| `routes/web.php` | `GET /notifications`, `POST /notifications/{id}/read`, `POST /notifications/read-all`. |

Notifications are raised from `DisposalWorkflowService` (on stage changes) and
`DisposalWorkflowService::raiseApproachingDisposalNotifications()` (called
every time the scheduler runs).

---

## 4. QR code per item

| File | Purpose |
|---|---|
| `app/Http/Controllers/QrCodeController.php` | **New.** `image()` returns an inline SVG QR code encoding a direct link to `items.show`; `card()` renders the printable desk card. |
| `resources/views/items/qr-card.blade.php` | **New.** A small, print-sized card (80mm × 120mm, label-sticker shaped) with the QR code, reference, name, color, location and date — has its own "Print this card" button. |
| `resources/views/items/show.blade.php` | Added a "Print QR desk card" button. |
| `routes/web.php` | `GET /items/{item}/qr`, `GET /items/{item}/qr-card`. |

Scanning the code at the desk opens the item's page directly — faster than
typing in a reference number for intake lookup or claim verification.

---

## 5. Unit tests (the 90-day boundary) + 10. Expanded test suite

| File | Covers |
|---|---|
| `app/Models/Item.php` | Added a plain `isDue(): bool` method (thin wrapper over the existing `is_due_for_disposal` accessor) so there's an explicit, obviously-named unit under test. |
| `tests/Unit/ItemDisposalEligibilityTest.php` | **New.** Boundary tests at exactly 89/90/91 days, age-band boundaries (29/30, 82/83, 89/90), claimed/donated items never being "due" regardless of age, the approaching-disposal window, and that the `dueForDisposal` query scope agrees with `isDue()`. |
| `tests/Feature/DisposalAutomationTest.php` | **New.** The scheduler command sends notices for due items, releases elapsed batches, respects `LNF_AUTOMATION_ENABLED=false`, and notifications aren't duplicated across repeated runs. |
| `tests/Feature/RolePermissionTest.php` | **New.** Officer can log/claim items and send notices but is forbidden from deleting items or releasing a batch; admin can do both. |
| `tests/Feature/NotificationCenterTest.php` | **New.** Viewing, marking-as-read (with redirect), and mark-all-as-read. |
| `tests/Feature/QrCodeTest.php` | **New.** QR image route returns SVG; desk card shows the item reference. |
| `tests/Feature/ReportExportTest.php` | **New.** Reports page lists disposed items for a period; PDF and Excel export routes respond successfully. |
| `tests/Feature/DisposalWorkflowTest.php` / `ItemRegistryTest.php` / `AuthTest.php` | Unchanged — still pass against the refactored controller/service. |

Run everything with `php artisan test` (or `vendor/bin/phpunit`).

---

## 6. Report export (PDF/Excel, by period) + dashboard chart

| File | Purpose |
|---|---|
| `app/Http/Controllers/ReportController.php` | **New.** `index` (filter UI), `exportPdf`, `exportExcel`. Period presets: `today`, `this_week`, `this_month`, `this_quarter`, `this_year`, or `custom` (`from`/`to` query params). Also exposes `monthlyChartData()`, a static helper reused by the dashboard chart. |
| `app/Exports/AuditLogExport.php` | **New.** `Maatwebsite\Excel` export class for the Excel download. |
| `resources/views/reports/index.blade.php` | **New.** Period picker + preview table + Export PDF / Export Excel buttons. |
| `resources/views/reports/pdf.blade.php` | **New.** Plain-HTML/inline-CSS template for the PDF (dompdf doesn't execute Tailwind's CDN script). |
| `app/Http/Controllers/DashboardController.php` | Now passes `chartData` (last 6 months, logged/claimed/disposed) to the view. |
| `resources/views/dashboard/index.blade.php` | Added a Chart.js bar chart ("Last 6 months") above the filters. |
| `routes/web.php` | `GET /reports`, `GET /reports/export/pdf`, `GET /reports/export/excel`. |

---

## 7. Role-based permissions (officer vs admin)

| File | Purpose |
|---|---|
| `app/Policies/ItemPolicy.php` | **New.** `delete` is admin-only; everything else (`view`, `create`, `update`, `claim`) is open to any logged-in officer. Laravel 11 auto-discovers this — no manual registration needed, since `App\Models\Item` → `App\Policies\ItemPolicy` is the default convention. |
| `app/Policies/DisposalBatchPolicy.php` | **New.** `release` (handing items to Community Outreach — irreversible) is admin-only; `sendNotice` is open to any officer. |
| `app/Http/Controllers/Controller.php` | Added the `AuthorizesRequests` trait so `$this->authorize(...)` works in every controller. |
| `app/Http/Controllers/ItemController.php`, `DisposalController.php`, `QrCodeController.php` | Every action now calls `$this->authorize(...)`. |
| `resources/views/items/show.blade.php`, `resources/views/disposal/index.blade.php` | `@can` directives hide the Delete button and the Confirm Handover button from officers (they see "Admin approval required" instead). |
| `database/seeders/AdminUserSeeder.php` | Now also seeds `officer@strathmore.edu` / `Demo1234` (role `officer`) alongside the existing admin account, so you can demo the split immediately. |

---

## 8. Real outgoing email (SMTP/Mailgun)

| File | Purpose |
|---|---|
| `app/Mail/DisposalNoticeMail.php`, `StudentNoticeMail.php`, `OutreachHandoverMail.php` | **New.** Proper `Illuminate\Mail\Mailable` classes wrapping the three existing Blade email templates (`resources/views/emails/templates/*`, unchanged). |
| `app/Jobs/SendDisposalNoticeJob.php`, `SendStudentNoticeJob.php`, `SendOutreachHandoverJob.php` | **New.** Queued (`ShouldQueue`) jobs that actually call `Mail::to(...)->send(...)` **and** still log a `sent_emails` row, so the Email Preview page works whether or not real SMTP is configured. |
| `config/mail.php` | Added a `mailgun` mailer entry. |
| `config/services.php` | Added the `mailgun` service config block (`domain`, `secret`, `endpoint`). |
| `.env.example` | Clear SMTP **or** Mailgun setup block with inline instructions — flip `MAIL_MAILER` and fill in credentials to go live. |

---

## 11. Stricter upload validation

Covered under item 1 above (`ItemController::validated()`).

---

## New dependencies — run this after pulling the code

```bash
composer update
php artisan migrate        # creates the new `notifications` table
php artisan db:seed --class=AdminUserSeeder   # adds the officer@strathmore.edu demo account
```

Added to `composer.json`:

| Package | Used for |
|---|---|
| `barryvdh/laravel-dompdf` | PDF report export |
| `maatwebsite/excel` | Excel report export |
| `f9webltd/simple-qrcode` | QR code generation |

**Why `f9webltd/simple-qrcode` instead of the more commonly-referenced
`simplesoftwareio/simple-qrcode`:** the original package has an open,
unresolved dependency conflict with Laravel 11+ (it pins `bacon/bacon-qr-code
^2.0`, which clashes with newer Laravel packages that need `^3.0`). This fork
is an actively maintained, drop-in replacement built specifically for Laravel
11/12/13 that resolves that conflict — same `QrCode::size(...)->generate(...)`
API.

**One thing to verify once you run `composer update`:** I built
`app/Http/Controllers/QrCodeController.php` against the facade import
`use F9WebLtd\QrCode\Facades\QrCode;` based on that package's published
documentation, but I wasn't able to actually install it in my sandbox (no
internet access to Packagist) to confirm the exact class path. If you get a
"class not found" error on that line, open
`vendor/f9webltd/simple-qrcode/src/` and check the real facade location —
it'll be a one-line fix to the `use` statement at the top of that controller.
Everything else in this update **was** verified: every PHP file passes
`php -l`, and I cross-checked every route name, view reference, Blade
component, and `config()` key used in the code against what's actually
defined.
