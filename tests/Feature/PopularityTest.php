<?php

use App\Models\Series;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('gets the total visit count', function () {
    $series = Series::factory()->create();

    $series->visit();

    $series = Series::withTotalVisitCount()->first();

    expect($series->visit_count_total)->toEqual(1);
});

it('get records by all time popularity', function () {
    Series::factory()->times(2)->create()->each->visit();

    $popularSeries = Series::factory()->create();
    Carbon::setTestNow(now()->subDays(2));
    $popularSeries->visit();
    Carbon::setTestNow();
    $popularSeries->visit();

    $series = Series::latest()->popularAllTime()->get();

    expect($series->count())->toBe(3);
    expect($series->first()->visit_count_total)->toEqual(2);
});

it('gets popular records between two dates', function () {
    $series = Series::factory()->times(2)->create();

    Carbon::setTestNow(Carbon::createFromDate(1989, 11, 16));
    $series[0]->visit();

    Carbon::setTestNow();
    $series[0]->visit();
    $series[1]->visit();

    $series = Series::popularBetween(Carbon::createFromDate(1989, 11, 15), Carbon::createFromDate(1989, 11, 17))->get();

    expect($series->count())->toBe(1);
    expect($series[0]->visit_count)->toEqual(1);
});

it('gets popular records by the last x days', function () {
    $series = Series::factory()->times(2)->create();

    Carbon::setTestNow(now()->subDays(4));
    $series[0]->visit();

    Carbon::setTestNow();
    $series[1]->visit();

    $series = Series::popularLastDays(2)->get();

    expect($series->count())->toBe(1);
});

it('gets popular records by the last week', function () {
    $series = Series::factory()->times(2)->create();

    Carbon::setTestNow(now()->subDays(7)->startOfWeek());
    $series[0]->visit();

    Carbon::setTestNow();
    $series[1]->visit();

    $series = Series::popularLastWeek()->get();

    expect($series->count())->toBe(1);
});

it('gets popular records by this week', function () {
    $series = Series::factory()->times(2)->create();

    Carbon::setTestNow(now()->subWeek()->subDay());
    $series[0]->visit();

    Carbon::setTestNow();
    $series[1]->visit();

    $series = Series::popularThisWeek()->get();

    expect($series->count())->toBe(1);
});

it('gets popular records by this month', function () {
    $series = Series::factory()->times(2)->create();

    Carbon::setTestNow(now()->subMonth()->subDay());
    $series[0]->visit();

    Carbon::setTestNow();
    $series[1]->visit();

    $series = Series::popularThisMonth()->get();

    expect($series->count())->toBe(1);
});

it('gets popular records by last month', function () {
    $series = Series::factory()->times(2)->create();

    Carbon::setTestNow(now()->subMonth()->startOfMonth());
    $series[0]->visit();

    Carbon::setTestNow();
    $series[1]->visit();

    $series = Series::popularLastMonth()->get();

    expect($series->count())->toBe(1);
});

it('gets popular records by this year', function () {
    $series = Series::factory()->times(2)->create();

    Carbon::setTestNow(now()->subYear()->subDay());
    $series[0]->visit();

    Carbon::setTestNow();
    $series[1]->visit();

    $series = Series::popularThisYear()->get();

    expect($series->count())->toBe(1);
});

it('gets popular records by last year', function () {
    $series = Series::factory()->times(2)->create();

    Carbon::setTestNow(now()->subYear()->startOfYear());
    $series[0]->visit();

    Carbon::setTestNow();
    $series[1]->visit();

    $series = Series::popularLastYear()->get();

    expect($series->count())->toBe(1);
});
