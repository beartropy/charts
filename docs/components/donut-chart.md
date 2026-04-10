# Donut Chart

A pie chart with an inner hole, optional center text, and configurable legend.

## Basic Usage

```blade
<x-bt-donut-chart :data="['Complete' => 75, 'Remaining' => 25]" centerText="75%" />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `data` | `array` | `[]` | Chart data — simple associative or objects with label/value |
| `height` | `string` | `h-48 md:h-96` | Tailwind height classes |
| `showLabels` | `bool` | `true` | Show percentage labels on slices |
| `legendPosition` | `string` | `right` | Legend position: `right`, `bottom`, `none`, `hidden` |
| `formatValues` | `string` | `%s` | printf format for value labels |
| `label` | `string` | `label` | Key name for label in data objects |
| `value` | `string` | `value` | Key name for value in data objects |
| `labelColor` | `string` | `white` | Label text color (Tailwind class) |
| `innerRadius` | `float` | `0.6` | Hole size as ratio (0–1, where 1 = outer radius) |
| `centerText` | `string\|null` | `null` | Text displayed in center hole |
| `centerSubtext` | `string\|null` | `null` | Subtext below center text |
| `title` | `string\|null` | `null` | Chart title |
| `border` | `bool` | `true` | Show border around chart |
| `borderColor` | `string\|null` | `null` | Custom border color |
| `backgroundColor` | `string\|null` | `null` | Custom background color |
| `chartColor` | `string\|null` | `null` | Override color palette |

## Center Text

```blade
<x-bt-donut-chart
    :data="$data"
    centerText="85%"
    centerSubtext="Completion"
    :innerRadius="0.7"
/>
```

## Dark Mode

Dark mode styles are applied automatically via Tailwind `dark:` classes.
