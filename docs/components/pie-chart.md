# Pie Chart

An SVG pie chart with percentage labels and a configurable legend.

## Basic Usage

```blade
<x-bt-pie-chart :data="['Red' => 30, 'Blue' => 50, 'Yellow' => 20]" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `data` | `array` | `[]` | Chart data — simple associative or objects with label/value |
| `height` | `string` | `h-48 md:h-64` | Tailwind height classes |
| `showLabels` | `bool` | `true` | Show percentage labels on slices |
| `legendPosition` | `string` | `right` | Legend position: `right`, `bottom`, `none`, `hidden` |
| `formatValues` | `string` | `%s` | printf format for value labels |
| `label` | `string` | `label` | Key name for label in data objects |
| `value` | `string` | `value` | Key name for value in data objects |
| `labelColor` | `string` | `white` | Label text color (Tailwind class) |
| `title` | `string\|null` | `null` | Chart title |
| `border` | `bool` | `true` | Show border around chart |
| `borderColor` | `string\|null` | `null` | Custom border color |
| `backgroundColor` | `string\|null` | `null` | Custom background color |
| `chartColor` | `string\|null` | `null` | Override color palette |

## Custom Colors

```blade
<x-bt-pie-chart :data="[
    ['label' => 'Desktop', 'value' => 65, 'color' => '#3B82F6'],
    ['label' => 'Mobile', 'value' => 30, 'color' => '#10B981'],
    ['label' => 'Tablet', 'value' => 5, 'color' => '#F59E0B'],
]" />
```

## Dark Mode

Dark mode styles are applied automatically via Tailwind `dark:` classes.
