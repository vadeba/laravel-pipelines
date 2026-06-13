<?php

use App\Models\CreativityType;
use App\Models\Enrollment;
use App\Models\MasterClass;
use App\Models\User;

test('enrollment belongs to user', function () {
    $user = User::factory()->create();
    $master = User::factory()->create(['role' => 'master']);
    $type = CreativityType::factory()->create();
    $mc = MasterClass::factory()->create([
        'master_id' => $master->id,
        'type_id' => $type->id,
    ]);

    $enrollment = Enrollment::create([
        'user_id' => $user->id,
        'master_class_id' => $mc->id,
    ]);

    expect($enrollment->user->id)->toBe($user->id);
});

test('enrollment belongs to master class', function () {
    $user = User::factory()->create();
    $master = User::factory()->create(['role' => 'master']);
    $type = CreativityType::factory()->create();
    $mc = MasterClass::factory()->create([
        'master_id' => $master->id,
        'type_id' => $type->id,
    ]);

    $enrollment = Enrollment::create([
        'user_id' => $user->id,
        'master_class_id' => $mc->id,
    ]);

    expect($enrollment->masterClass->id)->toBe($mc->id);
});

test('enrollment has fillable attributes', function () {
    $user = User::factory()->create();
    $master = User::factory()->create(['role' => 'master']);
    $type = CreativityType::factory()->create();
    $mc = MasterClass::factory()->create([
        'master_id' => $master->id,
        'type_id' => $type->id,
    ]);

    $enrollment = Enrollment::create([
        'user_id' => $user->id,
        'master_class_id' => $mc->id,
    ]);

    expect($enrollment->user_id)->toBe($user->id);
    expect($enrollment->master_class_id)->toBe($mc->id);
});
