@php
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
    @include('emails.partials.manifest-table', ['items' => $releasedItems])
    <p style="font-size:12px; color:#64748b;">
        This list is also permanently recorded in the Lost &amp; Found system's audit log for university records.
    </p>
@else
    <p>These are general disposed items — the full manifest is stored in the audit log.</p>
@endif

<p>Thank you for the partnership.</p>