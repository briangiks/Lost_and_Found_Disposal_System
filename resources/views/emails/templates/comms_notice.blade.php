@php $itemCount = $itemCount ?? 0; @endphp
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
@if ($itemCount > 0)
    <p>
        The full itemized list of unclaimed items ({{ $itemCount }} in total) is attached as a PDF —
        please forward it along with this notice to students and staff.
    </p>
@endif
<p>Thank you,<br>Lost &amp; Found Office</p>