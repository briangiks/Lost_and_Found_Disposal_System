<p>Hello Community Outreach Club,</p>
<p>
    Please arrange collection today from the Lost &amp; Found office of this quarter's
    unclaimed items for onward donation.
    @if ($releasedCount !== null)
        In total, <strong>{{ $releasedCount }}</strong> {{ \Illuminate\Support\Str::plural('item', $releasedCount) }} {{ $releasedCount === 1 ? 'is' : 'are' }} being handed over.
    @endif
    These are general disposed items — the full manifest is stored in the audit log.
</p>
<p>📍 {{ $officeLocation }}</p>
<p>Thank you for the partnership.</p>
