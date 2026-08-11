<?php

use Backstage\Mails\Laravel\Models\Mail;
use Backstage\Mails\Resources\MailResource;
use Backstage\Mails\Tests\Fixtures\Team;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Cache;

it('scopes cached status counts to the current tenant', function () {
    Cache::flush();
    config()->set('mails.cache.counts_ttl', 60);

    $firstTeam = Team::create(['name' => 'First']);
    $secondTeam = Team::create(['name' => 'Second']);

    filament()->setCurrentPanel('tenancy');
    Filament::setTenant($firstTeam, true);

    Mail::factory()->create();

    expect(MailResource::getStatusCounts()['all'])->toBe(1);

    Mail::factory()->create();

    expect(MailResource::getStatusCounts()['all'])->toBe(1);

    Filament::setTenant($secondTeam, true);

    expect(MailResource::getStatusCounts()['all'])->toBe(2);
});
