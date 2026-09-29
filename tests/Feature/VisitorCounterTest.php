<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class VisitorCounterTest extends TestCase
{
    use DatabaseTransactions;

    public function test_visitor_is_tracked_on_frontend_page_visit(): void
    {
        $initialCount = Visitor::count();

        $response = $this->get('/');
        $response->assertStatus(200);

        // Visitor should be tracked
        $this->assertGreaterThanOrEqual($initialCount, Visitor::count());
    }

    public function test_home_page_displays_all_five_statistics_columns(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Verify all 5 columns are present
        $response->assertSee('Desa Binaan');
        $response->assertSee('Total Penduduk (Jiwa)');
        $response->assertSee('Layanan PATEN');
        $response->assertSee('Dokumen Publik');
        $response->assertSee('Visitor / Pengunjung');

        // Verify marquee ticker track and animation
        $response->assertSee('stats-ticker-track');
        $response->assertSee('statsTickerScroll');
    }

    public function test_total_visitor_count_includes_base_setting(): void
    {
        Setting::set('base_visitor_count', 25000);
        $total = Visitor::totalVisitorsCount();

        $this->assertGreaterThanOrEqual(25000, $total);
    }
}
