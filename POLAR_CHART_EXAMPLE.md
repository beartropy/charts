# Polar Chart Example

## Basic Usage

```php
<x-bt-polar-chart 
    :data="[
        'Sales' => 85,
        'Marketing' => 60,
        'Development' => 95,
        'Support' => 70,
        'Design' => 80,
    ]"
    title="Department Performance"
/>
```

## With Custom Colors

```php
<x-bt-polar-chart 
    :data="[
        ['label' => 'Q1', 'value' => 45, 'color' => 'blue'],
        ['label' => 'Q2', 'value' => 68, 'color' => 'green'],
        ['label' => 'Q3', 'value' => 82, 'color' => 'orange'],
        ['label' => 'Q4', 'value' => 95, 'color' => 'purple'],
    ]"
    title="Quarterly Growth"
    legend-position="bottom"
/>
```

## Available Props

- `data` (array): Data to display (required)
- `title` (string): Chart title
- `height` (string): Height class (default: 'h-80')
- `chartColor` (string|array): Color(s) for the segments
- `backgroundColor` (string): Background color
- `border` (bool): Show border (default: true)
- `borderColor` (string): Border color
- `showLabels` (bool): Show value labels (default: true)
- `legendPosition` (string): 'right', 'bottom', 'none' (default: 'right')
- `formatValues` (string): sprintf format for values (default: '%s')
- `label` (string): Key for label in array data (default: 'label')
- `value` (string): Key for value in array data (default: 'value')
- `labelColor` (string): Color for labels (default: 'white')
- `showGrid` (bool): Show grid circles and lines (default: true)
- `gridLevels` (int): Number of grid circles (default: 4)

## Features

- ✅ Radial/polar visualization
- ✅ Interactive hover effects with shadows
- ✅ Customizable grid (circles and radial lines)
- ✅ Legend with flexible positioning
- ✅ Support for custom colors (Tailwind, CSS, hex)
- ✅ Responsive design
- ✅ Dark mode support
- ✅ Auto-registration via service provider
