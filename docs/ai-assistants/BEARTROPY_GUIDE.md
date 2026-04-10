# Beartropy Charts - Universal AI Assistant Guide

> This guide helps AI assistants generate correct code using Beartropy Charts for Laravel/Livewire applications.

## Overview

**Beartropy Charts** is a collection of SVG-based chart components for the TALL stack.

- **6 Chart Types**: BarChart, LineChart, PieChart, DonutChart, RadarChart, PolarChart
- **1 Wrapper**: ChartWrapper for consistent styling
- **Features**: Dark mode, custom colors, Livewire-compatible, responsive, no JS dependencies (Alpine.js for radar/polar hover only)

## Component Tags

All components use the `x-bt-` prefix:

```blade
<x-bt-bar-chart :data="$data" />
<x-bt-line-chart :data="$data" />
<x-bt-pie-chart :data="$data" />
<x-bt-donut-chart :data="$data" />
<x-bt-radar-chart :data="$data" />
<x-bt-polar-chart :data="$data" />
<x-bt-chart-wrapper title="Title">...</x-bt-chart-wrapper>
```

## Data Formats

### Simple Associative Array (Bar, Pie, Donut, Polar)
```php
$data = ['Jan' => 100, 'Feb' => 200, 'Mar' => 150];
```

### Objects with Custom Colors
```php
$data = [
    ['label' => 'Sales', 'value' => 300, 'color' => '#3B82F6'],
    ['label' => 'Returns', 'value' => 50, 'color' => '#EF4444'],
];
```

### Single Line Dataset
```php
$data = ['Jan' => 500, 'Feb' => 800, 'Mar' => 600];
```

### Multiple Line Datasets
```php
$data = [
    ['label' => 'Revenue', 'data' => ['Jan' => 500, 'Feb' => 800], 'color' => '#3B82F6'],
    ['label' => 'Expenses', 'data' => ['Jan' => 400, 'Feb' => 600], 'color' => '#EF4444'],
];
```

### Radar Data
```php
$data = [
    ['label' => 'Product A', 'Speed' => 80, 'Power' => 90, 'Cost' => 60],
    ['label' => 'Product B', 'Speed' => 70, 'Power' => 85, 'Cost' => 45],
];
```

## Common Props (All Charts)

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `data` | `array` | `[]` | Chart data |
| `height` | `string` | `h-48 md:h-64` | Tailwind height classes |
| `title` | `string\|null` | `null` | Chart title |
| `border` | `bool` | `true` | Show border |
| `borderColor` | `string\|null` | `null` | Custom border color |
| `backgroundColor` | `string\|null` | `null` | Custom background color |
| `chartColor` | `string\|null` | `null` | Override color palette |
| `formatValues` | `string` | `%s` | printf format for values |

## Chart-Specific Props

### BarChart
- `showValues` (bool, true) — Show value labels
- `showValuesAlways` (bool, false) — Always visible vs hover only
- `gap` (string, 'sm') — Bar gap: xs, sm, md, lg, xl
- `rounded` (mixed, true) — Bar border radius
- `showGrid` (bool, false) — Horizontal grid lines
- `showYAxis` (bool, false) — Y-axis labels
- `color` (string, 'beartropy') — Default color

### LineChart
- `showPoints` (bool, true) — Data point dots
- `dataLabels` (string, 'hover') — hover, always, none
- `showGrid` (bool, true) — Grid lines
- `showYAxis` (bool, true) — Y-axis labels
- `legendPosition` (string, 'bottom') — right, bottom, none, hidden
- `xAxisTitle` / `yAxisTitle` (string|null)

### PieChart / DonutChart
- `showLabels` (bool, true) — Percentage labels on slices
- `legendPosition` (string, 'right') — right, bottom, none, hidden
- `labelColor` (string, 'white') — Label text color

### DonutChart Only
- `innerRadius` (float, 0.6) — Hole size 0–1
- `centerText` (string|null) — Center hole text
- `centerSubtext` (string|null) — Center subtext

### RadarChart
- `showLabels` (bool, true) — Axis labels
- `showValues` (bool, false) — Value labels on points
- `showGrid` (bool, true) — Grid circles
- `gridLevels` (int, 5) — Number of grid circles
- `showAxes` (bool, true) — Radial axis lines
- `fillArea` (bool, true) — Fill polygon
- `fillOpacity` (float, 0.2) — Fill opacity

### PolarChart
- `showLabels` (bool, true) — Segment labels
- `showGrid` (bool, true) — Grid circles and radial lines
- `gridLevels` (int, 4) — Number of grid circles

## Color System

22-color default palette. Custom colors via:
- Tailwind color names: `chartColor="blue"`
- CSS colors: `chartColor="#3B82F6"`
- Per-item: `['label' => 'A', 'value' => 10, 'color' => '#hex']`

## Livewire Integration

Charts are Livewire-compatible — pass reactive data:

```blade
<x-bt-bar-chart :data="$this->chartData" />
```

```php
// In Livewire component
public function getChartDataProperty(): array
{
    return Order::query()
        ->selectRaw("DATE_FORMAT(created_at, '%b') as month, SUM(total) as total")
        ->groupByRaw("DATE_FORMAT(created_at, '%b')")
        ->pluck('total', 'month')
        ->toArray();
}
```
