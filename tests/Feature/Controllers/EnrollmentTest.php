<?php

use App\Models\CreativityType;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->type = CreativityType::factory()->create();
    $this->master = User::factory()->create(['role' => 'master']);
    $this->user = User::factory()->create(['role' => 'user']);
    $this->mc = MasterClass::factory()->create([
        'master_id' => $this->master->id,
        'type_id' => $this->type->id,
        'max_seats' => 10,
    ]);
});

test('guest cannot access enrollment confirmation', function () {
    $response = $this->get('/enroll/'.$this->mc->id.'/confirm');

    $response->assertRedirect('/login');
});

test('user can access enrollment confirmation', function () {
    $this->actingAs($this->user);

    $response = $this->get('/enroll/'.$this->mc->id.'/confirm');

    $response->assertStatus(200);
});

test('user can enroll in master class', function () {
    $this->actingAs($this->user);

    $response = $this->post('/enroll/'.$this->mc->id);

    $response->assertRedirect('/category/'.$this->type->id);
    $response->assertSessionHas('success');
    expect($this->user->enrollments()->where('master_class_id', $this->mc->id)->exists())->toBeTrue();
});

test('user cannot enroll twice', function () {
    $this->user->enrollments()->attach($this->mc->id);

    $this->actingAs($this->user);

    $response = $this->post('/enroll/'.$this->mc->id);

    $response->assertSessionHasErrors();
});

test('user cannot enroll in full master class', function () {
    $this->mc->update(['max_seats' => 1]);
    $anotherUser = User::factory()->create();
    $this->mc->participants()->attach($anotherUser->id);

    $this->actingAs($this->user);

    $response = $this->post('/enroll/'.$this->mc->id);

    $response->assertSessionHasErrors();
});

test('enrollment confirmation shows 404 for non-existent class', function () {
    $this->actingAs($this->user);

    $response = $this->get('/enroll/999/confirm');

    $response->assertStatus(404);
});
