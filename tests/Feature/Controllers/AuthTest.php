<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('registration page is accessible', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('user can register', function () {
    $response = $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
        'password' => 'password',
    ]);

    $response->assertRedirect('/cabinet');
    $this->assertAuthenticated();
    expect(User::where('email', 'john@example.com')->exists())->toBeTrue();
});

test('user cannot register with existing email', function () {
    User::factory()->create(['email' => 'john@example.com']);

    $response = $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('email');
});

test('user cannot register with existing phone', function () {
    User::factory()->create(['phone' => '+1234567890']);

    $response = $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('phone');
});

test('registration requires valid data', function () {
    $response = $this->post('/register', [
        'name' => '',
        'email' => 'invalid',
        'phone' => 'short',
        'password' => '12',
    ]);

    $response->assertSessionHasErrors(['name', 'email', 'phone', 'password']);
});

test('login page is accessible', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('user can login', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/cabinet');
    $this->assertAuthenticated();
});

test('user cannot login with wrong password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('user can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user);
    $this->assertAuthenticated();

    $response = $this->post('/logout');

    $response->assertRedirect('/');
    $this->assertGuest();
});

test('guest cannot access cabinet', function () {
    $response = $this->get('/cabinet');

    $response->assertRedirect('/login');
});
