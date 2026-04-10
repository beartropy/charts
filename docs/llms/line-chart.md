# x-bt-line-chart — AI Reference

## Component Tag
```blade
<x-bt-line-chart :data="['Jan' => 500, 'Feb' => 800]" />
```

## Architecture
- `LineChart` → extends `Illuminate\View\Component`, uses `HasChartStyling` trait
- Namespace: `Beartropy\Charts\Components`
- View: `beartropy-charts::line-chart`
- SVG polyline rendering, no JS dependencies

## Constructor Props

| Prop | PHP Type | Default |
|------|----------|---------|
| `data` | `array` | `[]` |
| `max` | `?float` | `null` |
| `height` | `string` | `'h-48 md:h-64'` |
| `showGrid` | `bool` | `true` |
| `showYAxis` | `bool` | `true` |
| `formatValues` | `string` | `'%s'` |
| `label` | `string` | `'label'` |
| `value` | `string` | `'value'` |
| `showPoints` | `bool` | `true` |
| `dataLabels` | `string` | `'hover'` |
| `xAxisTitle` | `?string` | `null` |
| `yAxisTitle` | `?string` | `null` |
| `legendPosition` | `string` | `'bottom'` |
| `title` | `?string` | `null` |
| `border` | `bool` | `true` |
| `borderColor` | `?string` | `null` |
| `backgroundColor` | `?string` | `null` |
| `chartColor` | `?string` | `null` |

## Key Methods
- `prepareDatasets(): array` — Normalizes single or multiple datasets into uniform structure.
- `extractXAxisLabels(): array` — Extracts labels from data for X-axis.
- `extractValues(array $data): array` — Extracts numeric values from dataset.
- `resolveLabelOverlaps(array &$datasets): void` — Prevents label collisions by alternating `label_position` between `top` and `bottom`.

## Data Format
- Single dataset: `['Jan' => 500, 'Feb' => 800]`
- Multiple datasets: `[['label' => 'Series', 'data' => ['Jan' => 500], 'color' => '#hex'], ...]`
- Accepts `data` or `values` key for nested values.

## Common Pitfalls
- Single dataset format auto-wraps into a dataset array internally
- `dataLabels` accepts `hover`, `always`, or `none` — not boolean
- Label overlap resolution only works with 2+ datasets
