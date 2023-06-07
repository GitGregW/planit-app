<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\UserGroupSeeder;
use Illuminate\Support\Facades\Auth;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register/attendee');
        $response->assertStatus(200);

        $response = $this->get('/register/planner');
        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $this->withoutExceptionHandling();
        $this->seed(UserGroupSeeder::class);
        
        /** As an Attendee user group */
        $response = $this->post('/register/attendee', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
        Auth::logout();
        
        /** As a Planner user group */
        $response = $this->post('/register/planner', [
            'name' => 'Test User 2',
            'email' => 'tes2t@example.com',
            'password' => 'password', 
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
        Auth::logout();

        /** As an unknown user group */
        $response = $this->post('/register/foo', [
            'name' => 'Test User 3',
            'email' => 'test3@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertRedirect('/register');
    }
}
