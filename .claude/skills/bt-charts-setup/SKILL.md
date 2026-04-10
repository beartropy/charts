---
name: bt-charts-setup
description: Help users install and configure Beartropy Charts in their Laravel/Livewire projects
version: 1.0.0
author: Beartropy
tags: [beartropy, charts, installation, setup, configuration]
---

# Beartropy Charts Setup Guide

You are an expert in helping users install and configure Beartropy Charts in their Laravel applications.

---

## Requirements

- PHP >= 8.2
- Laravel >= 11.x
- Livewire >= 3.x
- Tailwind CSS configured

---

## Installation

### Step 1: Install via Composer

```bash
composer require beartropy/charts
```

### Step 2: Use in Blade Templates

No additional setup required. Components are auto-registered:

```blade
<x-bt-bar-chart :data="['Jan' => 100, 'Feb' => 200, 'Mar' => 150]" />
```

---

## Available Chart Components

| Component | Tag | Description |
|---|---|---|
| Bar Chart | `<x-bt-bar-chart>` | Vertical bar chart |
| Line Chart | `<x-bt-line-chart>` | Polyline chart with multiple datasets |
| Pie Chart | `<x-bt-pie-chart>` | Pie chart with percentage labels |
| Donut Chart | `<x-bt-donut-chart>` | Pie with inner hole and center text |
| Radar Chart | `<x-bt-radar-chart>` | Polygon radar chart |
| Polar Chart | `<x-bt-polar-chart>` | Radial segment chart |
| Chart Wrapper | `<x-bt-chart-wrapper>` | Consistent styling wrapper |

---

## Data Formats

### Simple Associative Array
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

### Multiple Datasets (Line/Radar)
```php
$data = [
    ['label' => 'Revenue', 'data' => ['Q1' => 500, 'Q2' => 800], 'color' => '#3B82F6'],
    ['label' => 'Expenses', 'data' => ['Q1' => 400, 'Q2' => 600], 'color' => '#EF4444'],
];
```

---

## Common Props

All charts share these props:

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `data` | `array` | `[]` | Chart data |
| `height` | `string` | `h-48 md:h-64` | Tailwind height classes |
| `title` | `string\|null` | `null` | Chart title |
| `border` | `bool` | `true` | Show border |
| `chartColor` | `string\|null` | `null` | Override color palette |
| `formatValues` | `string` | `%s` | printf format for values |

---

## Livewire Integration

Charts are Livewire-compatible — pass reactive data:

```blade
<x-bt-bar-chart :data="$this->chartData" />
```

```php
#[Computed]
public function chartData(): array
{
    return Order::query()
        ->selectRaw("DATE_FORMAT(created_at, '%b') as month, SUM(total) as total")
        ->groupByRaw("MONTH(created_at)")
        ->pluck('total', 'month')
        ->toArray();
}
```

---

## Dark Mode

All charts include dark mode support automatically via Tailwind `dark:` classes. No configuration needed.

---

## Troubleshooting

### Charts not rendering
- Ensure Tailwind CSS is configured and compiling
- Check that the `data` prop is a valid array (not null)

### Colors not showing
- Custom colors must be valid CSS (`#hex`, `rgb()`, `hsl()`) or Tailwind color names
- The default palette has 22 colors — data with more items will cycle through them
