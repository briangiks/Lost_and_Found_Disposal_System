@extends('layouts.app')

@section('title', 'Disposal Workflow')

@php
    $step = 1;
    if ($batch->stage === 'review' && $composing) $step = 2;
    if ($batch->stage === 'pickup_window' && ! $pickupComplete) $step = 3;
    if ($batch->stage === 'pickup_window' && $pickupComplete) $step = 4;
@endphp

@section('content')
<div class="mx-auto max-w-6xl px-4 sm:px-6 py-6 space-y-6"
     x-data="disposalCountdown('{{ $batch->handover_due_at?->toIso8601String() }}')" x-init="start()">

    <div>
        <h1 class="text-2xl font-bold">Disposal workflow</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Every three months, all unclaimed items in custody are handed over to the Community Outreach Club.
            Follow the steps below to complete this quarter's cycle — batch <span class="font-mono">#{{ $batch->reference }}</span>.
        </p>
        @if ($automationEnabled)
            <p class="mt-2 inline-flex items-center gap-1.5 text-xs px-2 py-1 rounded-md bg-emerald-100 text-emerald-700 border border-emerald-300 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-700">
                <x-icon name="clock" class="w-3 h-3" /> Automation is ON — the scheduler will send notices and release this batch on its own if nobody clicks through manually.
            </p>
        @endif
    </div>

    <ol class="grid sm:grid-cols-2 md:grid-cols-4 gap-3">
        @php
            $steps = [
                1 => ['icon' => 'alert-triangle', 'title' => "Review this quarter's batch"],
                2 => ['icon' => 'mail', 'title' => 'Email Strathmore Comms'],
                3 => ['icon' => 'clock', 'title' => '1-week student pickup window'],
                4 => ['icon' => 'gift', 'title' => 'Release to Community Outreach'],
            ];
        @endphp
        @foreach ($steps as $n => $s)
            <li class="rounded-lg border p-4 bg-white dark:bg-slate-800 dark:border-slate-700 relative {{ $step === $n ? 'border-brand-red ring-2 ring-brand-red/20' : '' }}">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold {{ $n < $step ? 'bg-emerald-500 text-white' : ($n === $step ? 'bg-brand-red text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400') }}">
                        @if ($n < $step) <x-icon name="check-circle" class="w-4 h-4" /> @else {{ $n }} @endif
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 inline-flex items-center gap-1"><x-icon :name="$s['icon']" class="w-4 h-4" /> Step {{ $n }}</div>
                </div>
                <div class="mt-2 text-sm font-semibold">{{ $s['title'] }}</div>
            </li>
        @endforeach
    </ol>

    {{-- Step 1: Review batch --}}
    @if ($step === 1)
        <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-5">
            <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
                <div>
                    <h2 class="font-semibold">Step 1 · This quarter's disposal batch</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        All unclaimed items that have reached the 90-day mark — {{ $totalToDispose }} in total.
                    </p>
                </div>
                <div class="text-sm">Batch <span class="font-mono">#{{ $batch->reference }}</span> · {{ $totalToDispose }} items</div>
            </div>

            <div class="overflow-x-auto rounded-md border dark:border-slate-700">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400">
                        <tr class="text-left">
                            <th class="px-3 py-2 font-medium">Photo</th>
                            <th class="px-3 py-2 font-medium">ID</th>
                            <th class="px-3 py-2 font-medium">Item</th>
                            <th class="px-3 py-2 font-medium">Color</th>
                            <th class="px-3 py-2 font-medium">Lost</th>
                            <th class="px-3 py-2 font-medium">Age</th>
                            <th class="px-3 py-2 font-medium">Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dueItems as $i)
                            <tr class="border-t dark:border-slate-700">
                                <td class="px-3 py-2"><img src="{{ $i->imageUrl() }}" alt="" class="w-10 h-10 rounded object-cover border dark:border-slate-600"></td>
                                <td class="px-3 py-2 font-mono text-xs">{{ $i->reference }}</td>
                                <td class="px-3 py-2 font-medium">{{ $i->name }}</td>
                                <td class="px-3 py-2">{{ $i->color }}</td>
                                <td class="px-3 py-2 text-slate-500 dark:text-slate-400">{{ $i->date_lost->toDateString() }}</td>
                                <td class="px-3 py-2 text-red-600 font-semibold">{{ $i->age_in_days }}d</td>
                                <td class="px-3 py-2">{{ $i->location }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-3 py-6 text-center text-slate-500 dark:text-slate-400">No items have reached the 90-day mark yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mt-4 gap-2">
                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md border dark:border-slate-600 text-sm">Postpone batch</a>
                <a href="{{ route('disposal.index', ['compose' => 1]) }}" class="px-4 py-2 rounded-md bg-brand-red text-white text-sm font-medium inline-flex items-center gap-2">
                    <x-icon name="send" class="w-4 h-4" /> Notify &amp; prepare email
                </a>
            </div>
        </div>
    @endif

    {{-- Step 2: Compose / send email --}}
    @if ($step === 2)
        <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-5">
            <h2 class="font-semibold">Step 2 · Email Strathmore Communications</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">A single general notice covers the entire batch — no per-item detail.</p>
            <div class="rounded-md border dark:border-slate-700 bg-slate-50 dark:bg-slate-900/40 p-4 text-sm space-y-2">
                <div><span class="text-slate-500 dark:text-slate-400">To:</span> {{ $commsEmail }}</div>
                <div><span class="text-slate-500 dark:text-slate-400">CC:</span> {{ $officeEmail }}</div>
                <div><span class="text-slate-500 dark:text-slate-400">Subject:</span> Disposal Notice: Unclaimed Lost &amp; Found Items</div>
                <hr class="my-2 border-slate-200 dark:border-slate-700">
                @include('emails.templates.comms_notice', ['handoverDate' => now()->addDays((int) config('lostandfound.pickup_window_days', 7)), 'officeLocation' => $officeLocation])
            </div>
            <div class="flex justify-end mt-4 gap-2">
                <a href="{{ route('disposal.index') }}" class="px-3 py-2 rounded-md border dark:border-slate-600 text-sm">Back</a>
                <form method="POST" action="{{ route('disposal.send-notice', $batch) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-md bg-brand-red text-white text-sm font-medium inline-flex items-center gap-2">
                        <x-icon name="mail" class="w-4 h-4" /> Send email
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- Step 3: Pickup window countdown --}}
    @if ($step === 3)
        <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-5">
            <h2 class="font-semibold">Step 3 · One-week pickup window</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                Countdown started the moment the email was sent. Students have 7 days to collect their items
                from {{ $officeLocation }}.
            </p>
            <div class="rounded-lg border dark:border-slate-700 bg-brand-blue/5 p-6 text-center">
                <div class="text-xs uppercase tracking-wider text-brand-blue font-semibold">Time remaining</div>
                <div class="text-5xl font-bold font-mono text-brand-bluedark dark:text-white mt-2" x-text="formatted"></div>
                <div class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                    Handover scheduled for {{ $batch->handover_due_at?->translatedFormat('l, j F Y') }}
                </div>
            </div>
            <div class="grid sm:grid-cols-3 gap-3 mt-4 text-center">
                <div class="rounded-md border dark:border-slate-700 p-3">
                    <div class="text-xs text-slate-500 dark:text-slate-400">Items in batch</div>
                    <div class="text-xl font-bold mt-1">{{ $totalToDispose }}</div>
                </div>
                <div class="rounded-md border dark:border-slate-700 p-3">
                    <div class="text-xs text-slate-500 dark:text-slate-400">Collected so far</div>
                    <div class="text-xl font-bold mt-1">{{ $collectedCount }}</div>
                </div>
                <div class="rounded-md border dark:border-slate-700 p-3">
                    <div class="text-xs text-slate-500 dark:text-slate-400">Still unclaimed</div>
                    <div class="text-xl font-bold mt-1">{{ max(0, $totalToDispose - $collectedCount) }}</div>
                </div>
            </div>
            <div class="flex justify-end mt-4 gap-2">
                <form method="POST" action="{{ route('disposal.skip-wait', $batch) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-md bg-brand-red text-white text-sm font-medium">Skip wait (demo) →</button>
                </form>
            </div>
        </div>
    @endif

    {{-- Step 4: Release confirmation --}}
    @if ($step === 4)
        <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-5">
            <h2 class="font-semibold">Step 4 · Release to Community Outreach Club</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                The 1-week pickup window has elapsed. All remaining unclaimed items are ready for handover.
            </p>
            <div class="rounded-md border dark:border-slate-700 bg-slate-50 dark:bg-slate-900/40 p-4 text-sm space-y-2">
                <div><span class="text-slate-500 dark:text-slate-400">To:</span> {{ $outreachEmail }}</div>
                <div><span class="text-slate-500 dark:text-slate-400">Subject:</span> Disposed items — handover for donation</div>
                <hr class="my-2 border-slate-200 dark:border-slate-700">
                @include('emails.templates.outreach_handover', ['officeLocation' => $officeLocation, 'releasedCount' => max(0, $totalToDispose - $collectedCount)])
            </div>
            <div class="mt-4 rounded-md border dark:border-slate-700 p-4 bg-white dark:bg-slate-800">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    In total, <strong>{{ max(0, $totalToDispose - $collectedCount) }}</strong> items are being handed over as this quarter's
                    disposal batch. These are general disposed items — the full manifest is stored in the audit log.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mt-4">
                <div class="inline-flex items-center gap-2 text-emerald-700 dark:text-emerald-400 text-sm">
                    <x-icon name="check-circle" class="w-4 h-4" /> Confirming will archive this batch and start the next 3-month cycle.
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('audit.index') }}" class="px-3 py-2 rounded-md border dark:border-slate-600 text-sm">View audit log</a>
                    @can('release', $batch)
                        <form method="POST" action="{{ route('disposal.release', $batch) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-md bg-brand-red text-white text-sm font-medium inline-flex items-center gap-2">
                                <x-icon name="gift" class="w-4 h-4" /> Confirm handover
                            </button>
                        </form>
                    @else
                        <span class="px-4 py-2 rounded-md bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-sm font-medium inline-flex items-center gap-2" title="Only an admin account can confirm the handover.">
                            <x-icon name="lock" class="w-4 h-4" /> Admin approval required
                        </span>
                    @endcan
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function disposalCountdown(handoverIso) {
        return {
            remaining: 0,
            formatted: '0d 00h 00m 00s',
            start() {
                if (!handoverIso) return;
                const target = new Date(handoverIso).getTime();
                const tick = () => {
                    const now = Date.now();
                    this.remaining = Math.max(0, Math.floor((target - now) / 1000));
                    const d = Math.floor(this.remaining / 86400);
                    const h = String(Math.floor((this.remaining % 86400) / 3600)).padStart(2, '0');
                    const m = String(Math.floor((this.remaining % 3600) / 60)).padStart(2, '0');
                    const s = String(Math.floor(this.remaining % 60)).padStart(2, '0');
                    this.formatted = `${d}d ${h}h ${m}m ${s}s`;
                };
                tick();
                setInterval(tick, 1000);
            },
        };
    }
</script>
@endsection
