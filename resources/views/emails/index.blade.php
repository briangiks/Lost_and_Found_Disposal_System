@extends('layouts.app')

@section('title', 'Email Preview')

@section('content')
<div class="mx-auto max-w-4xl px-4 sm:px-6 py-6 space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Email templates</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Drafts generated during a quarterly disposal cycle. Once a stage is actually sent from the
            disposal workflow, the real, logged copy is shown here instead of the draft.
        </p>
    </div>

    <div class="flex gap-2 border-b dark:border-slate-700 overflow-x-auto">
        @foreach ($types as $key => $label)
            <a href="{{ route('emails.index', ['type' => $key]) }}"
               class="px-3 py-2 text-sm font-medium border-b-2 -mb-px whitespace-nowrap {{ $activeType === $key ? 'border-brand-red text-brand-red' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 overflow-hidden">
        <div class="border-b dark:border-slate-700 bg-slate-50 dark:bg-slate-900/40 p-4 text-sm space-y-1">
            <div class="flex gap-2"><span class="text-slate-500 dark:text-slate-400 w-16">From:</span><span class="font-medium">{{ $preview['from'] }}</span></div>
            <div class="flex gap-2"><span class="text-slate-500 dark:text-slate-400 w-16">To:</span><span class="font-medium">{{ $preview['to'] }}</span></div>
            @if ($preview['cc'])
                <div class="flex gap-2"><span class="text-slate-500 dark:text-slate-400 w-16">CC:</span><span class="font-medium">{{ $preview['cc'] }}</span></div>
            @endif
            <div class="flex gap-2"><span class="text-slate-500 dark:text-slate-400 w-16">Subject:</span><span class="font-medium">{{ $preview['subject'] }}</span></div>
        </div>

        <div class="p-6 text-sm leading-relaxed [&_p]:mb-3 [&_strong]:font-semibold max-w-none">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded bg-brand-red text-white grid place-content-center font-bold text-sm">S</div>
                <div class="font-semibold text-brand-bluedark dark:text-white">Strathmore University</div>
            </div>

            {!! $preview['body'] !!}

            <hr class="my-4 border-slate-200 dark:border-slate-700">
            <p class="text-xs text-slate-500 dark:text-slate-400">Strathmore University · Ole Sangale Road, Madaraka Estate, Nairobi</p>
        </div>

        <div class="px-6 pb-4">
            @if ($preview['is_live'])
                <span class="inline-flex items-center gap-1.5 text-xs px-2 py-1 rounded-md bg-emerald-100 text-emerald-700 border border-emerald-300 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-700">
                    <x-icon name="check-circle" class="w-3 h-3" /> Actually sent {{ $preview['sent_at']?->diffForHumans() }}
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 text-xs px-2 py-1 rounded-md bg-slate-100 text-slate-600 border border-slate-300 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600">
                    Preview only — not yet sent for the current cycle
                </span>
            @endif
        </div>
    </div>
</div>
@endsection
