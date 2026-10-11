<?php

namespace App\Http\Controllers;

use App\Exports\AuditLogExport;
use App\Models\Item;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        [$from, $to, $periodLabel] = $this->resolvePeriod($request);

        $items = $this->disposedItemsBetween($from, $to);

        return view('reports.index', [
            'period' => $request->get('period', 'this_month'),
            'from' => $from,
            'to' => $to,
            'periodLabel' => $periodLabel,
            'items' => $items,
            'totalDisposed' => $items->count(),
            'batchCount' => $items->pluck('disposal_batch_id')->unique()->count(),
        ]);
    }

    public function exportPdf(Request $request)
    {
        [$from, $to, $periodLabel] = $this->resolvePeriod($request);
        $items = $this->disposedItemsBetween($from, $to);

        $pdf = Pdf::loadView('reports.pdf', [
            'items' => $items,
            'periodLabel' => $periodLabel,
            'from' => $from,
            'to' => $to,
            'generatedAt' => now(),
        ])->setPaper('a4');

        return $pdf->download("lost-and-found-audit-{$from->toDateString()}-to-{$to->toDateString()}.pdf");
    }

    public function exportExcel(Request $request)
    {
        [$from, $to] = $this->resolvePeriod($request);

        return Excel::download(
            new AuditLogExport($from, $to),
            "lost-and-found-audit-{$from->toDateString()}-to-{$to->toDateString()}.xlsx"
        );
    }

    /**
     * Resolve the requested period into a [from, to, label] date range.
     * Supports the presets the UI exposes plus an arbitrary custom range.
     */
    private function resolvePeriod(Request $request): array
    {
        $period = $request->get('period', 'this_month');
        $now = Carbon::now();

        return match ($period) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay(), 'Today'],
            'this_week' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek(), 'This week'],
            'this_month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth(), 'This month'],
            'this_quarter' => [$now->copy()->startOfQuarter(), $now->copy()->endOfQuarter(), 'This quarter'],
            'this_year' => [$now->copy()->startOfYear(), $now->copy()->endOfYear(), 'This year'],
            'custom' => [
                Carbon::parse($request->get('from', $now->copy()->subMonth()))->startOfDay(),
                Carbon::parse($request->get('to', $now))->endOfDay(),
                'Custom range',
            ],
            default => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth(), 'This month'],
        };
    }

    private function disposedItemsBetween(Carbon $from, Carbon $to)
    {
        return Item::with('disposalBatch')
            ->where('status', 'donated')
            ->whereHas('disposalBatch', fn ($q) => $q->whereBetween('released_at', [$from, $to]))
            ->orderByDesc('updated_at')
            ->get();
    }

    /**
     * Last 6 months of logged / claimed / disposed counts, used by the
     * dashboard's Chart.js bar chart.
     */
    public static function monthlyChartData(int $months = 6): array
    {
        $labels = [];
        $logged = [];
        $claimed = [];
        $disposed = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $labels[] = $month->format('M Y');
            $logged[] = Item::whereBetween('created_at', [$start, $end])->count();
            $claimed[] = Item::where('status', 'claimed')->whereBetween('claimed_at', [$start, $end])->count();
            $disposed[] = Item::where('status', 'donated')
                ->whereHas('disposalBatch', fn ($q) => $q->whereBetween('released_at', [$start, $end]))
                ->count();
        }

        return compact('labels', 'logged', 'claimed', 'disposed');
    }
}
