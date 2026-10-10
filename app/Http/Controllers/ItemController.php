<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function show(Item $item)
    {
        return view('items.show', ['item' => $item]);
    }

    public function create()
    {
        return view('items.create', [
            'categories' => config('lostandfound.categories'),
            'locations' => config('lostandfound.locations'),
        ]);
    }

    public function store(Request $request)
    {
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
        return view('items.edit', [
            'item' => $item,
            'categories' => config('lostandfound.categories'),
            'locations' => config('lostandfound.locations'),
        ]);
    }

    public function update(Request $request, Item $item)
    {
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
        $reference = $item->reference;
        $item->delete();

        return redirect()
            ->route('dashboard')
            ->with('status', "Item {$reference} removed from the registry.");
    }

    public function markClaimed(Item $item)
    {
        $item->update([
            'status' => 'claimed',
            'claimed_at' => now(),
        ]);

        return redirect()
            ->route('items.show', $item)
            ->with('status', "Item {$item->reference} marked as collected by its owner.");
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'color' => ['nullable', 'string', 'max:60'],
            'category' => ['required', 'string', 'max:60'],
            'date_lost' => ['required', 'date', 'before_or_equal:today'],
            'location' => ['required', 'string', 'max:120'],
            'markings' => ['nullable', 'string', 'max:500'],
            'reporter' => ['nullable', 'string', 'max:120'],
            'image' => ['nullable', 'image', 'max:4096'],
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
