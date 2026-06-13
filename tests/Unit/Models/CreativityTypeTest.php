<?php

use App\Models\CreativityType;
use App\Models\MasterClass;
use App\Models\User;

test('creativity type has fillable attributes', function () {
    $type = CreativityType::factory()->create([
        'name' => 'Рисование',
        'description' => 'Уроки рисования',
    ]);

    expect($type->name)->toBe('Рисование');
    expect($type->description)->toBe('Уроки рисования');
});

test('creativity type has master classes', function () {
    $type = CreativityType::factory()->create();
    $master = User::factory()->create(['role' => 'master']);
    $mc = MasterClass::factory()->create([
        'master_id' => $master->id,
        'type_id' => $type->id,
    ]);

    expect($type->masterClasses)->toHaveCount(1);
    expect($type->masterClasses->first()->id)->toBe($mc->id);
});
