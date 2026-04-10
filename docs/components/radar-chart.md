# Radar Chart

A polygon radar chart with grid circles, axis lines, and interactive hover effects.

## Basic Usage

```blade
<x-bt-radar-chart :data="[
    ['label' => 'Product A', 'Speed' => 80, 'Power' => 90, 'Reliability' => 75, 'Cost' => 60],
]" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `data` | `array` | `[]` | Chart data — arrays with axis values |
| `height` | `string` | `h-48 md:h-96` | Tailwind height classes |
| `showLabels` | `bool` | `true` | Show axis labels |
| `showValues` | `bool` | `false` | Show value labels on data points |
| `legendPosition` | `string` | `bottom` | Legend position: `right`, `bottom`, `left`, `none` |
| `formatValues` | `string` | `%s` | printf format for value labels |
| `label` | `string` | `label` | Key name for dataset label |
| `value` | `string` | `value` | Key name for value in nested data |
| `showGrid` | `bool` | `true` | Show concentric grid circles |
| `gridLevels` | `int` | `5` | Number of grid circles |
| `showAxes` | `bool` | `true` | Show radial axis lines |
| `fillArea` | `bool` | `true` | Fill polygon area |
| `fillOpacity` | `float` | `0.2` | Opacity of filled area (0–1) |
| `showLegend` | `bool` | `true` | Show/hide legend |
| `title` | `string\|null` | `null` | Chart title |
| `border` | `bool` | `true` | Show border around chart |
| `borderColor` | `string\|null` | `null` | Custom border color |
| `backgroundColor` | `string\|null` | `null` | Custom background color |
| `chartColor` | `string\|null` | `null` | Override color palette |

## Multiple Datasets

```blade
<x-bt-radar-chart :data="[
    ['label' => 'Product A', 'Speed' => 80, 'Power' => 90, 'Reliability' => 75, 'Cost' => 60],
    ['label' => 'Product B', 'Speed' => 70, 'Power' => 85, 'Reliability' => 88, 'Cost' => 45],
]" legendPosition="bottom" />
```

## Dark Mode

Dark mode styles are applied automatically via Tailwind `dark:` classes. Uses Alpine.js for hover state management.
