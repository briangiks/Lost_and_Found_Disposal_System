@extends('layouts.app')

@section('title', 'Edit '.$item->reference)

@section('content')
<div class="mx-auto max-w-3xl px-4 sm:px-6 py-6 space-y-6">
    <a href="{{ route('items.show', $item) }}" class="inline-flex items-center gap-1 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
        <x-icon name="arrow-left" class="w-4 h-4" /> Back to {{ $item->reference }}
    </a>

    <div>
        <h1 class="text-2xl font-bold">Edit {{ $item->reference }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Update the details recorded for this item.</p>
    </div>

    <form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data" class="rounded-lg border bg-white dark:bg-slate-800 dark:border-slate-700 p-6 space-y-6">
        @method('PUT')
        @include('items._form')

        <div class="flex justify-end gap-2 pt-2 border-t dark:border-slate-700">
            <a href="{{ route('items.show', $item) }}" class="px-4 py-2 rounded-md border dark:border-slate-600 text-sm">Cancel</a>
            <button type="submit" class="px-4 py-2 rounded-md bg-brand-red text-white text-sm font-medium">Save changes</button>
        </div>
    </form>
</div>
@endsection
