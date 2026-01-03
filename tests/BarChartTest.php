<?php

use Beartropy\Charts\Components\BarChart;
use Livewire\Livewire;

it('can render basic bar chart component', function () {
    Livewire::test(BarChart::class)
        ->assertSee('flex');
});

it('renders bars based on data', function () {
    $data = [50, 100];
    Livewire::test(BarChart::class, ['data' => $data])
        ->assertSee('style="height: 50%"', false)
        ->assertSee('style="height: 100%"', false);
});

it('renders labels from array keys', function () {
    $data = ['January' => 10];
    Livewire::test(BarChart::class, ['data' => $data])
        ->assertSee('January');
});

it('renders labels from complex data structure', function () {
    $data = [
        ['label' => 'February', 'value' => 20]
    ];
    Livewire::test(BarChart::class, ['data' => $data])
        ->assertSee('February')
        ->assertSee('style="height: 100%"', false);
});

it('respects max value for scaling', function () {
    $data = [50];
    Livewire::test(BarChart::class, ['data' => $data, 'max' => 100])
        ->assertSee('style="height: 50%"', false);
});

it('automatically assigns colors', function () {
    $data = [10, 20];
    $component = Livewire::test(BarChart::class, ['data' => $data]);
    
    // Check for color classes
    $component->assertSeeHtml('-500');
});

it('supports custom label and value keys', function () {
    $data = [
        ['name' => 'John', 'score' => 50],
        ['name' => 'Jane', 'score' => 80],
    ];
    Livewire::test(BarChart::class, ['data' => $data, 'label' => 'name', 'value' => 'score'])
        ->assertSee('John')
        ->assertSee('Jane')
        ->assertSee('style="height: 62.5%"', false);
});

it('supports custom gap', function () {
    Livewire::test(BarChart::class, ['gap' => 'lg'])
        ->assertSee('space-x-6');
});

it('supports disabling rounded bars', function () {
    Livewire::test(BarChart::class, ['rounded' => false])
        ->assertDontSee('rounded-t');
});

it('can show grid lines', function () {
    Livewire::test(BarChart::class, ['showGrid' => true])
        ->assertSee('border-t border-gray-100');
});

it('can show y-axis', function () {
    $data = [100];
    Livewire::test(BarChart::class, ['data' => $data, 'showYAxis' => true])
        ->assertSee('100')
        ->assertSee('50')
        ->assertSee('0');
});

it('can format values', function () {
    $data = [50];
    Livewire::test(BarChart::class, ['data' => $data, 'formatValues' => '$%s'])
        ->assertSee('$50');
});

it('can render title', function () {
    Livewire::test(BarChart::class, ['title' => 'Monthly Revenue'])
        ->assertSee('Monthly Revenue')
        ->assertSeeHtml('<h3');
});
