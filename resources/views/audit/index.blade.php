@extends('layouts.app')

@section('title', 'Audit Log')

@section('content')
<div class="mx-auto max-w-5xl px-4 sm:px-6 py-6 space-y-6" x-data="{ open: {{ $batches->first()?->id ?? 'null' }} }">
    <div>
        <h1 class="text-2xl font-bold">Audit log · Disposal handovers</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Every cycle of items released to the Community Outreach Club, grouped by the period they covered.
            Click a batch to see the full list of items that were handed over.
        </p>
    </div>

    <div class="space-y-3">
        @forelse ($batches as $b)
            <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 overflow-hidden">
                <button @click="open = (open === {{ $b->id }} ? null : {{ $b->id }})"
                        class="w-full flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-slate-700/40 text-left gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <span x-show="open === {{ $b->id }}"><x-icon name="chevron-down" class="w-4 h-4 text-slate-400 shrink-0" /></span>
                        <span x-show="open !== {{ $b->id }}"><x-icon name="chevron-right" class="w-4 h-4 text-slate-400 shrink-0" /></span>
                        <div class="min-w-0">
                            <div class="font-semibold truncate">{{ $b->cycle_label }} items — released to Community Outreach</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 flex flex-wrap gap-x-4 gap-y-1 mt-1">
                                <span class="inline-flex items-center gap-1"><x-icon name="calendar" class="w-3 h-3" /> {{ $b->released_at?->toDateString() }}</span>
                                <span class="inline-flex items-center gap-1"><x-icon name="user" class="w-3 h-3" /> {{ $b->officer?->email ?? 'admin@strathmore.edu' }}</span>
                                <span class="inline-flex items-center gap-1"><x-icon name="package" class="w-3 h-3" /> {{ $b->items->count() }} items</span>
                                <span class="font-mono">Batch #{{ $b->reference }}</span>
                            </div>
                        </div>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-md bg-emerald-100 text-emerald-700 border border-emerald-300 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-700 shrink-0">
                        Handed over
                    </span>
                </button>

                <div x-show="open === {{ $b->id }}" x-cloak class="border-t dark:border-slate-700 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400">
                            <tr class="text-left">
                                <th class="px-4 py-2 font-medium">ID</th>
                                <th class="px-4 py-2 font-medium">Item</th>
                                <th class="px-4 py-2 font-medium">Color</th>
                                <th class="px-4 py-2 font-medium">Category</th>
                                <th class="px-4 py-2 font-medium">Date lost</th>
                                <th class="px-4 py-2 font-medium">Location</th>
                                <th class="px-4 py-2 font-medium">Markings</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($b->items as $i)
                                <tr class="border-t dark:border-slate-700">
                                    <td class="px-4 py-2 font-mono text-xs text-slate-500 dark:text-slate-400">{{ $i->reference }}</td>
                                    <td class="px-4 py-2 font-medium">{{ $i->name }}</td>
                                    <td class="px-4 py-2">{{ $i->color }}</td>
                                    <td class="px-4 py-2">{{ $i->category }}</td>
                                    <td class="px-4 py-2 text-slate-500 dark:text-slate-400">{{ $i->date_lost->toDateString() }}</td>
                                    <td class="px-4 py-2">{{ $i->location }}</td>
                                    <td class="px-4 py-2 text-slate-500 dark:text-slate-400">{{ $i->markings }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-8 text-center text-slate-500 dark:text-slate-400">
                No batches have been released yet. Completed disposal cycles will appear here.
            </div>
        @endforelse
    </div>
</div>
@endsection
