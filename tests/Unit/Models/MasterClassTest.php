<?php

use App\Models\CreativityType;
use App\Models\MasterClass;
use App\Models\User;

beforeEach(function () {
    $this->type = CreativityType::factory()->create();
    $this->master = User::factory()->create(['role' => 'master']);
});

test('master class has fillable attributes', function () {
    $mc = MasterClass::factory()->create([
        'master_id' => $this->master->id,
        'type_id' => $this->type->id,
    ]);

    expect($mc->title)->toBeString();
    expect($mc->date)->toBeString();
    expect($mc->price)->toBeNumeric();
});

test('master class belongs to master', function () {
    $mc = MasterClass::factory()->create([
        'master_id' => $this->master->id,
        'type_id' => $this->type->id,
    ]);

    expect($mc->master->id)->toBe($this->master->id);
});

test('master class belongs to type', function () {
    $mc = MasterClass::factory()->create([
        'master_id' => $this->master->id,
        'type_id' => $this->type->id,
    ]);

    expect($mc->type->id)->toBe($this->type->id);
});

test('master class has participants', function () {
    $mc = MasterClass::factory()->create([
        'master_id' => $this->master->id,
        'type_id' => $this->type->id,
        'max_seats' => 10,
    ]);
    $user = User::factory()->create();

    $mc->participants()->attach($user->id);

    expect($mc->participants)->toHaveCount(1);
});

test('master class calculates free seats', function () {
    $mc = MasterClass::factory()->create([
        'master_id' => $this->master->id,
        'type_id' => $this->type->id,
        'max_seats' => 10,
    ]);

    expect($mc->free_seats)->toBe(10);

    $user = User::factory()->create();
    $mc->participants()->attach($user->id);

    $mc->refresh();
    expect($mc->free_seats)->toBe(9);
});
