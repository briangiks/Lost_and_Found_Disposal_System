@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="mx-auto max-w-5xl px-4 sm:px-6 py-6 space-y-6" x-data="{ period: '{{ $period }}' }">
    <div>
        <h1 class="text-2xl font-bold">Disposal reports</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Export the audit log for a given period for university administration records.
        </p>
    </div>

    <form method="GET" action="{{ route('reports.index') }}" class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-4 space-y-3">
        <div class="flex flex-wrap gap-2">
            @foreach (['today' => 'Today', 'this_week' => 'This week', 'this_month' => 'This month', 'this_quarter' => 'This quarter', 'this_year' => 'This year', 'custom' => 'Custom range'] as $value => $label)
                <label class="cursor-pointer">
                    <input type="radio" name="period" value="{{ $value }}" x-model="period" class="sr-only peer">
                    <span class="inline-block px-3 py-1.5 rounded-md text-sm border dark:border-slate-600 peer-checked:bg-brand-blue peer-checked:text-white peer-checked:border-brand-blue">{{ $label }}</span>
                </label>
            @endforeach
        </div>

        <div x-show="period === 'custom'" x-cloak class="flex gap-3 items-end">
            <div>
                <label class="text-xs text-slate-500 dark:text-slate-400">From</label>
                <input type="date" name="from" value="{{ request('from', $from->toDateString()) }}" class="block mt-1 px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
            </div>
            <div>
                <label class="text-xs text-slate-500 dark:text-slate-400">To</label>
                <input type="date" name="to" value="{{ request('to', $to->toDateString()) }}" class="block mt-1 px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
            </div>
        </div>

        <button type="submit" class="px-4 py-2 rounded-md bg-brand-blue text-white text-sm font-medium">Apply</button>
    </form>

    <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-5">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <div class="text-sm text-slate-500 dark:text-slate-400">{{ $periodLabel }} · {{ $from->toDateString() }} to {{ $to->toDateString() }}</div>
                <div class="text-2xl font-bold mt-1">{{ $totalDisposed }} items disposed across {{ $batchCount }} batch(es)</div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('reports.export-pdf', request()->query()) }}" class="px-3 py-2 rounded-md border dark:border-slate-600 text-sm inline-flex items-center gap-1.5">
                    <x-icon name="clipboard-list" class="w-4 h-4" /> Export PDF
                </a>
                <a href="{{ route('reports.export-excel', request()->query()) }}" class="px-3 py-2 rounded-md bg-brand-red text-white text-sm font-medium inline-flex items-center gap-1.5">
                    <x-icon name="arrow-up-right" class="w-4 h-4" /> Export Excel
                </a>
            </div>
        </div>
    </div>

    <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400">
                    <tr class="text-left">
                        <th class="px-4 py-2 font-medium">ID</th>
                        <th class="px-4 py-2 font-medium">Item</th>
                        <th class="px-4 py-2 font-medium">Category</th>
                        <th class="px-4 py-2 font-medium">Batch</th>
                        <th class="px-4 py-2 font-medium">Released</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $i)
                        <tr class="border-t dark:border-slate-700">
                            <td class="px-4 py-2 font-mono text-xs text-slate-500 dark:text-slate-400">{{ $i->reference }}</td>
                            <td class="px-4 py-2 font-medium">{{ $i->name }}</td>
                            <td class="px-4 py-2">{{ $i->category }}</td>
                            <td class="px-4 py-2 font-mono text-xs">{{ $i->disposalBatch?->reference }}</td>
                            <td class="px-4 py-2 text-slate-500 dark:text-slate-400">{{ $i->disposalBatch?->released_at?->toDateString() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">No items were disposed in this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
