---
name: bt-charts-component
description: Get detailed information and examples for specific Beartropy Charts components
version: 1.0.0
author: Beartropy
tags: [beartropy, charts, components, documentation, examples]
---

# Beartropy Charts Component Helper

You are an expert in Beartropy Charts components. Use this guide to pick the right chart type and provide accurate props and examples.

All components use the `<x-bt-*>` tag prefix.

---

## Choosing the Right Chart

| User says... | Use this | Why |
|---|---|---|
| "bar chart", "column chart", "compare values" | `<x-bt-bar-chart>` | Vertical bars for comparing discrete values |
| "line chart", "trend over time", "time series" | `<x-bt-line-chart>` | Polyline for trends, supports multiple datasets |
| "pie chart", "proportions", "percentage breakdown" | `<x-bt-pie-chart>` | Circular slices showing proportions |
| "donut chart", "ring chart", "with center label" | `<x-bt-donut-chart>` | Pie with hole, supports center text |
| "radar chart", "spider chart", "multi-axis comparison" | `<x-bt-radar-chart>` | Polygon for multi-dimensional comparison |
| "polar chart", "radial chart" | `<x-bt-polar-chart>` | Radial segments with variable radius |
| "wrap chart in card", "chart with title", "collapsible chart" | `<x-bt-chart-wrapper>` | Consistent styling wrapper |

## Data Format Quick Reference

```php
// Simple (bar, pie, donut, polar)
['Jan' => 100, 'Feb' => 200]

// With colors
[['label' => 'A', 'value' => 100, 'color' => '#3B82F6']]

// Multiple datasets (line, radar)
[['label' => 'Series', 'data' => ['Jan' => 500], 'color' => '#hex']]

// Radar axes
[['label' => 'Product', 'Speed' => 80, 'Power' => 90]]
```
