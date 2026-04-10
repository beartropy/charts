# Bar Chart

A vertical bar chart component with optional grid lines, Y-axis labels, and value display.

## Basic Usage

```blade
<x-bt-bar-chart :data="['Jan' => 100, 'Feb' => 200, 'Mar' => 150]" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `data` | `array` | `[]` | Chart data — simple associative `['Label' => value]` or objects `[['label' => 'Jan', 'value' => 100]]` |
| `max` | `float\|null` | `null` | Maximum Y-axis value; auto-calculated if null |
| `height` | `string` | `h-48 md:h-64` | Tailwind height classes |
| `showValues` | `bool` | `true` | Show value labels above bars |
| `showValuesAlways` | `bool` | `false` | Always show values; `false` = hover only |
| `color` | `string` | `beartropy` | Default color palette name |
| `gap` | `string` | `sm` | Gap between bars: `xs`, `sm`, `md`, `lg`, `xl` |
| `rounded` | `mixed` | `true` | Border radius on bars; `true`/`false` or custom string |
| `showGrid` | `bool` | `false` | Show horizontal grid lines |
| `showYAxis` | `bool` | `false` | Show Y-axis labels |
| `formatValues` | `string` | `%s` | printf format for value labels |
| `label` | `string` | `label` | Key name for label in data objects |
| `value` | `string` | `value` | Key name for value in data objects |
| `title` | `string\|null` | `null` | Chart title |
| `border` | `bool` | `true` | Show border around chart |
| `borderColor` | `string\|null` | `null` | Custom border color |
| `backgroundColor` | `string\|null` | `null` | Custom background color |
| `chartColor` | `string\|null` | `null` | Override color palette |

## Data Formats

```blade
{{-- Simple associative array --}}
<x-bt-bar-chart :data="['Jan' => 100, 'Feb' => 200, 'Mar' => 150]" />

{{-- Objects with custom colors --}}
<x-bt-bar-chart :data="[
    ['label' => 'Sales', 'value' => 300, 'color' => '#3B82F6'],
    ['label' => 'Returns', 'value' => 50, 'color' => '#EF4444'],
]" />
```

## Grid and Y-Axis

```blade
<x-bt-bar-chart
    :data="$data"
    :showGrid="true"
    :showYAxis="true"
    formatValues="$%s"
/>
```

## Styling

```blade
<x-bt-bar-chart
    :data="$data"
    gap="lg"
    :rounded="true"
    height="h-64 md:h-96"
    :border="false"
/>
```

## Dark Mode

Dark mode styles are applied automatically via Tailwind `dark:` classes.
