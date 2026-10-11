<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1e293b; }
        h1 { font-size: 18px; color: #1c3357; margin-bottom: 2px; }
        .meta { color: #64748b; font-size: 10px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f1f5f9; text-align: left; padding: 6px 8px; font-size: 10px; border-bottom: 1px solid #cbd5e1; }
        td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        .summary { margin-top: 10px; font-size: 12px; font-weight: bold; }
        .footer { margin-top: 24px; font-size: 9px; color: #94a3b8; }
    </style>
</head>
<body>
    <h1>Strathmore University · Lost &amp; Found Disposal Audit Report</h1>
    <div class="meta">
        Period: {{ $periodLabel }} ({{ $from->toDateString() }} to {{ $to->toDateString() }}) ·
        Generated {{ $generatedAt->toDateTimeString() }}
    </div>

    <div class="summary">{{ $items->count() }} item(s) disposed in this period.</div>

    <table>
        <thead>
            <tr>
                <th>Reference</th>
                <th>Item</th>
                <th>Category</th>
                <th>Location</th>
                <th>Date Lost</th>
                <th>Batch</th>
                <th>Released</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td>{{ $item->reference }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->location }}</td>
                    <td>{{ $item->date_lost->toDateString() }}</td>
                    <td>{{ $item->disposalBatch?->reference }}</td>
                    <td>{{ $item->disposalBatch?->released_at?->toDateString() }}</td>
                </tr>
            @empty
                <tr><td colspan="7">No items were disposed in this period.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Strathmore University · Lost &amp; Found Disposal System — this is a system-generated record for administrative use.
    </div>
</body>
</html>
