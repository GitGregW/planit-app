<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Database\Seeders\UserGroupSeeder;
use App\Models\User;
use App\Models\UserGroup;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function signIn($userGroupName){
        $this->seed(UserGroupSeeder::class);
        $userGroup = UserGroup::where('name', $userGroupName)->first();
        $user = User::factory()->for($userGroup)->create();
        $this->actingAs($user);
        return $user;
    }
}
