<?php

namespace Tests\Feature;

use App\Models\Home;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_a_home_with_the_authenticated_user_id(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/homes', [
            'name' => 'Casa 2',
            'address' => 'La Morena',
        ]);

        $response->assertRedirect('/homes');

        $home = Home::where('name', 'Casa 2')->first();

        $this->assertNotNull($home);
        $this->assertNotEmpty($home->code);
        $this->assertSame(4, strlen($home->code));
        $this->assertDatabaseHas('homes', [
            'name' => 'Casa 2',
            'address' => 'La Morena',
            'user_id' => $user->id,
            'code' => $home->code,
        ]);
    }

    public function test_owner_can_remove_a_member_from_the_home(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $home = Home::create([
            'code' => 'ABCD',
            'name' => 'Casa admin',
            'address' => 'Centro',
            'user_id' => $owner->id,
        ]);

        $home->members()->attach($member->id, ['role' => 'member']);

        $response = $this->actingAs($owner)->delete(route('homes.members.remove', [$home, $member]));

        $response->assertRedirect();
        $this->assertDatabaseMissing('home_user', [
            'home_id' => $home->id,
            'user_id' => $member->id,
        ]);
    }
}
