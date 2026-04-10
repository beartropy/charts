# Chart Wrapper

A wrapper component that provides consistent styling (border, background, title, collapse) around any chart.

## Basic Usage

```blade
<x-bt-chart-wrapper title="Monthly Revenue">
    <x-bt-bar-chart :data="$data" :border="false" />
</x-bt-chart-wrapper>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | `string\|null` | `null` | Chart title (string or slot) |
| `border` | `bool` | `true` | Show border |
| `borderColor` | `string\|null` | `null` | Custom border color |
| `backgroundColor` | `string\|null` | `null` | Custom background color |
| `collapsible` | `bool` | `false` | Make title clickable to collapse/expand content |

## Collapsible

```blade
<x-bt-chart-wrapper title="Sales Data" :collapsible="true">
    <x-bt-line-chart :data="$data" :border="false" />
</x-bt-chart-wrapper>
```

## Dark Mode

Dark mode styles are applied automatically via Tailwind `dark:` classes.
