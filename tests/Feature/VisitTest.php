<?php

use App\Models\Series;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('it creates a visit', function () {
    $series = Series::factory()->create();

    $series->visit();

    expect($series->visits->count())->toBe(1);
});

it('it creates a visit with the default ip address', function () {
    $series = Series::factory()->create();

    $series->visit()->withIp();

    expect($series->visits->first()->data)->toMatchArray(['ip' => request()->ip()]);
});

it('it creates a visit with the given ip address', function () {
    $series = Series::factory()->create();

    $series->visit()->withIp('cats');

    expect($series->visits->first()->data)->toMatchArray(['ip' => 'cats']);
});

it('it creates a visit with custom data', function () {
    $series = Series::factory()->create();

    $series->visit()->withData([
        'cats' => true
    ]);

    expect($series->visits->first()->data)->toMatchArray(['cats' => true]);
});

it('it creates a visit with the default user', function () {
    $this->actingAs($user = User::factory()->create());

    $series = Series::factory()->create();

    $series->visit()->withUser();

    expect($series->visits->first()->data)->toMatchArray(['user_id' => $user->id]);
});

it('it creates a visit with the given user', function () {
    $user = User::factory()->create();
    $series = Series::factory()->create();

    $series->visit()->withUser($user);

    expect($series->visits->first()->data)->toMatchArray(['user_id' => $user->id]);
});

it('it does not create dupliate visits with the same data', function () {
    $series = Series::factory()->create();

    $series->visit()->withData([
        'cats' => true
    ]);

    $series->visit()->withData([
        'cats' => true
    ]);

    expect($series->visits->count())->toBe(1);
});

it('it does not create visits within the timeframe', function () {
    $series = Series::factory()->create();

    Carbon::setTestNow(now()->subDays(2));
    $series->visit();

    Carbon::setTestNow();
    $series->visit();
    $series->visit();

    expect($series->visits->count())->toBe(2);
});

it('it creates visits after a default daily timeframe', function () {
    $series = Series::factory()->create();

    $series->visit()->withIp();
    Carbon::setTestNow(now()->addDay()->addHour());
    $series->visit()->withIp();

    expect($series->visits->count())->toBe(2);
});

it('it creates visits after a hourly timeframe', function () {
    $series = Series::factory()->create();

    $series->visit()->hourlyIntervals()->withIp();
    Carbon::setTestNow(now()->addHour()->addMinute());
    $series->visit()->hourlyIntervals()->withIp();

    expect($series->visits->count())->toBe(2);
});

it('it creates visits after a daily timeframe', function () {
    $series = Series::factory()->create();

    $series->visit()->dailyInterval()->withIp();
    Carbon::setTestNow(now()->addDay());
    $series->visit()->dailyInterval()->withIp();

    expect($series->visits->count())->toBe(2);
});

it('it creates visits after a weekly timeframe', function () {
    $series = Series::factory()->create();

    $series->visit()->weeklyInterval()->withIp();
    Carbon::setTestNow(now()->addWeek());
    $series->visit()->weeklyInterval()->withIp();

    expect($series->visits->count())->toBe(2);
});

it('it creates visits after a monthly timeframe', function () {
    $series = Series::factory()->create();

    $series->visit()->monthlyInterval()->withIp();
    Carbon::setTestNow(now()->addMonth());
    $series->visit()->monthlyInterval()->withIp();

    expect($series->visits->count())->toBe(2);
});

it('it creates visits after a custom timeframe', function () {
    $series = Series::factory()->create();

    $series->visit()->customInterval(now()->subYear())->withIp();
    Carbon::setTestNow(now()->addYear());
    $series->visit()->customInterval(now()->subYear())->withIp();

    expect($series->visits->count())->toBe(2);
});
