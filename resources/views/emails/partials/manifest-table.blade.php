{{--
    Shared by emails.templates.comms_notice and emails.templates.outreach_handover.
    Expects:
      $items       - a collection of Item models (required)
      $monthLabel  - e.g. "August 2026" (optional — defaults to the current month)
--}}
@php
    $manifest = \App\Models\Item::groupedManifest($items);
    $monthLabel = $monthLabel ?? now()->format('F Y');
    $rowNumber = 0;
@endphp
<p style="font-weight:bold; text-decoration:underline; margin-bottom:10px;">
    UNCLAIMED LOST AND FOUND ITEMS FOR THE MONTH OF {{ strtoupper($monthLabel) }}.
</p>
<table style="width:100%; border-collapse:collapse; font-size:13px; margin-bottom:12px;" border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr style="background:#f1f5f9;">
            <th style="border:1px solid #334155; padding:6px;">NO.</th>
            <th style="border:1px solid #334155; padding:6px; text-align:left;">ITEM DESCRIPTION</th>
            <th style="border:1px solid #334155; padding:6px;">QUANTITY</th>
            <th style="border:1px solid #334155; padding:6px;">S/NO</th>
            <th style="border:1px solid #334155; padding:6px;">DISPOSE TO</th>
            <th style="border:1px solid #334155; padding:6px;">CONFIRMED BY</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($manifest as $category => $itemCounts)
            <tr>
                <td colspan="6" style="border:1px solid #334155; padding:6px; text-align:center; font-weight:bold;">
                    {{ strtoupper($category === 'Other' ? 'Others' : $category) }}
                </td>
            </tr>
            @foreach ($itemCounts as $name => $qty)
                @php $rowNumber++; @endphp
                <tr>
                    <td style="border:1px solid #334155; padding:6px; text-align:center;">{{ $rowNumber }}.</td>
                    <td style="border:1px solid #334155; padding:6px;">{{ $name }}</td>
                    <td style="border:1px solid #334155; padding:6px; text-align:center;">{{ str_pad((string) $qty, 2, '0', STR_PAD_LEFT) }}</td>
                    <td style="border:1px solid #334155; padding:6px;">&nbsp;</td>
                    <td style="border:1px solid #334155; padding:6px; text-align:center;">COP</td>
                    <td style="border:1px solid #334155; padding:6px;">&nbsp;</td>
                </tr>
            @endforeach
        @empty
            <tr><td colspan="6" style="border:1px solid #334155; padding:10px; text-align:center;">No items in this list.</td></tr>
        @endforelse
    </tbody>
</table>