<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ItemController extends Controller
{
    public function show(Item $item)
    {
        $this->authorize('view', $item);

        return view('items.show', ['item' => $item]);
    }

    public function create()
    {
        $this->authorize('create', Item::class);

        return view('items.create', [
            'categories' => config('lostandfound.categories'),
            'locations' => config('lostandfound.locations'),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Item::class);

        $data = $this->validated($request);

        $data['reference'] = $this->nextReference();
        $data['status'] = 'active';
        $data['reporter'] = $data['reporter'] ?: optional($request->user())->name;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($data);

        return redirect()
            ->route('items.show', $item)
            ->with('status', "Item {$item->reference} logged successfully.");
    }

    public function edit(Item $item)
    {
        $this->authorize('update', $item);

        return view('items.edit', [
            'item' => $item,
            'categories' => config('lostandfound.categories'),
            'locations' => config('lostandfound.locations'),
        ]);
    }

    public function update(Request $request, Item $item)
    {
        $this->authorize('update', $item);

        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            $data['image_path'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        return redirect()
            ->route('items.show', $item)
            ->with('status', "Item {$item->reference} updated.");
    }

    public function destroy(Item $item)
    {
        $this->authorize('delete', $item);

        if ($item->image_path) {
            Storage::disk('public')->delete($item->image_path);
        }

        $reference = $item->reference;
        $item->delete();

        return redirect()
            ->route('dashboard')
            ->with('status', "Item {$reference} removed from the registry.");
    }

    public function markClaimed(Item $item)
    {
        $this->authorize('claim', $item);

        $item->update([
            'status' => 'claimed',
            'claimed_at' => now(),
        ]);

        return redirect()
            ->route('items.show', $item)
            ->with('status', "Item {$item->reference} marked as collected by its owner.");
    }

    /**
     * Validation rules, hardened for real (not just demo) use:
     *  - photo: real image bytes only (not just a spoofed extension), capped
     *    at 4 MB, restricted to the three web-safe formats, with a sane
     *    minimum/maximum resolution so neither tiny nor absurdly large
     *    uploads make it into storage.
     *  - every text field has a realistic max length so a single bad
     *    submission can't blow up storage or the UI.
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'color' => ['nullable', 'string', 'max:60'],
            'category' => ['required', 'string', 'max:60', Rule::in(config('lostandfound.categories'))],
            'date_lost' => ['required', 'date', 'before_or_equal:today', 'after:'.now()->subYears(2)->toDateString()],
            'location' => ['required', 'string', 'max:120'],
            'markings' => ['nullable', 'string', 'max:500'],
            'reporter' => ['nullable', 'string', 'max:120'],
            'image' => [
                'nullable',
                File::image()
                    ->max(4 * 1024) // 4 MB
                    ->dimensions(Rule::dimensions()->minWidth(80)->minHeight(80)->maxWidth(6000)->maxHeight(6000)),
                'mimes:jpg,jpeg,png,webp',
            ],
        ]);
    }

    private function nextReference(): string
    {
        $last = Item::query()->orderByDesc('id')->value('id') ?? 1000;

        do {
            $last++;
            $candidate = 'LF-'.$last;
        } while (Item::where('reference', $candidate)->exists());

        return $candidate;
    }
}
