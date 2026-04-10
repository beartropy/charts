# x-bt-chart-wrapper — AI Reference

## Component Tag
```blade
<x-bt-chart-wrapper title="Title">
    <x-bt-bar-chart :data="$data" :border="false" />
</x-bt-chart-wrapper>
```

## Architecture
- `ChartWrapper` → extends `Illuminate\View\Component`
- Namespace: `Beartropy\Charts\Components`
- View: `beartropy-charts::chart-wrapper`
- Provides consistent border, background, title, and optional collapse around any chart

## Constructor Props

| Prop | PHP Type | Default |
|------|----------|---------|
| `title` | `mixed` | `null` |
| `border` | `bool` | `true` |
| `borderColor` | `?string` | `null` |
| `backgroundColor` | `?string` | `null` |
| `collapsible` | `bool` | `false` |

## Common Patterns
- Wrap charts with `:border="false"` on the inner chart to avoid double borders
- Use `collapsible` for dashboard layouts where users can hide/show charts
- `title` can be a string prop or a slot for custom title markup
