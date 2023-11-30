<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\Sequence;

class PlannerDashboardTest extends TestCase
{
    use refreshDatabase;

    public function test_a_planner_can_filter_events(): void
    {
        $planner = $this->signIn('planner');
        
        $events = Event::factory()->for($user)
            ->count(10)
            ->state(new Sequence(
                ['is_active' => 1],
                ['is_active' => 0]
            ))->create();

        /** 
         * 1. display only active events where url query includes 'active'
         * 2. display only inactive events where url query includes 'inactive'
         * 3. do not permit both 'active' and 'inactive' to display in url query.
         */

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
