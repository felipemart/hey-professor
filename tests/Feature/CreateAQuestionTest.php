<?php

declare(strict_types = 1);

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('should be able to create a new question bigger than 255 caracters', function () {
    $user = User::factory()->create();
    actingAs($user);

    $request = post(route('questions.store'), [
        'question' => str_repeat('a', 260) . '?',
    ]);

    $request->assertRedirect(route('dashboard'));
    assertDatabaseCount('questions', 1);
    assertDatabaseHas(
        'questions',
        [
            'question' => str_repeat('a', 260) . '?',
        ]
    );
});

it('should check if ends with a question mark ?', function () {
    $user = User::factory()->create();
    actingAs($user);

    $request = post(route('questions.store'), [
        'question' => str_repeat('a', 10),
    ]);

    $request->assertSessionHasErrors(['question' => 'Tem certeza que quer enviar uma pergunta? esta pergunta não tem sem uma interrogação!']);
    assertDatabaseCount('questions', 0);
});

it('should have at least 10 characters', function () {
    $user = User::factory()->create();
    actingAs($user);

    $request = post(route('questions.store'), [
        'question' => str_repeat('a', 8) . '?',
    ]);

    $request->assertSessionHasErrors(['question' => __(key: 'validation.min.string', replace: ['attribute' => 'question', 'min' => 10])]);
    assertDatabaseCount('questions', 0);
});
