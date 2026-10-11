<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Response;
use F9WebLtd\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    /**
     * Raw QR code image, encoding a direct link to the item's detail page.
     * Scanning it at the front desk takes staff straight to items.show for
     * fast intake lookup or claim verification — no typing the reference in.
     */
    public function image(Item $item): Response
    {
        $this->authorize('view', $item);

        $svg = QrCode::size(240)
            ->errorCorrection('M')
            ->generate(route('items.show', $item));

        return response($svg, 200)->header('Content-Type', 'image/svg+xml');
    }

    /** Printable desk card: QR code + the essentials, sized for a small sticker/label. */
    public function card(Item $item)
    {
        $this->authorize('view', $item);

        return view('items.qr-card', ['item' => $item]);
    }
}
