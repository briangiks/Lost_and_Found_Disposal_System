<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class CronController extends Controller
{
    public function runDisposalCycle(Request $request)
    {
        $this->checkToken($request);

        Artisan::call('disposal:process-cycle');

        return response(Artisan::output(), 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Processes any emails currently waiting in the queue (QUEUE_CONNECTION
     * = database), then stops — there's no free worker process to run it in
     * continuously. Call this every minute or two via a free cron-ping
     * service so whatever got queued a moment ago actually goes out shortly
     * after, without the admin's click having to wait on a mail server.
     *
     * GET /cron/run-queue?token=...
     */
    public function runQueue(Request $request)
    {
        $this->checkToken($request);

        Artisan::call('queue:work', [
            '--stop-when-empty' => true,
            '--tries' => 3,
            '--timeout' => 60,
        ]);

        return response(Artisan::output(), 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Diagnostic-only: attempts to send one real test email and returns the
     * exact exception if it fails. GET /diagnostics/test-mail?token=...&to=you@example.com
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