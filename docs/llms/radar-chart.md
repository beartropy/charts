# x-bt-radar-chart — AI Reference

## Component Tag
```blade
<x-bt-radar-chart :data="[['label' => 'A', 'Speed' => 80, 'Power' => 90]]" />
```

## Architecture
- `RadarChart` → extends `Illuminate\View\Component`, uses `HasChartStyling` trait
- Namespace: `Beartropy\Charts\Components`
- View: `beartropy-charts::radar-chart`
- SVG polygon rendering with Alpine.js for interactive hover effects

## Constructor Props

| Prop | PHP Type | Default |
|------|----------|---------|
| `data` | `array` | `[]` |
| `height` | `string` | `'h-48 md:h-96'` |
| `showLabels` | `bool` | `true` |
| `showValues` | `bool` | `false` |
| `legendPosition` | `string` | `'bottom'` |
| `formatValues` | `string` | `'%s'` |
| `label` | `string` | `'label'` |
| `value` | `string` | `'value'` |
| `showGrid` | `bool` | `true` |
| `gridLevels` | `int` | `5` |
| `showAxes` | `bool` | `true` |
| `fillArea` | `bool` | `true` |
| `fillOpacity` | `float` | `0.2` |
| `showLegend` | `bool` | `true` |
| `title` | `?string` | `null` |
| `border` | `bool` | `true` |
| `borderColor` | `?string` | `null` |
| `backgroundColor` | `?string` | `null` |
| `chartColor` | `?string` | `null` |

## Key Methods
- `prepareChartData(): array` — Extracts axes from data keys, normalizes datasets, calculates polygon points for SVG rendering.
- `calculatePolygonPoints(array $points, array $axes, float $maxValue): string` — Generates SVG polygon points string from data values mapped to radial coordinates.

## Data Format
- Flat: `[['label' => 'A', 'Speed' => 80, 'Power' => 90, ...]]` — axis names are the non-label keys
- Nested: `[['label' => 'A', 'data' => ['Speed' => 80, 'Power' => 90], 'color' => '#hex']]`

## Common Pitfalls
- Axes are extracted from data keys — all datasets must use the same axis names
- Uses Alpine.js for hover state (unlike other charts which are pure SVG)
- `fillOpacity` only takes effect when `fillArea` is true
