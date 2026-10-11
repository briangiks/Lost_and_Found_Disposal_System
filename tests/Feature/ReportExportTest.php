<?php

namespace Tests\Feature;

use App\Models\DisposalBatch;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    private function disposedItem(): Item
    {
        $batch = DisposalBatch::create([
            'reference' => 'B-100',
            'cycle_label' => 'Test',
            'stage' => 'released',
            'released_at' => now(),
        ]);

        return Item::factory()->create([
            'status' => 'donated',
            'disposal_batch_id' => $batch->id,
        ]);
    }

    public function test_the_reports_page_lists_items_disposed_in_the_selected_period(): void
    {
        $this->actingAs(User::factory()->create());
        $item = $this->disposedItem();

        $this->get('/reports?period=this_month')
            ->assertOk()
            ->assertSee($item->reference);
    }

    public function test_pdf_export_downloads_successfully(): void
    {
        $this->actingAs(User::factory()->create());
        $this->disposedItem();

        $response = $this->get('/reports/export/pdf?period=this_month');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_excel_export_downloads_successfully(): void
    {
        $this->actingAs(User::factory()->create());
        $this->disposedItem();

        $response = $this->get('/reports/export/excel?period=this_month');

        $response->assertOk();
    }
}
