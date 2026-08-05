<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Home;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeviceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_a_device_and_displays_it(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/devices', [
            'name' => 'Refrigerador',
            'power' => 120,
            'hours_per_day' => 8,
        ]);

        $response->assertRedirect('/devices');

        $this->assertDatabaseHas('devices', [
            'name' => 'Refrigerador',
            'power' => '120.00',
        ]);

        $response = $this->get('/devices');
        $response->assertSee('Refrigerador');
    }

    public function test_a_member_can_update_and_delete_a_device_from_a_home_they_belong_to(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $home = Home::create([
            'name' => 'Casa de prueba',
            'address' => 'Calle 123',
            'user_id' => $owner->id,
            'code' => 'TEST',
        ]);

        $home->members()->attach($member->id, ['role' => 'member']);

        $device = Device::create([
            'home_id' => $home->id,
            'name' => 'Lavadora',
            'type' => 'general',
            'power' => 300,
        ]);

        $this->actingAs($member)
            ->put('/devices/' . $device->id, [
                'name' => 'Lavadora nueva',
                'power' => 350,
                'home_id' => $home->id,
            ])
            ->assertRedirect('/devices');

        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'name' => 'Lavadora nueva',
            'power' => '350.00',
        ]);

        $this->actingAs($member)
            ->delete('/devices/' . $device->id)
            ->assertRedirect('/devices');

        $this->assertDatabaseMissing('devices', [
            'id' => $device->id,
        ]);
    }
}
