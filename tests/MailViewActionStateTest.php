<?php

use Backstage\Mails\Laravel\Models\Mail;
use Backstage\Mails\Resources\MailResource\Pages\ListMails;
use Backstage\Mails\Tests\Fixtures\User;
use Livewire\Livewire;

it('does not serialize mail content into the view action state', function () {
    filament()->setCurrentPanel('admin');

    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $mail = Mail::factory()->create([
        'html' => '<script>window.emailScriptRan = true</script><p>Secret HTML</p>',
        'text' => 'Secret text',
    ]);

    $component = Livewire::actingAs($user)
        ->test(ListMails::class)
        ->call('mountTableAction', 'view', (string) $mail->getKey());

    expect($component->get('mountedActions.0.data'))->toBe([]);
});
