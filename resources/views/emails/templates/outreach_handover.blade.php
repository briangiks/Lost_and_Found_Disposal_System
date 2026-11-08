@php
    // Accepts either a real collection of Item models ($releasedItems) or
    // nothing at all (a bare count-only preview before any batch exists).
    $releasedItems = $releasedItems ?? collect();
    $releasedCount = $releasedCount ?? $releasedItems->count();
@endphp
<p>Hello Community Outreach Club,</p>
<p>
    Please arrange collection today from the Lost &amp; Found office of this quarter's
    unclaimed items for onward donation.
    @if ($releasedCount)
        In total, <strong>{{ $releasedCount }}</strong> {{ \Illuminate\Support\Str::plural('item', $releasedCount) }} {{ $releasedCount === 1 ? 'is' : 'are' }} being handed over
        @isset($batchReference)
            as part of batch <strong>#{{ $batchReference }}</strong>
        @endisset
        .
    @endif
</p>
<p>📍 {{ $officeLocation }}</p>

@if ($releasedItems->isNotEmpty())
    <p style="margin-bottom:6px;"><strong>Itemized handover report</strong></p>
    <table style="width:100%; border-collapse:collapse; font-size:13px; margin-bottom:12px;">
        <thead>
            <tr style="background:#f1f5f9; text-align:left;">
                <th style="padding:6px 8px; border-bottom:1px solid #cbd5e1;">Reference</th>
                <th style="padding:6px 8px; border-bottom:1px solid #cbd5e1;">Item</th>
                <th style="padding:6px 8px; border-bottom:1px solid #cbd5e1;">Color</th>
                <th style="padding:6px 8px; border-bottom:1px solid #cbd5e1;">Category</th>
                <th style="padding:6px 8px; border-bottom:1px solid #cbd5e1;">Date lost</th>
                <th style="padding:6px 8px; border-bottom:1px solid #cbd5e1;">Location</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($releasedItems as $item)
                <tr>
                    <td style="padding:6px 8px; border-bottom:1px solid #e2e8f0;">{{ $item->reference }}</td>
                    <td style="padding:6px 8px; border-bottom:1px solid #e2e8f0;">{{ $item->name }}</td>
                    <td style="padding:6px 8px; border-bottom:1px solid #e2e8f0;">{{ $item->color }}</td>
                    <td style="padding:6px 8px; border-bottom:1px solid #e2e8f0;">{{ $item->category }}</td>
                    <td style="padding:6px 8px; border-bottom:1px solid #e2e8f0;">{{ $item->date_lost->toDateString() }}</td>
                    <td style="padding:6px 8px; border-bottom:1px solid #e2e8f0;">{{ $item->location }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p style="font-size:12px; color:#64748b;">
        This list is also permanently recorded in the Lost &amp; Found system's audit log for university records.
    </p>
@else
    <p>These are general disposed items — the full manifest is stored in the audit log.</p>
@endif

<p>Thank you for the partnership.</p>