<?php

use App\Models\CreativityType;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cabinet shows user view for regular user', function () {
    $user = User::factory()->create(['role' => 'user']);

    $this->actingAs($user);

    $response = $this->get('/cabinet');

    $response->assertStatus(200);
});

test('cabinet shows master view for master', function () {
    $master = User::factory()->create(['role' => 'master']);

    $this->actingAs($master);

    $response = $this->get('/cabinet');

    $response->assertStatus(200);
});

test('cabinet shows master classes for master', function () {
    $master = User::factory()->create(['role' => 'master']);
    $type = CreativityType::factory()->create();
    $mc = MasterClass::factory()->create([
        'master_id' => $master->id,
        'type_id' => $type->id,
    ]);

    $this->actingAs($master);

    $response = $this->get('/cabinet');

    $response->assertStatus(200);
});

test('cabinet shows enrollments for user', function () {
    $user = User::factory()->create(['role' => 'user']);
    $master = User::factory()->create(['role' => 'master']);
    $type = CreativityType::factory()->create();
    $mc = MasterClass::factory()->create([
        'master_id' => $master->id,
        'type_id' => $type->id,
    ]);

    $user->enrollments()->attach($mc->id);

    $this->actingAs($user);

    $response = $this->get('/cabinet');

    $response->assertStatus(200);
});
