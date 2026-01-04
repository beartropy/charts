<?php

use Illuminate\Support\Facades\Blade;

it('can render basic donut chart component', function () {
    $html = Blade::render('<x-bt-donut-chart />');
    expect($html)
        ->toContain('flex flex-col')
        ->toContain('viewBox="0 0 100 100"');
});

it('renders slices based on data', function () {
    $data = [50, 50];
    $html = Blade::render('<x-bt-donut-chart :data="$data" />', ['data' => $data]);
    
    // Should have 2 paths for the donut slices
    // Donut chart logic creates paths for each slice.
    $count = substr_count($html, '<path');
    expect($count)->toBeGreaterThanOrEqual(2);
});

it('renders labels from data', function () {
    $data = [
        ['label' => 'Apples', 'value' => 10],
        ['label' => 'Bananas', 'value' => 20],
    ];
    $html = Blade::render('<x-bt-donut-chart :data="$data" />', ['data' => $data]);
    
    expect($html)
        ->toContain('Apples')
        ->toContain('Bananas');
});

it('automatically assigns colors', function () {
    $data = [10, 20];
    $html = Blade::render('<x-bt-donut-chart :data="$data" />', ['data' => $data]);
    
    expect($html)->toContain('-500');
});

it('supports custom inner radius', function () {
    // Cannot easily check the exact path calculation without complex regex or parsing,
    // but we can ensure it renders without error with the prop.
    // Maybe check if it affects some other exposed property or just smoke test.
    $data = [10, 20];
    $html = Blade::render('<x-bt-donut-chart :data="$data" :innerRadius="0.8" />', ['data' => $data]);
    expect($html)->toContain('<svg');
});

it('supports center text', function () {
    $data = [10, 20];
    $html = Blade::render('<x-bt-donut-chart :data="$data" centerText="Total" centerSubtext="30" />', ['data' => $data]);
    
    expect($html)
        ->toContain('Total')
        ->toContain('30');
});

it('can format values', function () {
    $data = [50];
    $html = Blade::render('<x-bt-donut-chart :data="$data" formatValues="$%s" />', ['data' => $data]);
    
    expect($html)->toContain('$50');
});

it('can render title', function () {
    $html = Blade::render('<x-bt-donut-chart title="Donut Sales" />');
    expect($html)->toContain('Donut Sales');
});
