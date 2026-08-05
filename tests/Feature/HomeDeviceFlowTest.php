<?php

namespace Tests\Feature;

use App\Models\Home;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeDeviceFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_home_and_a_device(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->post(route('homes.store'), [
            'name' => 'Casa principal',
            'address' => 'Calle 123',
        ])->assertRedirect(route('homes.index'));

        $home = Home::where('name', 'Casa principal')->first();

        $this->assertNotNull($home);
        $this->assertTrue($home->members()->where('users.id', $user->id)->exists());

        $this->post(route('devices.store'), [
            'home_id' => $home->id,
            'name' => 'Refrigerador',
            'power_watts' => 120,
            'hours_per_day' => 8,
            'status' => 'active',
        ])->assertRedirect(route('devices.index'));

        $this->assertDatabaseHas('devices', [
            'name' => 'Refrigerador',
            'home_id' => $home->id,
            'user_id' => $user->id,
        ]);
    }
}
