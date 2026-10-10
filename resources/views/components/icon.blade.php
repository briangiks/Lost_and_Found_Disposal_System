@props(['name', 'class' => 'w-4 h-4'])

@php
    $paths = [
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
        'lock' => '<rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>',
        'eye' => '<path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>',
        'eye-off' => '<path d="M3 3l18 18"/><path d="M10.6 5.2A10.6 10.6 0 0 1 12 5c6 0 10 7 10 7a16.6 16.6 0 0 1-3.2 3.9M6.6 6.6C4 8.3 2 12 2 12s4 7 10 7a9.6 9.6 0 0 0 4.4-1"/><circle cx="12" cy="12" r="3"/>',
        'shield-check' => '<path d="M12 3 4 6v6c0 5 3.5 8.5 8 9 4.5-.5 8-4 8-9V6l-8-3Z"/><path d="m9 12 2 2 4-4"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
        'filter' => '<path d="M4 5h16M7 12h10M10 19h4"/>',
        'alert-triangle' => '<path d="M12 3 2 20h20L12 3Z"/><path d="M12 10v4M12 17h.01"/>',
        'send' => '<path d="m3 11 18-8-8 18-2-7-8-3Z"/>',
        'image' => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><path d="m21 17-5-5-4 4-3-3-5 5"/>',
        'arrow-up-right' => '<path d="M7 17 17 7M8 7h9v9"/>',
        'gift' => '<rect x="3" y="9" width="18" height="12" rx="1"/><path d="M12 9v12M3 9h18M12 9c-1.5-4-6-4-6-1s4.5 1 6 1Zm0 0c1.5-4 6-4 6-1s-4.5 1-6 1Z"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>',
        'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
        'chevron-right' => '<path d="m9 6 6 6-6 6"/>',
        'package' => '<path d="m3.3 7 8.7 5 8.7-5M12 22V12M4 7l8-4 8 4-8 4-8-4Zm16 10V7L12 12v10l8-5Z"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/>',
        'map-pin' => '<path d="M12 22s7-6.1 7-12a7 7 0 1 0-14 0c0 5.9 7 12 7 12Z"/><circle cx="12" cy="10" r="2.5"/>',
        'tag' => '<path d="M20 10 13 3H5a2 2 0 0 0-2 2v8l7 7 10-10Z"/><circle cx="8.5" cy="7.5" r="1"/>',
        'scan-search' => '<path d="M4 7V5a2 2 0 0 1 2-2h2M16 3h2a2 2 0 0 1 2 2v2M20 17v2a2 2 0 0 1-2 2h-2M8 21H6a2 2 0 0 1-2-2v-2"/><circle cx="11" cy="11" r="3"/><path d="m15 15 2 2"/>',
        'arrow-left' => '<path d="m12 19-7-7 7-7M5 12h14"/>',
        'check-circle' => '<circle cx="12" cy="12" r="9"/><path d="m8.5 12.5 2.3 2.3L16 10"/>',
        'circle' => '<circle cx="12" cy="12" r="9"/>',
        'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
        'moon' => '<path d="M20 14.5A8.5 8.5 0 1 1 9.5 4a7 7 0 0 0 10.5 10.5Z"/>',
        'bell' => '<path d="M6 9a6 6 0 1 1 12 0c0 5 2 6 2 6H4s2-1 2-6Z"/><path d="M10 20a2 2 0 0 0 4 0"/>',
        'log-out' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>',
        'layout-dashboard' => '<rect x="3" y="3" width="8" height="8" rx="1"/><rect x="13" y="3" width="8" height="5" rx="1"/><rect x="13" y="12" width="8" height="9" rx="1"/><rect x="3" y="14" width="8" height="7" rx="1"/>',
        'workflow' => '<rect x="3" y="3" width="7" height="6" rx="1"/><rect x="14" y="15" width="7" height="6" rx="1"/><path d="M6.5 9v3a2 2 0 0 0 2 2H14M17.5 15v-2"/>',
        'clipboard-list' => '<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 3h6v3H9zM9 11h6M9 15h6M9 19h4"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'pencil' => '<path d="m17 3 4 4L7 21H3v-4Z"/>',
        'trash' => '<path d="M4 7h16M9 7V4h6v3M6 7l1 13a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-13"/>',
        'upload' => '<path d="M12 16V4M7 9l5-5 5 5M4 20h16"/>',
        'x' => '<path d="M18 6 6 18M6 6l12 12"/>',
    ];
    $path = $paths[$name] ?? $paths['circle'];
@endphp
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $class }}">{!! $path !!}</svg>
