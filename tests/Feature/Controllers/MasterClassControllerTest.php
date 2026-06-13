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
});

test('master can access create page', function () {
    $this->actingAs($this->master);

    $response = $this->get('/master-classes/create');

    $response->assertStatus(200);
});

test('user cannot access create page', function () {
    $this->actingAs($this->user);

    $response = $this->get('/master-classes/create');

    $response->assertSessionHasErrors();
    $response->assertRedirect('/cabinet');
});

test('guest cannot access create page', function () {
    $response = $this->get('/master-classes/create');

    $response->assertRedirect('/login');
});

test('master can create master class', function () {
    $this->actingAs($this->master);

    $response = $this->post('/master-classes', [
        'type_id' => $this->type->id,
        'title' => 'New Master Class',
        'description' => 'Description of the master class',
        'date' => now()->addDays(1)->format('Y-m-d'),
        'time_slot' => '09:00',
        'max_seats' => 10,
        'price' => 50.00,
    ]);

    $response->assertRedirect('/cabinet');
    $response->assertSessionHas('success');
    expect(MasterClass::where('title', 'New Master Class')->exists())->toBeTrue();
});

test('master cannot create master class with occupied slot', function () {
    MasterClass::factory()->create([
        'master_id' => $this->master->id,
        'type_id' => $this->type->id,
        'date' => now()->addDays(1)->format('Y-m-d'),
        'time_slot' => '09:00',
    ]);

    $this->actingAs($this->master);

    $response = $this->post('/master-classes', [
        'type_id' => $this->type->id,
        'title' => 'Another Master Class',
        'description' => 'Description',
        'date' => now()->addDays(1)->format('Y-m-d'),
        'time_slot' => '09:00',
        'max_seats' => 10,
        'price' => 50.00,
    ]);

    $response->assertSessionHasErrors();
});

test('master class creation validates required fields', function () {
    $this->actingAs($this->master);

    $response = $this->post('/master-classes', [
        'type_id' => '',
        'title' => '',
        'description' => '',
        'date' => '',
        'time_slot' => '',
        'max_seats' => '',
        'price' => '',
    ]);

    $response->assertSessionHasErrors(['type_id', 'title', 'description', 'date', 'time_slot', 'max_seats', 'price']);
});

test('master class creation rejects past dates', function () {
    $this->actingAs($this->master);

    $response = $this->post('/master-classes', [
        'type_id' => $this->type->id,
        'title' => 'Past Master Class',
        'description' => 'Description',
        'date' => now()->subDays(1)->format('Y-m-d'),
        'time_slot' => '09:00',
        'max_seats' => 10,
        'price' => 50.00,
    ]);

    $response->assertSessionHasErrors('date');
});

test('master can access edit page', function () {
    $mc = MasterClass::factory()->create([
        'master_id' => $this->master->id,
        'type_id' => $this->type->id,
    ]);

    $this->actingAs($this->master);

    $response = $this->get('/master-classes/'.$mc->id.'/edit');

    $response->assertStatus(200);
});

test('master cannot edit another masters class', function () {
    $otherMaster = User::factory()->create(['role' => 'master']);
    $mc = MasterClass::factory()->create([
        'master_id' => $otherMaster->id,
        'type_id' => $this->type->id,
    ]);

    $this->actingAs($this->master);

    $response = $this->get('/master-classes/'.$mc->id.'/edit');

    $response->assertStatus(403);
});

test('master can update master class', function () {
    $mc = MasterClass::factory()->create([
        'master_id' => $this->master->id,
        'type_id' => $this->type->id,
    ]);

    $this->actingAs($this->master);

    $response = $this->put('/master-classes/'.$mc->id, [
        'description' => 'Updated description',
        'price' => 75.00,
    ]);

    $response->assertRedirect('/cabinet');
    $mc->refresh();
    expect($mc->description)->toBe('Updated description');
    expect((float) $mc->price)->toBe(75.0);
});

test('user cannot create master class (403)', function () {
    $this->actingAs($this->user);

    $response = $this->post('/master-classes', [
        'type_id' => $this->type->id,
        'title' => 'Attempt',
        'description' => 'Description',
        'date' => now()->addDays(1)->format('Y-m-d'),
        'time_slot' => '09:00',
        'max_seats' => 10,
        'price' => 50.00,
    ]);

    $response->assertStatus(403);
});

test('get occupied slots returns json', function () {
    $mc = MasterClass::factory()->create([
        'master_id' => $this->master->id,
        'type_id' => $this->type->id,
        'date' => now()->addDays(1)->format('Y-m-d'),
        'time_slot' => '09:00',
    ]);

    $this->actingAs($this->master);

    $response = $this->get('/api/occupied-slots?date='.now()->addDays(1)->format('Y-m-d'));

    $response->assertJson(['09:00']);
});
