<?php

use Illuminate\Support\Facades\Blade;

it('can render basic polar chart component', function () {
    $html = Blade::render('<x-bt-polar-chart />');
    expect($html)
        ->toContain('flex flex-col')
        ->toContain('viewBox="0 0 120 120"'); // Center is 60,60, maxRadius 40 + padding
});

it('renders segments based on data', function () {
    $data = [50, 100];
    $html = Blade::render('<x-bt-polar-chart :data="$data" />', ['data' => $data]);
    
    // Polar chart logic creates paths for each segment.
    $count = substr_count($html, '<path');
    expect($count)->toBeGreaterThanOrEqual(2);
});

it('renders labels from data', function () {
    $data = [
        ['label' => 'North', 'value' => 10],
        ['label' => 'South', 'value' => 20],
    ];
    $html = Blade::render('<x-bt-polar-chart :data="$data" />', ['data' => $data]);
    
    expect($html)
        ->toContain('North')
        ->toContain('South');
});

it('automatically assigns colors', function () {
    $data = [10, 20];
    $html = Blade::render('<x-bt-polar-chart :data="$data" />', ['data' => $data]);
    
    expect($html)->toContain('-500');
});

it('can show grid', function () {
    $html = Blade::render('<x-bt-polar-chart :showGrid="true" />');
    // Provides concentric circles
    expect($html)->toContain('<circle');
});

it('can format values', function () {
    $data = [50];
    $html = Blade::render('<x-bt-polar-chart :data="$data" formatValues="$%s" />', ['data' => $data]);
    
    expect($html)->toContain('$50');
});

it('can render title', function () {
    $html = Blade::render('<x-bt-polar-chart title="Polar Sales" />');
    expect($html)->toContain('Polar Sales');
});
