# x-bt-bar-chart — AI Reference

## Component Tag
```blade
<x-bt-bar-chart :data="['Jan' => 100, 'Feb' => 200]" />
```

## Architecture
- `BarChart` → extends `Illuminate\View\Component`, uses `HasChartStyling` trait
- Namespace: `Beartropy\Charts\Components`
- View: `beartropy-charts::bar-chart`
- SVG-based rendering, no JS dependencies

## Constructor Props

| Prop | PHP Type | Default |
|------|----------|---------|
| `data` | `array` | `[]` |
| `max` | `?float` | `null` |
| `height` | `string` | `'h-48 md:h-64'` |
| `showValues` | `bool` | `true` |
| `showValuesAlways` | `bool` | `false` |
| `color` | `string` | `'beartropy'` |
| `gap` | `string` | `'sm'` |
| `rounded` | `mixed` | `true` |
| `showGrid` | `bool` | `false` |
| `showYAxis` | `bool` | `false` |
| `formatValues` | `string` | `'%s'` |
| `label` | `string` | `'label'` |
| `value` | `string` | `'value'` |
| `title` | `?string` | `null` |
| `border` | `bool` | `true` |
| `borderColor` | `?string` | `null` |
| `backgroundColor` | `?string` | `null` |
| `chartColor` | `?string` | `null` |

## Key Methods
- `prepareItems(): array` — Transforms raw data into display items with colors (from palette) and percentage heights relative to max value.

## Data Format
- Simple: `['Label' => value, ...]`
- Objects: `[['label' => 'L', 'value' => 100, 'color' => '#hex'], ...]`
- Per-item `color` overrides the palette color.

## Color System
- 22-color default palette via `HasChartStyling::getColorPalette()`
- Supports CSS colors (#hex, rgb, hsl) and Tailwind color names
- `getTailwindColorValue()` maps Tailwind names to hex (500 shade)

## Common Pitfalls
- `max` auto-calculates from data if null — set explicitly when comparing multiple charts
- `gap` accepts only preset strings: `xs`, `sm`, `md`, `lg`, `xl`
- `showValues` shows on hover only unless `showValuesAlways` is true
