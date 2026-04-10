# Polar Chart

A radial segment chart with grid circles, labels, and interactive hover effects.

## Basic Usage

```blade
<x-bt-polar-chart :data="['North' => 100, 'East' => 120, 'South' => 90, 'West' => 110]" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `data` | `array` | `[]` | Chart data — simple associative or objects with label/value |
| `height` | `string` | `h-48 md:h-96` | Tailwind height classes |
| `showLabels` | `bool` | `true` | Show segment labels |
| `legendPosition` | `string` | `right` | Legend position: `right`, `bottom`, `none`, `hidden` |
| `formatValues` | `string` | `%s` | printf format for value labels |
| `label` | `string` | `label` | Key name for label in data objects |
| `value` | `string` | `value` | Key name for value in data objects |
| `labelColor` | `string` | `white` | Label text color (Tailwind class) |
| `showGrid` | `bool` | `true` | Show grid circles and radial lines |
| `gridLevels` | `int` | `4` | Number of grid circles |
| `title` | `string\|null` | `null` | Chart title |
| `border` | `bool` | `true` | Show border around chart |
| `borderColor` | `string\|null` | `null` | Custom border color |
| `backgroundColor` | `string\|null` | `null` | Custom background color |
| `chartColor` | `string\|null` | `null` | Override color palette |

## Dark Mode

Dark mode styles are applied automatically via Tailwind `dark:` classes. Uses Alpine.js for hover state management.
