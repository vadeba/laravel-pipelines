<?php

use App\Models\CreativityType;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('home page shows categories', function () {
    $category = CreativityType::factory()->create();

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee($category->name);
});

test('category page shows master classes', function () {
    $category = CreativityType::factory()->create();
    $master = User::factory()->create(['role' => 'master']);
    $mc = MasterClass::factory()->create([
        'master_id' => $master->id,
        'type_id' => $category->id,
        'date' => now()->addDays(1)->format('Y-m-d'),
    ]);

    $response = $this->get('/category/'.$category->id);

    $response->assertStatus(200);
    $response->assertSee($mc->title);
});

test('category page returns 404 for non-existent category', function () {
    $response = $this->get('/category/999');

    $response->assertStatus(404);
});

test('home page shows enrollments for authenticated user', function () {
    $user = User::factory()->create(['role' => 'user']);
    $category = CreativityType::factory()->create();
    $master = User::factory()->create(['role' => 'master']);
    $mc = MasterClass::factory()->create([
        'master_id' => $master->id,
        'type_id' => $category->id,
        'date' => now()->addDays(1)->format('Y-m-d'),
    ]);

    $this->actingAs($user);
    $user->enrollments()->attach($mc->id);

    $response = $this->get('/');

    $response->assertStatus(200);
});
