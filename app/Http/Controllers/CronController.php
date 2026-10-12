<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/**
 * Some free hosts (Render's free tier, for example) don't offer a native
 * cron/scheduler or background worker, so routes/console.php's
 * Schedule::command(...) never actually fires there. This gives the same
 * automation a trigger a free external cron-ping service (e.g.
 * cron-job.org, EasyCron) can hit instead — a plain HTTPS GET request once
 * a day, guarded by a secret token so nobody else can trigger it.
 *
 * Leave CRON_SECRET_TOKEN unset to disable this route entirely (it 403s on
 * every request until a token is configured).
 */
class CronController extends Controller
{
    public function runDisposalCycle(Request $request)
    {
        $expected = config('services.cron_secret');

        if (blank($expected) || ! hash_equals((string) $expected, (string) $request->query('token'))) {
            abort(403, 'Invalid or missing cron token.');
        }

        Artisan::call('disposal:process-cycle');

        return response(Artisan::output(), 200)->header('Content-Type', 'text/plain');
    }
}