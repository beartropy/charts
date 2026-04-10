# x-bt-donut-chart — AI Reference

## Component Tag
```blade
<x-bt-donut-chart :data="['Done' => 75, 'Left' => 25]" centerText="75%" />
```

## Architecture
- `DonutChart` → extends `Illuminate\View\Component`, uses `HasChartStyling` trait
- Namespace: `Beartropy\Charts\Components`
- View: `beartropy-charts::donut-chart`
- SVG path-based segments with inner/outer radius

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
| `innerRadius` | `float` | `0.6` |
| `centerText` | `?string` | `null` |
| `centerSubtext` | `?string` | `null` |
| `title` | `?string` | `null` |
| `border` | `bool` | `true` |
| `borderColor` | `?string` | `null` |
| `backgroundColor` | `?string` | `null` |
| `chartColor` | `?string` | `null` |

## Key Methods
- `prepareSlices(): array` — Calculates SVG arc paths for donut segments using both inner and outer radius values.
- `extractItems(): array` — Parses raw data into items with colors from palette.

## Common Pitfalls
- `innerRadius` is a ratio (0–1) relative to the outer radius, not pixels
- Higher `innerRadius` = thinner ring; lower = thicker ring
- `centerText` and `centerSubtext` are only visible when `innerRadius` leaves enough space
