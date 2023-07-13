<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
use App\Models\UserGroup;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $seed = true;

    public function signIn($userGroupName){
        $userGroup = UserGroup::where('name', $userGroupName)->first();
        $user = User::factory()->for($userGroup)->create();
        $this->actingAs($user);
        return $user;
    }
}
