<?php

namespace Tests\Feature;

use Tests\TestCase;

class SimpleTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    public function test_example()
    {
        $user = \App\Models\User::factory()->create();
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }
}
