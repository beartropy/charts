<?php

use Illuminate\Support\Facades\Blade;

it('can render basic pie chart component', function () {
    $html = Blade::render('<x-bt-pie-chart />');
    expect($html)
        ->toContain('flex flex-col') // Container class
        ->toContain('viewBox="0 0 100 100"'); // SVG
});

it('renders slices based on data', function () {
    $data = [50, 50];
    $html = Blade::render('<x-bt-pie-chart :data="$data" />', ['data' => $data]);
    
    // Should have 2 paths
    $count = substr_count($html, '<path');
    expect($count)->toBeGreaterThanOrEqual(2);
});

it('renders labels from data', function () {
    $data = [
        ['label' => 'Apples', 'value' => 10],
        ['label' => 'Bananas', 'value' => 20],
    ];
    $html = Blade::render('<x-bt-pie-chart :data="$data" />', ['data' => $data]);
    
    expect($html)
        ->toContain('Apples')
        ->toContain('Bananas');
});

it('automatically assigns colors', function () {
    $data = [10, 20];
    $html = Blade::render('<x-bt-pie-chart :data="$data" />', ['data' => $data]);
    
    // Check for color classes (e.g., text-yellow-500, bg-yellow-500)
    expect($html)->toContain('-500'); 
});

it('supports custom legend positions', function () {
    $data = [10, 20];
    
    // Right (default)
    $htmlRight = Blade::render('<x-bt-pie-chart :data="$data" legendPosition="right" />', ['data' => $data]);
    expect($htmlRight)->toContain('flex-row');
    
    // Bottom
    $htmlBottom = Blade::render('<x-bt-pie-chart :data="$data" legendPosition="bottom" />', ['data' => $data]);
    expect($htmlBottom)->toContain('flex-col');
});

it('supports hiding the legend', function () {
    $data = [10, 20];
    
    // We check for the specific legend container class or structure
    // The legend container has 'overflow-y-auto' which is unique to it in this component
    $htmlHidden = Blade::render('<x-bt-pie-chart :data="$data" legendPosition="none" />', ['data' => $data]);
    
    expect($htmlHidden)->not->toContain('overflow-y-auto');
});

it('can format values', function () {
    $data = [50];
    $html = Blade::render('<x-bt-pie-chart :data="$data" formatValues="$%s" />', ['data' => $data]);
    
    expect($html)->toContain('$50');
});

it('can render title', function () {
    $html = Blade::render('<x-bt-pie-chart title="Market Share" />');
    
    expect($html)->toContain('Market Share');
});

it('can hide percentages on slices', function () {
    $data = [['label' => 'A', 'value' => 100]]; 
    
    // Enabled by default - should have <text> element
    $htmlWith = Blade::render('<x-bt-pie-chart :data="$data" />', ['data' => $data]);
    expect($htmlWith)->toContain('<text');
    
    // Disabled - should NOT have <text> element for labels
    // Note: <text> is only used for the slice labels in this component
    $htmlWithout = Blade::render('<x-bt-pie-chart :data="$data" :showLabels="false" />', ['data' => $data]);
    expect($htmlWithout)->not->toContain('<text');
});
