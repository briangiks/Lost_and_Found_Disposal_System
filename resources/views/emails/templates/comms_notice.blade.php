@php $items = $items ?? collect(); @endphp
<p>Dear Strathmore Communications team,</p>
<p>
    The Lost &amp; Found office is preparing this quarter's disposal of unclaimed items.
    Kindly circulate a notice to all students and university staff today letting them know
    they have <strong>one week</strong> to come and check for any of their belongings at the
    Lost &amp; Found office.
</p>
<p>📍 <strong>Location:</strong> {{ $officeLocation }}</p>
<p>
    Any items not collected within the week will be handed over to the Community
    Outreach Club on <strong>{{ $handoverDate->translatedFormat('l, j F Y') }}</strong> for disposal.
</p>

@if ($items->isNotEmpty())
    <p>For your reference, and to forward to students and staff, the full list of unclaimed items is below:</p>
    @include('emails.partials.manifest-table', ['items' => $items, 'monthLabel' => $handoverDate->format('F Y')])
@endif

<p>Thank you,<br>Lost &amp; Found Office</p>