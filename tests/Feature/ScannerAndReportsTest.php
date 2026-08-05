<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Home;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ScannerAndReportsTest extends TestCase
{
    use RefreshDatabase;

    public function test_scanner_can_return_suggested_values_for_a_device(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/scanner/analyze', [
            'image' => UploadedFile::fake()->image('voltage220.jpg', 400, 300),
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'suggested_name',
                'suggested_voltage',
                'suggested_power',
                'suggested_hours_per_day',
            ]);

        $this->assertNotEmpty($response->json('suggested_name'));
        $this->assertNotNull($response->json('suggested_voltage'));
    }

    public function test_reports_can_be_rendered_and_downloaded_for_the_selected_month(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $home = Home::create([
            'name' => 'Casa del reporte',
            'address' => 'Calle 123',
            'user_id' => $owner->id,
            'code' => 'RPT1',
        ]);

        $home->members()->attach($member->id, ['role' => 'member']);

        $device = Device::create([
            'home_id' => $home->id,
            'user_id' => $member->id,
            'name' => 'Refrigerador',
            'type' => 'general',
            'power' => 120,
            'hours_per_day' => 8,
        ]);

        $this->actingAs($member)
            ->get('/reports?mes=2026-08')
            ->assertOk()
            ->assertSee('Refrigerador');

        $this->actingAs($member)
            ->get('/reports/download?mes=2026-08')
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
