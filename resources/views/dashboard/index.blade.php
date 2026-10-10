@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 py-6 space-y-6">

    @if ($approachingCount > 0)
        <div class="rounded-lg border border-amber-300 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-700 p-4 flex flex-col sm:flex-row sm:items-start gap-3">
            <x-icon name="clock" class="w-5 h-5 text-amber-700 dark:text-amber-400 shrink-0 mt-0.5" />
            <div class="flex-1">
                <div class="font-semibold text-amber-800 dark:text-amber-300">
                    One week to the disposal cut-off · {{ $approachingCount }} item{{ $approachingCount > 1 ? 's' : '' }} approaching
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-300">
                    Please email Strathmore Communications to remind students to collect their items from
                    the Lost &amp; Found office within the next 7 days before the quarterly disposal.
                </p>
            </div>
            <a href="{{ route('disposal.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-md bg-brand-blue text-white text-sm font-medium hover:opacity-95 shrink-0">
                <x-icon name="send" class="w-4 h-4" /> Email Communications
            </a>
        </div>
    @endif

    @if ($dueCount > 0)
        <div class="rounded-lg border border-red-300 bg-red-50 dark:bg-red-900/20 dark:border-red-700 p-4 flex flex-col sm:flex-row sm:items-start gap-3">
            <x-icon name="alert-triangle" class="w-5 h-5 text-red-700 dark:text-red-400 shrink-0 mt-0.5" />
            <div class="flex-1">
                <div class="font-semibold text-red-700 dark:text-red-400">
                    Quarterly disposal ready · this quarter's unclaimed items are due for handover
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-300">
                    Every three months all unclaimed items in custody are handed over to the Community
                    Service (Outreach Club). Start the disposal workflow below.
                </p>
            </div>
            <a href="{{ route('disposal.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-md bg-brand-red text-white text-sm font-medium hover:opacity-95 shrink-0">
                <x-icon name="gift" class="w-4 h-4" /> Start disposal
            </a>
        </div>
    @endif

    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold">Lost items registry</h1>
        <a href="{{ route('items.create') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-md bg-brand-red text-white text-sm font-medium hover:opacity-95">
            <x-icon name="plus" class="w-4 h-4" /> Log new item
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-5">
            <div class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Total items in custody</div>
            <div class="text-3xl font-bold mt-2 text-brand-blue">{{ $totalInCustody }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Unclaimed items currently held this quarter.</div>
        </div>
        <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-5">
            <div class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Total items to be disposed</div>
            <div class="text-3xl font-bold mt-2 text-red-600">{{ $totalToDispose }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Items that have reached or passed the 90-day window.</div>
        </div>
    </div>

    <form method="GET" action="{{ route('dashboard') }}" class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-4 space-y-3">
        <div class="flex items-center gap-2 text-sm font-medium"><x-icon name="filter" class="w-4 h-4" /> Filters</div>
        <div class="grid md:grid-cols-12 gap-3">
            <div class="md:col-span-4 relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><x-icon name="search" class="w-4 h-4" /></span>
                <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Search item, ID, keyword…"
                       class="w-full pl-9 pr-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/40">
            </div>
            <select name="category" class="md:col-span-2 px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
                @foreach ($categories as $c)
                    <option value="{{ $c }}" @selected($filters['category'] === $c)>{{ $c === 'All' ? 'All items' : $c }}</option>
                @endforeach
            </select>
            <select name="color" class="md:col-span-2 px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
                @foreach ($colorOptions as $c)
                    <option value="{{ $c }}" @selected($filters['color'] === $c)>{{ $c === 'All' ? 'All colors' : $c }}</option>
                @endforeach
            </select>
            <select name="location" class="md:col-span-2 px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
                @foreach ($locationOptions as $l)
                    <option value="{{ $l }}" @selected($filters['location'] === $l)>{{ $l === 'All' ? 'All locations' : $l }}</option>
                @endforeach
            </select>
            <input type="date" name="date_lost" value="{{ $filters['date_lost'] }}"
                   class="md:col-span-2 px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
            <input type="text" name="markings" value="{{ $filters['markings'] }}" placeholder="Markings contain…"
                   class="md:col-span-10 px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 px-3 py-2 rounded-md bg-brand-blue text-white text-sm font-medium">Apply</button>
                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md border text-sm">Reset</a>
            </div>
        </div>
    </form>

    <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b dark:border-slate-700">
            <h2 class="font-semibold">Lost items registry</h2>
            <span class="text-xs text-slate-500 dark:text-slate-400">Showing {{ $items->count() }} of {{ $totalCount }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400">
                    <tr class="text-left">
                        <th class="px-4 py-3 font-medium">Photo</th>
                        <th class="px-4 py-3 font-medium">ID</th>
                        <th class="px-4 py-3 font-medium">Item</th>
                        <th class="px-4 py-3 font-medium">Color</th>
                        <th class="px-4 py-3 font-medium">Date lost</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Location</th>
                        <th class="px-4 py-3 font-medium">Markings</th>
                        <th class="px-4 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr class="border-t dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/40">
                            <td class="px-4 py-3">
                                <img src="{{ $item->imageUrl() }}" alt="{{ $item->name }}" class="w-12 h-12 rounded-md object-cover border dark:border-slate-600">
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-500 dark:text-slate-400">{{ $item->reference }}</td>
                            <td class="px-4 py-3 font-medium">{{ $item->name }}</td>
                            <td class="px-4 py-3">{{ $item->color }}</td>
                            <td class="px-4 py-3 text-slate-500 dark:text-slate-400">{{ $item->date_lost->toDateString() }}</td>
                            <td class="px-4 py-3">
                                @include('dashboard.partials.age-chip', ['band' => $item->age_band, 'age' => $item->age_in_days])
                            </td>
                            <td class="px-4 py-3">{{ $item->location }}</td>
                            <td class="px-4 py-3 text-slate-500 dark:text-slate-400 max-w-[220px] truncate" title="{{ $item->markings }}">{{ $item->markings }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('items.show', $item) }}" class="inline-flex items-center gap-1 text-brand-blue hover:underline text-xs font-medium">
                                    View <x-icon name="arrow-up-right" class="w-3 h-3" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">No items match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
