@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="mx-auto max-w-2xl px-4 sm:px-6 py-6 space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Disposal policy settings</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Change how long an unclaimed item stays in Lost &amp; Found before it's due for disposal
            to the Community Outreach Club. Takes effect immediately across the whole system —
            dashboard, disposal workflow, notifications and reports — no redeploy needed.
        </p>
    </div>

    <form method="POST" action="{{ route('settings.update') }}" class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-6 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="text-sm font-semibold">Disposal window (days)</label>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                An unclaimed item becomes due for disposal once it has been in custody this many days.
                Currently <strong>{{ $disposalAgeDays }}</strong> days.
            </p>
            <input type="number" name="disposal_age_days" min="1" max="3650" value="{{ old('disposal_age_days', $disposalAgeDays) }}" required
                   class="mt-2 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
        </div>

        <div>
            <label class="text-sm font-semibold">Early-warning period (days)</label>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                How many days before the disposal window closes the dashboard starts showing the
                "approaching disposal" banner and notifying officers. Must be less than the disposal
                window above. Currently <strong>{{ $earlyWarningDays }}</strong> days.
            </p>
            <input type="number" name="early_warning_days" min="0" value="{{ old('early_warning_days', $earlyWarningDays) }}" required
                   class="mt-2 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
        </div>

        <div>
            <label class="text-sm font-semibold">Student pickup window (days)</label>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Once the disposal notice is sent to Communications and students, how many days they
                have to collect their item before it's released to Community Outreach.
                Currently <strong>{{ $pickupWindowDays }}</strong> days.
            </p>
            <input type="number" name="pickup_window_days" min="1" max="60" value="{{ old('pickup_window_days', $pickupWindowDays) }}" required
                   class="mt-2 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
        </div>

        <div class="rounded-md border border-amber-300 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-700 px-4 py-3 text-xs text-amber-800 dark:text-amber-300 flex items-start gap-2">
            <x-icon name="alert-triangle" class="w-4 h-4 shrink-0 mt-0.5" />
            Changing the disposal window changes which items count as "due" immediately — some items
            may instantly move in or out of the current quarter's disposal batch.
        </div>

        <div class="flex justify-end pt-2 border-t dark:border-slate-700">
            <button type="submit" class="px-4 py-2 rounded-md bg-brand-red text-white text-sm font-medium">Save settings</button>
        </div>
    </form>
</div>
@endsection