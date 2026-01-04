<?php

use Illuminate\Support\Facades\Blade;

it('can render basic line chart component', function () {
    $html = Blade::render('<x-bt-line-chart />');
    expect($html)
        ->toContain('flex flex-col');
});

it('renders lines for simple data', function () {
    $data = [10, 20, 30];
    $html = Blade::render('<x-bt-line-chart :data="$data" />', ['data' => $data]);
    
    expect($html)
        ->toContain('<polyline')
        ->toContain('points="');
});

it('renders multiple datasets', function () {
    $data = [
        ['label' => 'A', 'values' => [10, 20]],
        ['label' => 'B', 'values' => [30, 40]]
    ];
    $html = Blade::render('<x-bt-line-chart :data="$data" />', ['data' => $data]);
    
    // Check polylines exist (should have 2)
    $count = substr_count($html, '<polyline');
    expect($count)->toBeGreaterThanOrEqual(2);
});

it('can show y-axis and grid', function () {
    $data = [100];
    $html = Blade::render('<x-bt-line-chart :data="$data" :showGrid="true" :showYAxis="true" />', ['data' => $data]);
    
    expect($html)
        ->toContain('border-t border-gray-200') // Check for grid lines
        ->toContain('100');
});

it('can toggle points', function () {
    $data = [10];
    
    // Points visible by default or when enabled (checking logic from component might be needed, assuming showPoints=true makes them visible) 
    // Usually points are small circles
    $htmlWith = Blade::render('<x-bt-line-chart :data="$data" :showPoints="true" />', ['data' => $data]);
    expect($htmlWith)->toContain('w-2 h-2 rounded-full');

    $htmlWithout = Blade::render('<x-bt-line-chart :data="$data" :showPoints="false" />', ['data' => $data]);
    expect($htmlWithout)->not->toContain('w-2 h-2 rounded-full');
});

it('supports data labels configuration', function () {
    $data = [10];
    
    // Hover (default)
    $htmlHover = Blade::render('<x-bt-line-chart :data="$data" />', ['data' => $data]);
    expect($htmlHover)->toContain('opacity-0 group-hover:opacity-100');
    
    // Always visible
    $htmlAlways = Blade::render('<x-bt-line-chart :data="$data" dataLabels="always" />', ['data' => $data]);
    expect($htmlAlways)
        ->toContain('opacity-100')
        ->not->toContain('group-hover:opacity-100');

    // None
    $htmlNone = Blade::render('<x-bt-line-chart :data="$data" dataLabels="none" />', ['data' => $data]);
    // Assuming 'formatted_value' logic puts value in a span/div
    expect($htmlNone)->not->toContain('opacity-0 group-hover:opacity-100'); 
});

it('can render title', function () {
    $html = Blade::render('<x-bt-line-chart title="Monthly Revenue" />');
    
    expect($html)
        ->toContain('Monthly Revenue')
        ->toContain('<h3');
});

it('can render axes titles', function () {
    $html = Blade::render('<x-bt-line-chart xAxisTitle="Month" yAxisTitle="Revenue" />');
    
    expect($html)
        ->toContain('Month')
        ->toContain('Revenue');
});

it('renders x-axis ticks from keys', function () {
    $data = ['Jan' => 10, 'Feb' => 20];
    $html = Blade::render('<x-bt-line-chart :data="$data" />', ['data' => $data]);
    
    expect($html)
        ->toContain('Jan')
        ->toContain('Feb');
});
