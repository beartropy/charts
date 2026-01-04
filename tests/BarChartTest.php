<?php

use Illuminate\Support\Facades\Blade;

it('can render basic bar chart component', function () {
    $html = Blade::render('<x-bt-bar-chart />');
    expect($html)
        ->toContain('flex');
});

it('renders bars based on data', function () {
    $data = [50, 100];
    $html = Blade::render('<x-bt-bar-chart :data="$data" />', ['data' => $data]);
    
    expect($html)
        ->toContain('style="height: 50%;')
        ->toContain('style="height: 100%;');
});

it('renders labels from array keys', function () {
    $data = ['January' => 10];
    $html = Blade::render('<x-bt-bar-chart :data="$data" />', ['data' => $data]);
    
    expect($html)->toContain('January');
});

it('renders labels from complex data structure', function () {
    $data = [
        ['label' => 'February', 'value' => 20]
    ];
    $html = Blade::render('<x-bt-bar-chart :data="$data" />', ['data' => $data]);
    
    expect($html)
        ->toContain('February')
        ->toContain('style="height: 100%;');
});

it('respects max value for scaling', function () {
    $data = [50];
    $html = Blade::render('<x-bt-bar-chart :data="$data" :max="100" />', ['data' => $data]);
    
    expect($html)->toContain('style="height: 50%;');
});

it('automatically assigns colors', function () {
    $data = [10, 20];
    $html = Blade::render('<x-bt-bar-chart :data="$data" />', ['data' => $data]);
    
    // Check for color classes
    expect($html)->toContain('-500');
});

it('supports custom label and value keys', function () {
    $data = [
        ['name' => 'John', 'score' => 50],
        ['name' => 'Jane', 'score' => 80],
    ];
    $html = Blade::render('<x-bt-bar-chart :data="$data" label="name" value="score" />', ['data' => $data]);
    
    expect($html)
        ->toContain('John')
        ->toContain('Jane')
        ->toContain('style="height: 62.5%;');
});

it('supports custom gap', function () {
    $html = Blade::render('<x-bt-bar-chart gap="lg" />');
    expect($html)->toContain('space-x-6');
});

it('can show grid lines', function () {
    $html = Blade::render('<x-bt-bar-chart :showGrid="true" />');
    expect($html)->toContain('border-t border-gray-100');
});

it('can show y-axis', function () {
    $data = [100];
    $html = Blade::render('<x-bt-bar-chart :data="$data" :showYAxis="true" />', ['data' => $data]);
    
    expect($html)
        ->toContain('100')
        ->toContain('50')
        ->toContain('0');
});

it('can format values', function () {
    $data = [50];
    $html = Blade::render('<x-bt-bar-chart :data="$data" formatValues="$%s" />', ['data' => $data]);
    
    expect($html)->toContain('$50');
});

it('can render title', function () {
    $html = Blade::render('<x-bt-bar-chart title="Monthly Revenue" />');
    
    expect($html)
        ->toContain('Monthly Revenue')
        ->toContain('<h3');
});

it('supports showValuesAlways prop', function () {
    $data = [50];
    
    // Default (false) - should have group-hover
    $htmlDefault = Blade::render('<x-bt-bar-chart :data="$data" />', ['data' => $data]);
    expect($htmlDefault)->toContain('group-hover:opacity-100');
    
    // Enabled - should NOT have opacity-0 or group-hover dependency for visibility
    // Note: implementation might be different, let's check for what marks it as always visible
    // Based on previous knowledge, it likely removes 'opacity-0' class
    $htmlAlways = Blade::render('<x-bt-bar-chart :data="$data" :showValuesAlways="true" />', ['data' => $data]);
    expect($htmlAlways)->not->toContain('opacity-0');
});
