@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="mx-auto max-w-3xl px-4 sm:px-6 py-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Notifications</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Items approaching disposal and disposal batch updates.</p>
        </div>
        @if ($notifications->getCollection()->contains(fn ($n) => $n->read_at === null))
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="px-3 py-2 rounded-md border dark:border-slate-600 text-sm">Mark all as read</button>
            </form>
        @endif
    </div>

    <div class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 divide-y dark:divide-slate-700">
        @forelse ($notifications as $n)
            <form method="POST" action="{{ route('notifications.read', $n->id) }}" class="block">
                @csrf
                <button type="submit" class="w-full text-left p-4 flex items-start gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/40 {{ $n->read_at ? 'opacity-60' : '' }}">
                    <span class="mt-0.5 shrink-0 w-8 h-8 rounded-full bg-brand-blue/10 text-brand-blue grid place-content-center">
                        <x-icon :name="$n->data['icon'] ?? 'bell'" class="w-4 h-4" />
                    </span>
                    <span class="flex-1">
                        <span class="block text-sm font-medium">{{ $n->data['title'] ?? 'Notification' }}</span>
                        <span class="block text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $n->data['body'] ?? '' }}</span>
                        <span class="block text-xs text-slate-400 dark:text-slate-500 mt-1">{{ $n->created_at->diffForHumans() }}</span>
                    </span>
                    @if (! $n->read_at)
                        <span class="w-2 h-2 rounded-full bg-brand-red shrink-0 mt-2"></span>
                    @endif
                </button>
            </form>
        @empty
            <div class="p-8 text-center text-slate-500 dark:text-slate-400">No notifications yet.</div>
        @endforelse
    </div>

    {{ $notifications->links() }}
</div>
@endsection
