@csrf
<div class="grid sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold">Item name</label>
        <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" required placeholder="e.g. Black HP Laptop"
               class="mt-1 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
    </div>

    <div>
        <label class="text-sm font-semibold">Color</label>
        <input type="text" name="color" value="{{ old('color', $item->color ?? '') }}" placeholder="e.g. Black"
               class="mt-1 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
    </div>

    <div>
        <label class="text-sm font-semibold">Category</label>
        <select name="category" class="mt-1 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
            @foreach ($categories as $c)
                <option value="{{ $c }}" @selected(old('category', $item->category ?? '') === $c)>{{ $c }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="text-sm font-semibold">Date lost</label>
        <input type="date" name="date_lost" value="{{ old('date_lost', isset($item) ? $item->date_lost->toDateString() : now()->toDateString()) }}" required
               class="mt-1 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
    </div>

    <div>
        <label class="text-sm font-semibold">Location found</label>
        <input list="location-options" name="location" value="{{ old('location', $item->location ?? '') }}" required placeholder="e.g. Main Library"
               class="mt-1 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
        <datalist id="location-options">
            @foreach ($locations as $l)
                <option value="{{ $l }}"></option>
            @endforeach
        </datalist>
    </div>

    <div class="sm:col-span-2">
        <label class="text-sm font-semibold">Special markings</label>
        <textarea name="markings" rows="3" placeholder="Distinguishing features, stickers, names, dents…"
                  class="mt-1 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">{{ old('markings', $item->markings ?? '') }}</textarea>
    </div>

    <div>
        <label class="text-sm font-semibold">Logged by (reporter)</label>
        <input type="text" name="reporter" value="{{ old('reporter', $item->reporter ?? auth()->user()->name) }}"
               class="mt-1 w-full px-3 py-2 rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-900 text-sm">
    </div>

    <div>
        <label class="text-sm font-semibold">Photo</label>
        <input type="file" name="image" accept="image/*"
               class="mt-1 w-full text-sm file:mr-3 file:px-3 file:py-1.5 file:rounded-md file:border-0 file:bg-brand-blue file:text-white file:text-xs">
        @if (isset($item) && $item->image_path)
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Leave blank to keep the current photo.</p>
        @endif
    </div>
</div>
