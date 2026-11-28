<?php

use App\Models\User;
use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new users can register', function () {
    $response = $this->post(route('register.store'), [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'coordinator', // doit être ignoré : le rôle est fixé par le serveur
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'first_name' => 'Test',
        'last_name' => 'User',
        'role' => 'student',
        'section_id' => null,
    ]);
});

test('registration ignores optional profile fields', function () {
    $this->post(route('register.store'), [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'national_number' => '00000000000',
        'address' => 'Rue du Test 1',
        'phone' => '0470000000',
        'section_id' => 1,
    ]);

    $user = User::where('email', 'test@example.com')->firstOrFail();

    expect($user->national_number)->toBeNull()
        ->and($user->address)->toBeNull()
        ->and($user->phone)->toBeNull()
        ->and($user->section_id)->toBeNull();
});
