<?php

namespace App\Exports;

use App\Models\Item;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AuditLogExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        private Carbon $from,
        private Carbon $to,
    ) {}

    public function collection(): Collection
    {
        return Item::with('disposalBatch')
            ->where('status', 'donated')
            ->whereHas('disposalBatch', fn ($q) => $q->whereBetween('released_at', [$this->from, $this->to]))
            ->orderBy('disposal_batch_id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Reference', 'Item', 'Color', 'Category', 'Date Lost',
            'Location', 'Markings', 'Batch Reference', 'Released At',
        ];
    }

    public function map($item): array
    {
        return [
            $item->reference,
            $item->name,
            $item->color,
            $item->category,
            $item->date_lost->toDateString(),
            $item->location,
            $item->markings,
            $item->disposalBatch?->reference,
            $item->disposalBatch?->released_at?->toDateString(),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
