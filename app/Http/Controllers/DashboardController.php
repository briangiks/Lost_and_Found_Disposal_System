<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query()->active();

        if ($q = trim((string) $request->get('q'))) {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('reference', 'like', "%{$q}%")
                    ->orWhere('color', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%")
                    ->orWhere('markings', 'like', "%{$q}%");
            });
        }

        if (($category = $request->get('category')) && $category !== 'All') {
            $query->where('category', $category);
        }

        if (($color = $request->get('color')) && $color !== 'All') {
            $query->where('color', $color);
        }

        if (($location = $request->get('location')) && $location !== 'All') {
            $query->where('location', $location);
        }

        if ($markings = trim((string) $request->get('markings'))) {
            $query->where('markings', 'like', "%{$markings}%");
        }

        if ($dateLost = $request->get('date_lost')) {
            $query->whereDate('date_lost', $dateLost);
        }

        $items = $query->orderByDesc('date_lost')->get();

        $totalInCustody = Item::active()->count();
        $totalToDispose = Item::dueForDisposal()->count();

        $approachingItems = Item::active()->get()->filter(fn (Item $i) => $i->is_approaching_disposal);
        $dueItems = Item::active()->get()->filter(fn (Item $i) => $i->is_due_for_disposal);

        $categories = collect(['All'])->merge(config('lostandfound.categories'))->unique()->values();
        $colorOptions = collect(['All'])->merge(
            Item::query()->whereNotNull('color')->distinct()->orderBy('color')->pluck('color')
        )->unique()->values();
        $locationOptions = collect(['All'])->merge(config('lostandfound.locations'))->unique()->values();

        return view('dashboard.index', [
            'items' => $items,
            'totalCount' => Item::count(),
            'totalInCustody' => $totalInCustody,
            'totalToDispose' => $totalToDispose,
            'approachingCount' => $approachingItems->count(),
            'dueCount' => $dueItems->count(),
            'categories' => $categories,
            'colorOptions' => $colorOptions,
            'locationOptions' => $locationOptions,
            'filters' => [
                'q' => $q ?? '',
                'category' => $category ?? 'All',
                'color' => $color ?? 'All',
                'location' => $location ?? 'All',
                'markings' => $markings ?? '',
                'date_lost' => $dateLost ?? '',
            ],
            'chartData' => ReportController::monthlyChartData(),
        ]);
    }
}
