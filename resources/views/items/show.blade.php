@extends('layouts.app')

@section('title', $item->reference.' · Item detail')

@section('content')
<div class="mx-auto max-w-5xl px-4 sm:px-6 py-6 space-y-6">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
        <x-icon name="arrow-left" class="w-4 h-4" /> Back to dashboard
    </a>

    <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-1">
            <img src="{{ $item->imageUrl() }}" alt="{{ $item->name }}" class="w-full aspect-square object-cover rounded-lg border dark:border-slate-700">
            <div class="mt-3 text-xs text-slate-500 dark:text-slate-400">Photo uploaded at intake.</div>
        </div>

        <div class="md:col-span-2 space-y-4">
            <div>
                <div class="text-xs font-mono text-slate-500 dark:text-slate-400">{{ $item->reference }}</div>
                <h1 class="text-2xl font-bold">{{ $item->name }}</h1>
                <div class="mt-2"><x-status-badge :status="$item->status" /></div>
            </div>

            <div class="grid sm:grid-cols-2 gap-3 text-sm">
                <div class="rounded-md border bg-white dark:bg-slate-800 dark:border-slate-700 p-3">
                    <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400"><x-icon name="tag" class="w-4 h-4" />Color</div>
                    <div class="mt-1 font-medium">{{ $item->color ?: '—' }}</div>
                </div>
                <div class="rounded-md border bg-white dark:bg-slate-800 dark:border-slate-700 p-3">
                    <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400"><x-icon name="tag" class="w-4 h-4" />Category</div>
                    <div class="mt-1 font-medium">{{ $item->category }}</div>
                </div>
                <div class="rounded-md border bg-white dark:bg-slate-800 dark:border-slate-700 p-3">
                    <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400"><x-icon name="calendar" class="w-4 h-4" />Date lost</div>
                    <div class="mt-1 font-medium {{ $item->is_due_for_disposal ? 'text-red-600' : '' }}">
                        {{ $item->date_lost->toDateString() }} ({{ $item->age_in_days }} days ago)
                    </div>
                </div>
                <div class="rounded-md border bg-white dark:bg-slate-800 dark:border-slate-700 p-3">
                    <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400"><x-icon name="map-pin" class="w-4 h-4" />Location</div>
                    <div class="mt-1 font-medium">{{ $item->location }}</div>
                </div>
                <div class="rounded-md border bg-white dark:bg-slate-800 dark:border-slate-700 p-3 sm:col-span-2">
                    <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400"><x-icon name="scan-search" class="w-4 h-4" />Special markings</div>
                    <div class="mt-1 font-medium">{{ $item->markings ?: '—' }}</div>
                </div>
                @if ($item->reporter)
                <div class="rounded-md border bg-white dark:bg-slate-800 dark:border-slate-700 p-3 sm:col-span-2">
                    <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400"><x-icon name="user" class="w-4 h-4" />Logged by</div>
                    <div class="mt-1 font-medium">{{ $item->reporter }}</div>
                </div>
                @endif
            </div>

            <div class="rounded-lg border dark:border-slate-700 p-4 bg-slate-50 dark:bg-slate-900/40">
                <div class="text-sm font-medium mb-2">Disposal timeline</div>
                <ul class="text-sm space-y-1.5 text-slate-500 dark:text-slate-400">
                    <li>• Day 0 — Item logged at L&amp;F intake desk.</li>
                    <li>• Day 60 — Reminder posted on student noticeboard.</li>
                    <li class="{{ $item->age_in_days >= 90 ? 'text-red-600 font-medium' : '' }}">• Day 90 — Flagged for disposal review.</li>
                    <li>• Day 90+ — If unclaimed, released to Community Outreach Club in the next quarterly batch.</li>
                    @if ($item->status === 'claimed')
                        <li class="text-emerald-600 font-medium">• {{ $item->claimed_at?->toDateString() }} — Collected by owner.</li>
                    @endif
                    @if ($item->status === 'donated')
                        <li class="text-brand-blue font-medium">• Released to Community Outreach Club (batch {{ $item->disposalBatch?->reference }}).</li>
                    @endif
                </ul>
            </div>

            <div class="flex flex-wrap gap-2">
                @if ($item->status === 'active')
                    <a href="{{ route('disposal.index') }}" class="px-3 py-2 rounded-md bg-brand-red text-white text-sm font-medium">View disposal batch</a>
                    @can('claim', $item)
                        <form method="POST" action="{{ route('items.claim', $item) }}">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-md border dark:border-slate-600 text-sm">Mark as collected</button>
                        </form>
                    @endcan
                @endif
                <a href="{{ route('items.edit', $item) }}" class="px-3 py-2 rounded-md border dark:border-slate-600 text-sm inline-flex items-center gap-1.5">
                    <x-icon name="pencil" class="w-3.5 h-3.5" /> Edit
                </a>
                <a href="{{ route('items.qr-card', $item) }}" target="_blank" class="px-3 py-2 rounded-md border dark:border-slate-600 text-sm inline-flex items-center gap-1.5">
                    <x-icon name="scan-search" class="w-3.5 h-3.5" /> Print QR desk card
                </a>
                @can('delete', $item)
                    <form method="POST" action="{{ route('items.destroy', $item) }}" onsubmit="return confirm('Remove {{ $item->reference }} from the registry? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-2 rounded-md border border-red-300 text-red-600 dark:border-red-700 dark:text-red-400 text-sm inline-flex items-center gap-1.5">
                            <x-icon name="trash" class="w-3.5 h-3.5" /> Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
