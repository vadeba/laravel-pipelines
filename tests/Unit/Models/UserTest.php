<?php

use App\Models\CreativityType;
use App\Models\MasterClass;
use App\Models\User;

test('user has fillable attributes', function () {
    $user = User::factory()->create();

    expect($user->name)->toBeString();
    expect($user->email)->toBeString();
});

test('user has created master classes relationship', function () {
    $master = User::factory()->create(['role' => 'master']);
    $type = CreativityType::factory()->create();
    $masterClass = MasterClass::factory()->create([
        'master_id' => $master->id,
        'type_id' => $type->id,
    ]);

    expect($master->createdMasterClasses)->toHaveCount(1);
    expect($master->createdMasterClasses->first()->id)->toBe($masterClass->id);
});

test('user has enrollments relationship', function () {
    $user = User::factory()->create();
    $type = CreativityType::factory()->create();
    $master = User::factory()->create(['role' => 'master']);
    $masterClass = MasterClass::factory()->create([
        'master_id' => $master->id,
        'type_id' => $type->id,
    ]);

    $user->enrollments()->attach($masterClass->id);

    expect($user->enrollments)->toHaveCount(1);
});

test('user password is hashed', function () {
    $user = User::factory()->create([
        'password' => 'plain-password',
    ]);

    expect($user->password)->not->toBe('plain-password');
    expect(password_verify('plain-password', $user->password))->toBeTrue();
});

test('hidden attributes are not serialized', function () {
    $user = User::factory()->create();
    $array = $user->toArray();

    expect($array)->not->toHaveKey('password');
    expect($array)->not->toHaveKey('remember_token');
});
