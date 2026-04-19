<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

test('dashboard assigns default user role and redirects to client dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertRedirect(route('client.dashboard', absolute: false));
    expect($user->fresh()->hasRole('user'))->toBeTrue();
});

test('owners are redirected from dashboard to owner panel', function () {
    Role::findOrCreate('owner');

    $user = User::factory()->create();
    $user->assignRole('owner');

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertRedirect(route('owner.dashboard', absolute: false));
});

test('legacy business role is normalized to owner role', function () {
    Role::findOrCreate('business');

    $user = User::factory()->create();
    $user->assignRole('business');

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertRedirect(route('owner.dashboard', absolute: false));
    expect($user->fresh()->hasRole('owner'))->toBeTrue();
});
