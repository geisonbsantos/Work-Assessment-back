<?php

use App\Events\UserUpdatedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Event;

it('dispara UserUpdatedEvent ao criar usuário (H6)', function () {
    Event::fake([UserUpdatedEvent::class]);

    User::factory()->create();

    Event::assertDispatched(UserUpdatedEvent::class, fn ($e) => $e->action === 'created');
});

it('dispara UserUpdatedEvent ao atualizar usuário (H6)', function () {
    $user = User::factory()->create();
    Event::fake([UserUpdatedEvent::class]);

    $user->update(['name' => 'Outro Nome']);

    Event::assertDispatched(UserUpdatedEvent::class, fn ($e) => $e->action === 'updated');
});

it('não dispara "updated" quando só o soft delete muda (H6)', function () {
    $user = User::factory()->create();
    Event::fake([UserUpdatedEvent::class]);

    $user->delete();

    Event::assertDispatched(UserUpdatedEvent::class, fn ($e) => $e->action === 'deleted');
    Event::assertNotDispatched(UserUpdatedEvent::class, fn ($e) => $e->action === 'updated');
});
