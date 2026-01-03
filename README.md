<div align="center">
    <h1>🐻 Beartropy Charts</h1>
    <p><strong>A powerful chart component library for the TALL stack</strong></p>
    <p>Tailwind • Alpine • Laravel • Livewire</p>
</div>

<div align="center">
    <a href="https://packagist.org/packages/beartropy/charts"><img src="https://img.shields.io/packagist/v/beartropy/charts.svg?style=flat-square&color=indigo" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/beartropy/charts"><img src="https://img.shields.io/packagist/dt/beartropy/charts.svg?style=flat-square&color=blue" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/beartropy/charts"><img src="https://img.shields.io/packagist/l/beartropy/charts?style=flat-square&color=slate" alt="License"></a>
</div>

<br>

A comprehensive chart component library specifically designed for the TALL stack (Tailwind, Alpine, Laravel, Livewire).

## 📚 Documentation

The full documentation for this package involves installation, configuration, and advanced usage examples.

👉 **[Read the full documentation at beartropy.com/charts](https://beartropy.com/charts)**

## ✨ Key Features

*   **Diverse Chart Types**: Includes Bar, Line, and Pie charts out of the box.
*   **TALL Stack Optimized**: Built seamlessly for Tailwind CSS, Alpine.js, Laravel, and Livewire integration.
*   **Customizable**: Easily configure colors, labels, and data sets.
*   **Reactive**: Works perfectly with Livewire for real-time data updates.
*   **Developer Friendly**: Simple API for quick integration.

## 🚀 Quick Installation

You can install the package via composer:

```bash
composer require beartropy/charts
```

## 💡 Basic Usage

### Bar Chart

```blade
<x-bar-chart
    :labels="['Jan', 'Feb', 'Mar']"
    :datasets="[
        [
            'label' => 'Sales',
            'data' => [100, 200, 150],
            'backgroundColor' => '#4F46E5',
        ]
    ]"
/>
```

### Line Chart

```blade
<x-line-chart
    :labels="['Jan', 'Feb', 'Mar']"
    :datasets="[
        [
            'label' => 'Visitors',
            'data' => [500, 800, 600],
            'borderColor' => '#10B981',
            'fill' => false,
        ]
    ]"
/>
```

### Pie Chart

```blade
<x-pie-chart
    :labels="['Red', 'Blue', 'Yellow']"
    :datasets="[
        [
            'data' => [30, 50, 20],
            'backgroundColor' => ['#EF4444', '#3B82F6', '#F59E0B'],
        ]
    ]"
/>
```

## 🤝 Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

> [!NOTE]
> **Disclaimer**: This software is provided "as is", without warranty of any kind, express or implied. Use at your own risk.