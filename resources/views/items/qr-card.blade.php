<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>QR card · {{ $item->reference }}</title>
    <style>
        @page { size: 80mm 120mm; margin: 6mm; }
        * { box-sizing: border-box; }
        body {
            font-family: ui-sans-serif, system-ui, sans-serif;
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh; margin: 0; background: #f1f5f9;
        }
        .card {
            width: 280px; background: white; border: 1px solid #e2e8f0; border-radius: 12px;
            padding: 20px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,.1);
        }
        .ref { font-family: ui-monospace, monospace; font-size: 12px; color: #64748b; }
        h1 { font-size: 16px; margin: 4px 0 12px; }
        .qr { margin: 0 auto 12px; width: 180px; height: 180px; }
        .meta { font-size: 11px; color: #64748b; text-align: left; margin-top: 10px; line-height: 1.5; }
        .brand { font-size: 10px; color: #94a3b8; margin-top: 14px; }
        .print-btn {
            margin-top: 16px; padding: 8px 16px; border-radius: 8px; border: 1px solid #cbd5e1;
            background: white; cursor: pointer; font-size: 13px;
        }
        @media print { .no-print { display: none; } body { background: white; } }
    </style>
</head>
<body>
    <div class="card">
        <div class="ref">{{ $item->reference }}</div>
        <h1>{{ $item->name }}</h1>
        <img class="qr" src="{{ route('items.qr', $item) }}" alt="QR code for {{ $item->reference }}">
        <div class="meta">
            <div><strong>Color:</strong> {{ $item->color ?: '—' }}</div>
            <div><strong>Location:</strong> {{ $item->location }}</div>
            <div><strong>Date lost:</strong> {{ $item->date_lost->toDateString() }}</div>
        </div>
        <div class="brand">Strathmore University · Lost &amp; Found Office</div>
        <button class="print-btn no-print" onclick="window.print()">Print this card</button>
    </div>
</body>
</html>
