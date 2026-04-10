# Line Chart

A polyline chart with support for multiple datasets, data points, and legends.

## Basic Usage

```blade
<x-bt-line-chart :data="['Jan' => 500, 'Feb' => 800, 'Mar' => 600]" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `data` | `array` | `[]` | Chart data — single dataset or multiple datasets |
| `max` | `float\|null` | `null` | Maximum Y-axis value; auto-calculated if null |
| `height` | `string` | `h-48 md:h-64` | Tailwind height classes |
| `showGrid` | `bool` | `true` | Show horizontal grid lines |
| `showYAxis` | `bool` | `true` | Show Y-axis labels |
| `formatValues` | `string` | `%s` | printf format for value labels |
| `label` | `string` | `label` | Key name for label in data objects |
| `value` | `string` | `value` | Key name for value in data objects |
| `showPoints` | `bool` | `true` | Show data point dots |
| `dataLabels` | `string` | `hover` | Label visibility: `hover`, `always`, `none` |
| `xAxisTitle` | `string\|null` | `null` | X-axis title |
| `yAxisTitle` | `string\|null` | `null` | Y-axis title |
| `legendPosition` | `string` | `bottom` | Legend position: `right`, `bottom`, `none`, `hidden` |
| `title` | `string\|null` | `null` | Chart title |
| `border` | `bool` | `true` | Show border around chart |
| `borderColor` | `string\|null` | `null` | Custom border color |
| `backgroundColor` | `string\|null` | `null` | Custom background color |
| `chartColor` | `string\|null` | `null` | Override color palette |

## Multiple Datasets

```blade
<x-bt-line-chart :data="[
    ['label' => 'Revenue', 'data' => ['Jan' => 500, 'Feb' => 800, 'Mar' => 600], 'color' => '#3B82F6'],
    ['label' => 'Expenses', 'data' => ['Jan' => 400, 'Feb' => 600, 'Mar' => 550], 'color' => '#EF4444'],
]" legendPosition="bottom" />
```

## Axis Titles

```blade
<x-bt-line-chart
    :data="$data"
    xAxisTitle="Month"
    yAxisTitle="Revenue ($)"
    formatValues="$%s"
/>
```

## Dark Mode

Dark mode styles are applied automatically via Tailwind `dark:` classes.
