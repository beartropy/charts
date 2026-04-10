# x-bt-pie-chart — AI Reference

## Component Tag
```blade
<x-bt-pie-chart :data="['Red' => 30, 'Blue' => 50]" />
```

## Architecture
- `PieChart` → extends `Illuminate\View\Component`, uses `HasChartStyling` trait
- Namespace: `Beartropy\Charts\Components`
- View: `beartropy-charts::pie-chart`
- SVG path-based slices with labels positioned at centroid

## Constructor Props

| Prop | PHP Type | Default |
|------|----------|---------|
| `data` | `array` | `[]` |
| `height` | `string` | `'h-48 md:h-64'` |
| `showLabels` | `bool` | `true` |
| `legendPosition` | `string` | `'right'` |
| `formatValues` | `string` | `'%s'` |
| `label` | `string` | `'label'` |
| `value` | `string` | `'value'` |
| `labelColor` | `string` | `'white'` |
| `title` | `?string` | `null` |
| `border` | `bool` | `true` |
| `borderColor` | `?string` | `null` |
| `backgroundColor` | `?string` | `null` |
| `chartColor` | `?string` | `null` |

## Key Methods
- `prepareSlices(): array` — Calculates SVG arc paths for each slice with percentage labels positioned at the centroid of each slice.
- `extractItems(): array` — Parses raw data into `[label, value, color]` items using the color palette.

## Common Pitfalls
- Very small slices may have overlapping labels — consider hiding labels for data with many small values
- `labelColor` is a Tailwind text color class, not a hex value
