<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #1e293b; }
        .meta { color: #64748b; font-size: 10px; margin-bottom: 14px; }
    </style>
</head>
<body>
    <div class="meta">Strathmore University · Lost &amp; Found Office — generated {{ now()->toDateTimeString() }}</div>
    @include('emails.partials.manifest-table', ['items' => $items, 'monthLabel' => $monthLabel ?? null])
</body>
</html>