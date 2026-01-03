<?php

use Beartropy\Charts\Components\LineChart;
use Livewire\Livewire;

it('can render basic line chart component', function () {
    Livewire::test(LineChart::class)
        ->assertSee('flex flex-col');
});

it('renders lines for simple data', function () {
    $data = [10, 20, 30];
    Livewire::test(LineChart::class, ['data' => $data])
        ->assertSeeHtml('<polyline')
        ->assertSeeHtml('points="');
});

it('renders multiple datasets', function () {
    $data = [
        ['label' => 'A', 'values' => [10, 20]],
        ['label' => 'B', 'values' => [30, 40]]
    ];
    $component = Livewire::test(LineChart::class, ['data' => $data]);
    
    // Check colors
    $component->assertSee('text-yellow-500');
    $component->assertSee('text-slate-500');
});

it('can show y-axis and grid', function () {
    $data = [100];
    Livewire::test(LineChart::class, ['data' => $data, 'showGrid' => true, 'showYAxis' => true])
        ->assertSee('border-t border-gray-200')
        ->assertSee('100');
});

it('can toggle points', function () {
    $data = [10];
    Livewire::test(LineChart::class, ['data' => $data, 'showPoints' => true])
        ->assertSee('w-2 h-2 rounded-full');

    Livewire::test(LineChart::class, ['data' => $data, 'showPoints' => false])
        ->assertDontSee('w-2 h-2 rounded-full');
});

it('supports data labels configuration', function () {
    $data = [10];
    
    // Hover (default)
    Livewire::test(LineChart::class, ['data' => $data])
        ->assertSee('opacity-0 group-hover:opacity-100');
    
    // Always visible
    Livewire::test(LineChart::class, ['data' => $data, 'dataLabels' => 'always'])
        ->assertSee('opacity-100')
        ->assertDontSee('group-hover:opacity-100');

    // None
    Livewire::test(LineChart::class, ['data' => $data, 'dataLabels' => 'none'])
        ->assertDontSee('formatted_value');
});

it('can render title', function () {
    Livewire::test(LineChart::class, ['title' => 'Monthly Revenue'])
        ->assertSee('Monthly Revenue')
        ->assertSeeHtml('<h3');
});

it('can render axes titles', function () {
    Livewire::test(LineChart::class, ['xAxisTitle' => 'Month', 'yAxisTitle' => 'Revenue'])
        ->assertSee('Month')
        ->assertSee('Revenue');
});

it('renders x-axis ticks from keys', function () {
    $data = ['Jan' => 10, 'Feb' => 20];
    Livewire::test(LineChart::class, ['data' => $data])
        ->assertSee('Jan')
        ->assertSee('Feb');
});
