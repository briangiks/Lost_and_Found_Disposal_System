<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

/**
 * Some free hosts (Render's free tier, for example) don't offer a native
 * cron/scheduler or background worker, so routes/console.php's
 * Schedule::command(...) never actually fires there. This gives the same
 * automation a trigger a free external cron-ping service (e.g.
 * cron-job.org, EasyCron) can hit instead — a plain HTTPS GET request once
 * a day, guarded by a secret token so nobody else can trigger it.
 *
 * Leave CRON_SECRET_TOKEN unset to disable both routes in this controller
 * entirely (they 403 on every request until a token is configured).
 */
class CronController extends Controller
{
    public function runDisposalCycle(Request $request)
    {
        $this->checkToken($request);

        Artisan::call('disposal:process-cycle');

        return response(Artisan::output(), 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Diagnostic-only: attempts to send one real test email and returns the
     * exact exception (class, message, first few trace lines) as plain text
     * if it fails — because Render's free tier gives you no interactive
     * shell to run `php artisan tinker` like you would locally, and the
     * friendly in-app error message deliberately hides this detail from
     * end users.
     *
     * GET /diagnostics/test-mail?token=...&to=you@example.com
     *
     * Remove this route (or just leave CRON_SECRET_TOKEN blank) once you're
     * done debugging — it's token-protected, but there's no reason to leave
     * a mail-sending diagnostic endpoint reachable long-term.
     */
    public function testMail(Request $request)
    {
        $this->checkToken($request);

        $to = $request->query('to');
        if (blank($to)) {
            return response("Add ?to=you@example.com to the URL so there's somewhere to send the test email.", 400);
        }

        try {
            Mail::raw(
                'This is a test email from the Strathmore Lost & Found system, sent to verify SMTP/Mailgun delivery from the live deployment.',
                fn ($message) => $message->to($to)->subject('Test email — Lost & Found diagnostics')
            );
        } catch (\Throwable $e) {
            $trace = collect(explode("\n", $e->getTraceAsString()))->take(8)->implode("\n");

            return response(
                "FAILED\n\n".
                'Exception: '.get_class($e)."\n".
                'Message: '.$e->getMessage()."\n\n".
                "Mailer config in use:\n".
                '  MAIL_MAILER = '.config('mail.default')."\n".
                '  MAIL_HOST   = '.config('mail.mailers.smtp.host')."\n".
                '  MAIL_PORT   = '.config('mail.mailers.smtp.port')."\n".
                "\nFirst few trace lines:\n".$trace,
                200
            )->header('Content-Type', 'text/plain');
        }

        return response("OK — test email sent to {$to}. Check its inbox (and spam folder).", 200)
            ->header('Content-Type', 'text/plain');
    }

    private function checkToken(Request $request): void
    {
        $expected = config('services.cron_secret');

        if (blank($expected) || ! hash_equals((string) $expected, (string) $request->query('token'))) {
            abort(403, 'Invalid or missing token.');
        }
    }
}