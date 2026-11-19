<?php

namespace App\Http\Controllers;

use App\Models\DisposalBatch;
use App\Models\Item;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403, 'Only an admin account can change these settings.');

        return view('settings.edit', [
            'disposalAgeDays' => Item::disposalAgeDays(),
            'earlyWarningDays' => Item::earlyWarningDays(),
            'pickupWindowDays' => DisposalBatch::pickupWindowDays(),
        ]);
    }

    public function update(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403, 'Only an admin account can change these settings.');

        $data = $request->validate([
            'disposal_age_days' => ['required', 'integer', 'min:1', 'max:3650'],
            'early_warning_days' => ['required', 'integer', 'min:0', 'lt:disposal_age_days'],
            'pickup_window_days' => ['required', 'integer', 'min:1', 'max:60'],
        ], [
            'early_warning_days.lt' => 'The early-warning period must be fewer days than the disposal window itself.',
        ]);

        Setting::set('disposal_age_days', $data['disposal_age_days']);
        Setting::set('early_warning_days', $data['early_warning_days']);
        Setting::set('pickup_window_days', $data['pickup_window_days']);

        return redirect()
            ->route('settings.edit')
            ->with('status', "Saved. Items now become due for disposal after {$data['disposal_age_days']} days — this applies immediately, system-wide.");
    }
}