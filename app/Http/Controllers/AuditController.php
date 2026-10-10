<?php

namespace App\Http\Controllers;

use App\Models\DisposalBatch;

class AuditController extends Controller
{
    public function index()
    {
        $batches = DisposalBatch::with(['items', 'officer'])
            ->where('stage', 'released')
            ->orderByDesc('released_at')
            ->get();

        return view('audit.index', ['batches' => $batches]);
    }
}
