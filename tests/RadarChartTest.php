<?php

use Illuminate\Support\Facades\Blade;

it('can render basic radar chart component', function () {
    $html = Blade::render('<x-bt-radar-chart />');
    expect($html)
        ->toContain('flex flex-col')
        ->toContain('viewBox="0 0 120 120"');
});

it('renders polygon for dataset', function () {
    $data = [
        ['label' => 'A', 'data' => ['Speed' => 50, 'Power' => 80]],
    ];
    $html = Blade::render('<x-bt-radar-chart :data="$data" />', ['data' => $data]);
    
    // Look for <polygon points="...">
    expect($html)->toContain('<polygon');
});

it('renders axes labels', function () {
    $data = [
        ['label' => 'A', 'data' => ['Speed' => 50, 'Power' => 80]],
    ];
    $html = Blade::render('<x-bt-radar-chart :data="$data" />', ['data' => $data]);
    
    expect($html)
        ->toContain('Speed')
        ->toContain('Power');
});

it('automatically assigns colors', function () {
    $data = [
        ['label' => 'A', 'data' => ['Speed' => 50]],
        ['label' => 'B', 'data' => ['Speed' => 60]],
    ];
    $html = Blade::render('<x-bt-radar-chart :data="$data" />', ['data' => $data]);
    
    expect($html)->toContain('-500');
});

it('can show grid', function () {
    $html = Blade::render('<x-bt-radar-chart :showGrid="true" />');
    // Polygon for grid levels
    expect($html)->toContain('stroke="currentColor"'); 
});

it('can show values', function () {
    $data = [
        ['label' => 'A', 'data' => ['Speed' => 50]],
    ];
    // Default showValues is false
    $htmlOff = Blade::render('<x-bt-radar-chart :data="$data" />', ['data' => $data]);
    
    // Not easily checkable if it's absent without exact selector, but let's check positive case
    
    $htmlOn = Blade::render('<x-bt-radar-chart :data="$data" :showValues="true" />', ['data' => $data]);
    expect($htmlOn)->toContain('50'); // The value should be rendered
});

it('can render title', function () {
    $html = Blade::render('<x-bt-radar-chart title="Radar Stats" />');
    expect($html)->toContain('Radar Stats');
});
