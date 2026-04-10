# x-bt-polar-chart — AI Reference

## Component Tag
```blade
<x-bt-polar-chart :data="['North' => 100, 'East' => 120]" />
```

## Architecture
- `PolarChart` → extends `Illuminate\View\Component`, uses `HasChartStyling` trait
- Namespace: `Beartropy\Charts\Components`
- View: `beartropy-charts::polar-chart`
- SVG path-based radial segments with Alpine.js for hover effects

## Constructor Props

| Prop | PHP Type | Default |
|------|----------|---------|
| `data` | `array` | `[]` |
| `height` | `string` | `'h-48 md:h-96'` |
| `showLabels` | `bool` | `true` |
| `legendPosition` | `string` | `'right'` |
| `formatValues` | `string` | `'%s'` |
| `label` | `string` | `'label'` |
| `value` | `string` | `'value'` |
| `labelColor` | `string` | `'white'` |
| `showGrid` | `bool` | `true` |
| `gridLevels` | `int` | `4` |
| `title` | `?string` | `null` |
| `border` | `bool` | `true` |
| `borderColor` | `?string` | `null` |
| `backgroundColor` | `?string` | `null` |
| `chartColor` | `?string` | `null` |

## Key Methods
- `prepareSegments(): array` — Calculates SVG arc paths for polar segments where each segment's radius is proportional to its value.
- `extractItems(): array` — Parses data into items with colors from palette.

## Common Pitfalls
- Segment radius is proportional to value — large value differences create very different segment sizes
- Uses Alpine.js for hover state management
