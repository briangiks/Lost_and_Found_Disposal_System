@props(['status'])

@php
    $styles = [
        'active' => 'bg-slate-100 text-slate-700 border border-slate-300 dark:bg-slate-700 dark:text-slate-200 dark:border-slate-600',
        'claimed' => 'bg-emerald-100 text-emerald-700 border border-emerald-300 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-700',
        'donated' => 'bg-brand-blue/10 text-brand-blue border border-brand-blue/30',
    ];
    $labels = [
        'active' => 'In Custody',
        'claimed' => 'Claimed by Owner',
        'donated' => 'Donated',
    ];
@endphp
<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium {{ $styles[$status] ?? $styles['active'] }}">
    {{ $labels[$status] ?? ucfirst($status) }}
</span>
